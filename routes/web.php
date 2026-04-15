<?php
/*настройки роутинга */
/*Статический метод add(), позволяет добавить новый маршрут. Маршрут представляет
собой элемент ассоциативного массива routes с ключем - адресом url маршрута и
значением - массивом из двух элементов (наименование класса и его метод, который
нужно вызвать).*/

use Src\Route;
Route::add('GET', '/go', [Controller\Site::class, 'index']);
Route::add('GET', '/hello', [Controller\Site::class, 'hello'])->middleware('auth');
Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);
