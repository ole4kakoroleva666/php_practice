<?php
namespace Controller;

use Model\Post;
use Model\User;
use Model\Employee;
use Model\Department;
use Model\Discipline;
use Src\View;
use Src\Request;
use Src\Auth\Auth;

class Site
{
    public function index(): string
    {
        return new View('site.index');
    }

    public function hello(): string
    {
        return new View('site.hello', ['message' => 'hello working']);
    }

    public function signup(Request $request): string
    {
        if ($request->method === 'POST' && User::create($request->all())) {
            app()->route->redirect('/go');
        }
        return new View('site.signup');
    }

    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }

    public function departments(): string
    {
        $departments = Department::all();
        return new View('site.departments', ['departments' => $departments]);
    }

    public function disciplines(): string
    {
        $disciplines = Discipline::all();
        return new View('site.disciplines', ['disciplines' => $disciplines]);
    }

    public function employees(Request $request): string
    {
        $allEmployees = Employee::with('department', 'disciplines')->get()->toArray();
        $selectedEmployee = null;
        $selectedEmployeeId = null;
        
        if ($request->get('employee_id')) {
            $selectedEmployeeId = $request->get('employee_id');
            $selectedEmployee = Employee::with('department', 'disciplines')->find($selectedEmployeeId);
        }
        
        return new View('site.employees', [
            'allEmployees' => $allEmployees,
            'selectedEmployee' => $selectedEmployee,
            'selectedEmployeeId' => $selectedEmployeeId
        ]);
    }
}