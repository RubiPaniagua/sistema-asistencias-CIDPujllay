<?php

use Illuminate\Support\Facades\Route;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas de Registro de Asistencia
Route::get('/presencial', function () {
    return view('presencial.index');
});

Route::get('/remoto', function () {
    return view('remoto.index');
});

// Rutas del Panel de Administración
Route::get('/admin/login', function () {
    return view('admin.login');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/admin/reportes', function () {
    return view('admin.reportes');
});

// Gestión de Usuarios
Route::get('/admin/usuarios', function () {
    return view('admin.usuarios.index');
});

Route::get('/admin/usuarios/crear', function () {
    return view('admin.usuarios.create');
});