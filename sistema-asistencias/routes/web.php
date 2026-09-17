<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');

Route::get('/presencial', function () {
    return view('presencial.index');
});

Route::get('/remoto', function () {
    return view('remoto.index');
});

Route::get('/admin/login', function () {
    return view('admin.login');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/admin/reportes', function () {
    return view('admin.reportes');
});

Route::get('/admin/usuarios', function () {
    return view('admin.usuarios.index');
});

Route::get('/admin/usuarios/crear', function () {
    return view('admin.usuarios.create');
});



});
