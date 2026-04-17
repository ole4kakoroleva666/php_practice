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

    public function disciplines(Request $request): string
{
    if ($request->method === 'POST') {
        Discipline::create($request->all());
        app()->route->redirect('/disciplines');
    }
    
    $disciplines = Discipline::all()->toArray();
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

public function assignment(): string
{
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
    $data = json_decode(file_get_contents('php://input'), true);
    EmployeeDiscipline::where('id', $data['id'])->delete();
    echo json_encode(['success' => true]);
}

public function departments(Request $request): string
{
    if ($request->method === 'POST') {
        Department::create($request->all());
        app()->route->redirect('/departments');
    }
    
    $departments = Department::all()->toArray();
    return new View('site.departments', ['departments' => $departments]);
}
}