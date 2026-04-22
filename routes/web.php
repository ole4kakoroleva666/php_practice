<?php
use Src\Route;

Route::add('GET', '/', [Controller\Site::class, 'welcome']);
Route::add('GET', '/go', [Controller\Site::class, 'index']);
Route::add('GET', '/hello', [Controller\Site::class, 'hello'])->middleware('auth');

Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);

Route::add('GET', '/employees', [Controller\Site::class, 'employees']);
Route::add(['GET', 'POST'], '/employees/create', [Controller\Site::class, 'employeesCreate']);

Route::add(['GET', 'POST'], '/departments', [Controller\Site::class, 'departments']);

Route::add(['GET', 'POST'], '/disciplines', [Controller\Site::class, 'disciplines']);

Route::add('GET', '/assignment', [Controller\Site::class, 'assignment']);
Route::add('POST', '/assignment/create', [Controller\Site::class, 'assignmentCreate']);
Route::add('POST', '/assignment/delete', [Controller\Site::class, 'assignmentDelete']);


Route::add('GET', '/reports', [Controller\Site::class, 'reports']);


Route::add(['GET', 'POST'], '/decanat/create', [Controller\Site::class, 'decanatCreate']);