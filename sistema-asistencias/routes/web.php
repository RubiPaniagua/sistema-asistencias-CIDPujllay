<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JustificacionController;
use App\Http\Controllers\Api\AsistenciaController;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- Rutas de API / Asistencia ---
Route::get('/api/practicante/buscar', [AsistenciaController::class, 'buscarPorDni']);
Route::post('/api/asistencia/entrada', [AsistenciaController::class, 'marcarEntrada']);
Route::post('/api/asistencia/salida', [AsistenciaController::class, 'marcarEntrada']); // Ajustar al controlador de salida cuando esté implementado
Route::post('/api/asistencia/remoto', [AsistenciaController::class, 'marcarRemoto']);

// Ruta de Asistencia Remota
Route::get('/remoto', function () {
    return view('remoto.index');
})->name('asistencia.remota');

// Ruta de Asistencia Presencial
Route::get('/presencial', function () {
    return view('presencial.index');
})->name('asistencia.presencial');

// Formulario de justificaciones
Route::get('/justificaciones/crear', [JustificacionController::class, 'create'])->name('justificaciones.create');
Route::post('/justificaciones', [JustificacionController::class, 'store'])->name('justificaciones.store');

// --- Rutas del Panel de Administración ---
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('login');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/reportes', function () {
    return view('admin.reportes');
})->name('admin.reportes');

// Gestión de Usuarios
Route::get('/admin/usuarios', function () {
    return view('admin.usuarios.index');
})->name('admin.usuarios.index');

Route::get('/admin/usuarios/crear', function () {
    return view('admin.usuarios.create');
})->name('admin.usuarios.create');

Route::get('/carreras', [App\Http\Controllers\CarreraController::class, 'index'])->name('carreras.index');
Route::get('/carreras/{carrera}', [App\Http\Controllers\CarreraController::class, 'show'])->name('carreras.show');
Route::post('/carreras', [App\Http\Controllers\CarreraController::class, 'store'])->name('carreras.store');
Route::put('/carreras/{carrera}', [App\Http\Controllers\CarreraController::class, 'update'])->name('carreras.update');
Route::delete('/carreras/carrera', [App\Http\Controllers\CarreraController::class, 'destroy'])->name('carreras.destroy');
