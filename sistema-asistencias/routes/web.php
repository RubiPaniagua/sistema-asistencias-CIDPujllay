<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JustificacionController;
use App\Http\Controllers\CarreraController;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Asistencia remota
Route::get('/remoto', function () {
    return view('remoto.index');
})->name('asistencia.remota');

// Asistencia presencial
Route::get('/presencial', function () {
    return view('presencial.index');
})->name('asistencia.presencial');

// Justificaciones
Route::get('/justificaciones/crear', [JustificacionController::class, 'create'])
    ->name('justificaciones.create');

Route::post('/justificaciones', [JustificacionController::class, 'store'])
    ->name('justificaciones.store');

// Panel de administración
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('login');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/reportes', function () {
    return view('admin.reportes');
})->name('admin.reportes');

// Gestión de usuarios
Route::get('/admin/usuarios', function () {
    return view('admin.usuarios.index');
})->name('admin.usuarios.index');

Route::get('/admin/usuarios/crear', function () {
    return view('admin.usuarios.create');
})->name('admin.usuarios.create');

// Carreras
Route::get('/carreras', [CarreraController::class, 'index'])
    ->name('carreras.index');

Route::get('/carreras/{carrera}', [CarreraController::class, 'show'])
    ->name('carreras.show');

Route::post('/carreras', [CarreraController::class, 'store'])
    ->name('carreras.store');

Route::put('/carreras/{carrera}', [CarreraController::class, 'update'])
    ->name('carreras.update');

Route::delete('/carreras/{carrera}', [CarreraController::class, 'destroy'])
    ->name('carreras.destroy');

