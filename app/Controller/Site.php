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
            $name = $request->get('name');
            $login = $request->get('login');
            $email = $request->get('email');
            $password = $request->get('password');

            if (empty($name) || empty($login) || empty($email) || empty($password)) {
                return (new View())->render('site.signup', [
                    'message' => 'Заполните все поля'
                ], 'empty');
            }

            $existing = User::where('login', $login)
                ->orWhere('email', $email)
                ->first();

            if ($existing) {
                return (new View())->render('site.signup', [
                    'message' => 'Пользователь с таким логином или email уже существует'
                ], 'empty');
            }

            User::create([
                'name' => $name,
                'login' => $login,
                'email' => $email,
                'password' => md5($password),
                'role' => 'decanat'
            ]);

            Auth::attempt([
                'login' => $login,
                'password' => $password
            ]);

            app()->route->redirect('/');
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
            Discipline::create($request->all());
            app()->route->redirect('/disciplines');
        }

        return (new View())->render('site.disciplines', [
            'disciplines' => Discipline::all()->toArray()
        ]);
    }

    public function employees(Request $request): string
    {
        $this->requireRole(['decanat']);

        $allEmployees = Employee::with('department', 'disciplines')->get()->toArray();

        return (new View())->render('site.employees', [
            'allEmployees' => $allEmployees
        ]);
    }

    public function employeesCreate(Request $request): string
    {
        $this->requireRole(['decanat']);

        if ($request->method === 'POST') {
            Employee::create($request->all());
            app()->route->redirect('/employees');
        }

        return (new View())->render('site.employees_create', [
            'departments' => Department::all()->toArray()
        ]);
    }

    public function departments(Request $request): string
    {
        $this->requireRole(['decanat']);

        if ($request->method === 'POST') {
            Department::create($request->all());
            app()->route->redirect('/departments');
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

        $data = json_decode(file_get_contents('php://input'), true);

        $exists = EmployeeDiscipline::where('employee_id', $data['employee_id'])
            ->where('discipline_id', $data['discipline_id'])
            ->exists();

        if ($exists) {
            echo json_encode([
                'success' => false,
                'error' => 'Уже существует'
            ]);
            return;
        }

        $assignment = EmployeeDiscipline::create($data);

        echo json_encode([
            'success' => true,
            'id' => $assignment->id
        ]);
    }

    public function assignmentDelete(): void
    {
        $this->requireRole(['decanat']);

        $data = json_decode(file_get_contents('php://input'), true);

        EmployeeDiscipline::where('id', $data['id'])->delete();

        echo json_encode([
            'success' => true
        ]);
    }

    public function reports(Request $request): string
    {
        $this->requireRole(['decanat']);

        $query = EmployeeDiscipline::with('employee.department', 'discipline');

        if ($request->get('employee_id')) {
            $query->where('employee_id', $request->get('employee_id'));
        }

        if ($request->get('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->get('department_id'));
            });
        }

        return (new View())->render('site.reports', [
            'employees' => Employee::all()->toArray(),
            'departments' => Department::all()->toArray(),
            'disciplines' => Discipline::all()->toArray(),
            'reports' => $query->get()->toArray()
        ]);
    }

    public function decanatCreate(Request $request): string
    {
        $this->requireRole(['admin']);

        if ($request->method === 'POST') {
            $name = $request->get('name');
            $login = $request->get('login');
            $email = $request->get('email');
            $password = $request->get('password');

            if (empty($name) || empty($login) || empty($email) || empty($password)) {
                return (new View())->render('site.decanat_create', [
                    'message' => 'Заполните все поля'
                ]);
            }

            $existing = User::where('login', $login)
                ->orWhere('email', $email)
                ->first();

            if ($existing) {
                return (new View())->render('site.decanat_create', [
                    'message' => 'Пользователь с таким логином или email уже существует'
                ]);
            }

            User::create([
                'name' => $name,
                'login' => $login,
                'email' => $email,
                'password' => md5($password),
                'role' => 'decanat'
            ]);

            app()->route->redirect('/');
        }

        return (new View())->render('site.decanat_create');
    }
}