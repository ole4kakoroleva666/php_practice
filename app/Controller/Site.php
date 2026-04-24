<?php

namespace Controller;

use Model\User;
use Model\Employee;
use Model\EmployeeDiscipline;
use Model\Department;
use Model\Discipline;
use Src\View;
use Src\Request;
use Src\Auth\Auth;

class Site
{
    private function requireRole(array $roles): void
    {
        if (!Auth::check()) {
            app()->route->redirect('/login');
            exit;
        }

        $userRole = app()->auth::user()->role;

        if ($userRole === 'admin') {
            return;
        }

        if (!in_array($userRole, $roles)) {
            app()->route->redirect('/');
            exit;
        }
    }

    public function index(): string
    {
        return (new View())->render('site.index');
    }

    public function welcome(): string
    {
        if (Auth::check()) {
            return (new View())->render('site.index');
        }

        return (new View())->render('site.welcome', [], 'empty');
    }

    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return (new View())->render('site.login', [], 'empty');
        }

        if (Auth::attempt($request->all())) {
            app()->route->redirect('/');
        }

        return (new View())->render('site.login', [
            'message' => 'Неправильные логин или пароль'
        ], 'empty');
    }

    public function signup(Request $request): string
    {
        if (!empty($_GET)) {
            app()->route->redirect('/signup');
        }

        if ($request->method === 'POST') {
            $validator = new \Src\Validator\Validator($request->all(), [
                'name' => ['required', 'min:2'],
                'login' => ['required', 'min:3', 'unique:users,login'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'min:6']
            ], [
                'required' => 'Поле :field обязательно',
                'min' => 'Поле :field слишком короткое',
                'email' => 'Поле :field должно содержать корректный email',
                'unique' => 'Поле :field должно быть уникально'
            ]);

            if ($validator->fails()) {
                return (new View())->render('site.signup', [
                    'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)
                ], 'empty');
            }

            User::create([
                'name' => $request->get('name'),
                'login' => $request->get('login'),
                'email' => $request->get('email'),
                'password' => md5($request->get('password')),
                'role' => 'decanat'
            ]);

            Auth::attempt([
                'login' => $request->get('login'),
                'password' => $request->get('password')
            ]);

            app()->route->redirect('/');
            exit;
        }

        return (new View())->render('site.signup', [], 'empty');
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }

    public function disciplines(Request $request): string
    {
        $this->requireRole(['decanat']);

        if ($request->method === 'POST') {
            $validator = new \Src\Validator\Validator($request->all(), [
                'name' => ['required', 'min:2', 'unique:disciplines,name']
            ], [
                'required' => 'Название дисциплины обязательно',
                'min' => 'Название дисциплины слишком короткое',
                'unique' => 'Такая дисциплина уже существует'
            ]);

            if ($validator->fails()) {
                return (new View())->render('site.disciplines', [
                    'disciplines' => Discipline::all()->toArray(),
                    'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)
                ]);
            }

            Discipline::create([
                'name' => $request->get('name')
            ]);

            app()->route->redirect('/disciplines');
            exit;
        }

        return (new View())->render('site.disciplines', [
            'disciplines' => Discipline::all()->toArray()
        ]);
    }

    public function employees(Request $request): string
    {
        $this->requireRole(['decanat']);

        $query = Employee::with('department', 'disciplines');

        $search = trim($request->get('search') ?? '');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%");
            });
        }

        $allEmployees = $query->get()->toArray();

        $selectedEmployee = null;
        $selectedEmployeeId = $request->get('employee_id');

        if (!empty($selectedEmployeeId)) {
            $selectedEmployee = Employee::with('department', 'disciplines')
                ->find($selectedEmployeeId);
        }

        return (new View())->render('site.employees', [
            'allEmployees' => $allEmployees,
            'selectedEmployee' => $selectedEmployee,
            'search' => $search
        ]);
    }

    public function employeesCreate(Request $request): string
    {
        $this->requireRole(['decanat']);

        if ($request->method === 'POST') {
            $validator = new \Src\Validator\Validator($request->all(), [
                'last_name' => ['required', 'min:2'],
                'first_name' => ['required', 'min:2'],
                'position' => ['required', 'min:2'],
                'birth_date' => ['required', 'date'],
                'department_id' => ['required']
            ], [
                'required' => 'Поле :field обязательно',
                'min' => 'Поле :field слишком короткое',
                'date' => 'Поле :field содержит некорректную дату'
            ]);

            $errors = $validator->errors();

            if (!Department::find($request->get('department_id'))) {
                $errors['department_id'][] = 'Выбранная кафедра не существует';
            }

            if (isset($_FILES['photo']) && is_array($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                    $errors['photo'][] = 'Ошибка при загрузке изображения';
                } else {
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

                    if (!in_array($ext, $allowedExtensions, true)) {
                        $errors['photo'][] = 'Допустимы только jpg, jpeg, png, webp';
                    } elseif (getimagesize($_FILES['photo']['tmp_name']) === false) {
                        $errors['photo'][] = 'Файл должен быть изображением';
                    }
                }
            }

            if ($validator->fails() || !empty($errors)) {
                return (new View())->render('site.employees_create', [
                    'departments' => Department::all()->toArray(),
                    'message' => json_encode($errors, JSON_UNESCAPED_UNICODE)
                ]);
            }

            $data = $request->all();
            unset($data['csrf_token']);

            if (isset($_FILES['photo']) && is_array($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowedExtensions, true)) {
                    $uploadDir = realpath(__DIR__ . '/../../public') . '/uploads/';

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $filename = uniqid('employee_', true) . '.' . $ext;

                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename)) {
                        $data['photo'] = app()->route->getUrl('/uploads/' . $filename);
                    }
                }
            }

            Employee::create($data);
            app()->route->redirect('/employees');
            exit;
        }

        return (new View())->render('site.employees_create', [
            'departments' => Department::all()->toArray()
        ]);
    }

    public function departments(Request $request): string
    {
        $this->requireRole(['decanat']);

        if ($request->method === 'POST') {
            $validator = new \Src\Validator\Validator($request->all(), [
                'name' => ['required', 'min:2', 'unique:departments,name']
            ], [
                'required' => 'Название кафедры обязательно',
                'min' => 'Название кафедры слишком короткое',
                'unique' => 'Такая кафедра уже существует'
            ]);

            if ($validator->fails()) {
                return (new View())->render('site.departments', [
                    'departments' => Department::all()->toArray(),
                    'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)
                ]);
            }

            Department::create([
                'name' => $request->get('name')
            ]);

            app()->route->redirect('/departments');
            exit;
        }

        return (new View())->render('site.departments', [
            'departments' => Department::all()->toArray()
        ]);
    }

    public function assignment(): string
    {
        $this->requireRole(['decanat']);

        return (new View())->render('site.assignment', [
            'employees' => Employee::with('department')->get()->toArray(),
            'disciplines' => Discipline::all()->toArray(),
            'assignments' => EmployeeDiscipline::with('employee.department', 'discipline')->get()->toArray()
        ]);
    }

    public function assignmentCreate(Request $request): void
    {
        $this->requireRole(['decanat']);

        header('Content-Type: application/json; charset=utf-8');

        $validator = new \Validator\Validator($request->all(), [
            'employee_id' => ['required'],
            'discipline_id' => ['required']
        ], [
            'required' => 'Поле :field обязательно'
        ]);

        $errors = $validator->errors();

        if (!Employee::find($request->get('employee_id'))) {
            $errors['employee_id'][] = 'Сотрудник не найден';
        }

        if (!Discipline::find($request->get('discipline_id'))) {
            $errors['discipline_id'][] = 'Дисциплина не найдена';
        }

        if ($validator->fails() || !empty($errors)) {
            echo json_encode([
                'success' => false,
                'error' => $errors
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $employeeId = $request->get('employee_id');
        $disciplineId = $request->get('discipline_id');

        $exists = EmployeeDiscipline::where('employee_id', $employeeId)
            ->where('discipline_id', $disciplineId)
            ->exists();

        if ($exists) {
            echo json_encode([
                'success' => false,
                'error' => 'Такое назначение уже существует'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $assignment = EmployeeDiscipline::create([
            'employee_id' => $employeeId,
            'discipline_id' => $disciplineId
        ]);

        echo json_encode([
            'success' => true,
            'id' => $assignment->id
        ], JSON_UNESCAPED_UNICODE);
    }

    public function assignmentDelete(Request $request): void
    {
        $this->requireRole(['decanat']);

        header('Content-Type: application/json; charset=utf-8');

        $id = $request->get('id');

        EmployeeDiscipline::where('id', $id)->delete();

        echo json_encode([
            'success' => true
        ], JSON_UNESCAPED_UNICODE);
    }

    public function reports(Request $request): string
    {
        $this->requireRole(['decanat']);

        $query = EmployeeDiscipline::with('employee.department', 'discipline');

        if ($request->get('employee_id')) {
            $query->where('employee_id', $request->get('employee_id'));
        }

        if ($request->get('department_id')) {
            $departmentId = $request->get('department_id');
            $query->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $assignments = $query->get()->toArray();

        return (new View())->render('site.reports', [
            'reports' => $assignments,
            'employees' => Employee::all()->toArray(),
            'departments' => Department::all()->toArray()
        ]);
    }
}