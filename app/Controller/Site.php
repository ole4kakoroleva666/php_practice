<?php
namespace Controller;

use Model\Post;
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
    // Вспомогательный метод для проверки ролей
    private function hasRole(array $roles): bool
    {
        return in_array(app()->auth::user()->role, $roles);
    }

    public function index(): string
    {
        return new View('site.index');
    }

    public function hello(): string
    {
        return new View('site.hello', ['message' => 'hello working']);
    }

    public function welcome(): string
    {
        if (Auth::check()) {
            return new View('site.index');
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
        return (new View())->render('site.login', ['message' => 'Неправильные логин или пароль'], 'empty');
    }

    public function signup(Request $request): string
    {
        if (!empty($_GET)) {
            app()->route->redirect('/signup');
        }

        if ($request->method === 'POST') {
            $name = $request->get('name');
            $login = $request->get('login');
            $email = $request->get('email');
            $password = $request->get('password');
            
            if (empty($name) || empty($login) || empty($email) || empty($password)) {
                return (new View())->render('site.signup', ['message' => 'Заполните все поля'], 'empty');
            }
            
            $existing = User::where('login', $login)->orWhere('email', $email)->first();
            if ($existing) {
                return (new View())->render('site.signup', ['message' => 'Пользователь с таким логином или email уже существует'], 'empty');
            }
            
            User::create([
                'name' => $name,
                'login' => $login,
                'email' => $email,
                'password' => md5($password),
                'role' => $request->get('role') ?? 'user'
            ]);
            
            app()->route->redirect('/');
        }
        return (new View())->render('site.signup', [], 'empty');
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }

    // ========== ДИСЦИПЛИНЫ ==========
    public function disciplines(Request $request): string
    {
        if ($request->method === 'POST') {
            // Добавление дисциплины доступно admin и decanat
            if (!$this->hasRole(['admin', 'decanat'])) {
                app()->route->redirect('/disciplines');
            }
            Discipline::create($request->all());
            app()->route->redirect('/disciplines');
        }
        
        $disciplines = Discipline::all()->toArray();
        return new View('site.disciplines', ['disciplines' => $disciplines]);
    }

    // ========== СОТРУДНИКИ ==========
    public function employees(Request $request): string
    {
        $allEmployees = Employee::with('department', 'disciplines')->get()->toArray();
        $selectedEmployee = null;
        $selectedEmployeeId = null;
        
        if ($request->get('employee_id')) {
            $selectedEmployeeId = $request->get('employee_id');
            $selectedEmployee = Employee::with('department', 'disciplines')->find($selectedEmployeeId);
        }
        
        return (new View())->render('site.employees', [
            'allEmployees' => $allEmployees,
            'selectedEmployee' => $selectedEmployee,
            'selectedEmployeeId' => $selectedEmployeeId
        ]);
    }

    public function employeesCreate(Request $request): string
    {
        // Добавление сотрудника доступно admin и decanat
        if (!$this->hasRole(['admin', 'decanat'])) {
            app()->route->redirect('/employees');
        }
        
        if ($request->method === 'POST') {
            Employee::create($request->all());
            app()->route->redirect('/employees');
        }
        
        $departments = Department::all()->toArray();
        return new View('site.employees_create', ['departments' => $departments]);
    }

    // ========== КАФЕДРЫ ==========
    public function departments(Request $request): string
    {
        if ($request->method === 'POST') {
            // Добавление кафедры доступно admin и decanat
            if (!$this->hasRole(['admin', 'decanat'])) {
                app()->route->redirect('/departments');
            }
            Department::create($request->all());
            app()->route->redirect('/departments');
        }
        
        $departments = Department::all()->toArray();
        return new View('site.departments', ['departments' => $departments]);
    }

    // ========== НАЗНАЧЕНИЕ ДИСЦИПЛИН ==========
    public function assignment(): string
    {
        // Просмотр доступен всем авторизованным
        $employees = Employee::with('department')->get()->toArray();
        $disciplines = Discipline::all()->toArray();
        $assignments = EmployeeDiscipline::with('employee.department', 'discipline')->get()->toArray();
        
        return new View('site.assignment', [
            'employees' => $employees,
            'disciplines' => $disciplines,
            'assignments' => $assignments
        ]);
    }

    public function assignmentCreate(Request $request): void
    {
        // Создание назначения доступно admin и decanat
        if (!$this->hasRole(['admin', 'decanat'])) {
            echo json_encode(['success' => false, 'error' => 'Нет прав']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $exists = EmployeeDiscipline::where('employee_id', $data['employee_id'])
            ->where('discipline_id', $data['discipline_id'])
            ->exists();
        
        if ($exists) {
            echo json_encode(['success' => false, 'error' => 'Такое назначение уже существует']);
            return;
        }
        
        $assignment = EmployeeDiscipline::create([
            'employee_id' => $data['employee_id'],
            'discipline_id' => $data['discipline_id']
        ]);
        
        echo json_encode(['success' => true, 'id' => $assignment->id]);
    }

    public function assignmentDelete(Request $request): void
    {
        // Удаление назначения доступно admin и decanat
        if (!$this->hasRole(['admin', 'decanat'])) {
            echo json_encode(['success' => false, 'error' => 'Нет прав']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        EmployeeDiscipline::where('id', $data['id'])->delete();
        echo json_encode(['success' => true]);
    }

    // ========== ПРОСМОТР ДАННЫХ ==========
    public function reports(Request $request): string
    {
        // Просмотр доступен всем авторизованным
        $employees = Employee::all()->toArray();
        $disciplines = Discipline::all()->toArray();
        $departments = Department::all()->toArray();
        
        $query = EmployeeDiscipline::with('employee.department', 'discipline');
        
        if ($request->get('employee_id')) {
            $query->where('employee_id', $request->get('employee_id'));
        }
        
        if ($request->get('discipline_id')) {
            $query->where('discipline_id', $request->get('discipline_id'));
        }
        
        if ($request->get('department_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->get('department_id'));
            });
        }
        
        $reports = $query->get()->toArray();
        
        return new View('site.reports', [
            'employees' => $employees,
            'disciplines' => $disciplines,
            'departments' => $departments,
            'reports' => $reports
        ]);
    }
}