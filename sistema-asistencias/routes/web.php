<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JustificacionController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Api\AsistenciaController;
use Illuminate\Support\Facades\Storage;
use App\Models\Justificacion;

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

// Justificaciones (Públicas / Practicantes)
Route::get('/justificaciones/crear', [JustificacionController::class, 'create'])
    ->name('justificaciones.create');

Route::post('/justificaciones', [JustificacionController::class, 'store'])
    ->name('justificaciones.store');

// Panel de administración - Justificaciones
Route::get('/admin/justificaciones', [JustificacionController::class, 'indexAdmin'])
    ->name('admin.justificaciones');
Route::post('/admin/justificaciones/{id}/aprobar', [JustificacionController::class, 'aprobar'])
    ->name('admin.justificaciones.aprobar');
Route::post('/admin/justificaciones/{id}/rechazar', [JustificacionController::class, 'rechazar'])
    ->name('admin.justificaciones.rechazar');

// Ruta segura para visualizar evidencias de justificaciones y evitar errores 403
Route::get('/admin/justificaciones/evidencia/{id}', function ($id) {
    $justificacion = Justificacion::findOrFail($id);
    
    if (!$justificacion->evidencia_path || !Storage::disk('public')->exists($justificacion->evidencia_path)) {
        abort(404, 'Archivo no encontrado');
    }

    return response()->file(storage_path('app/public/' . $justificacion->evidencia_path));
})->name('admin.justificaciones.evidencia');

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

// Gestión de usuarios (Vistas Blade)
Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
Route::get('/admin/usuarios/crear', [UsuarioController::class, 'create'])->name('admin.usuarios.create');
Route::get('/admin/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('admin.usuarios.edit');

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

// Ruta para la vista PDF de reportes pasando los filtros
Route::get('/admin/reportes/pdf', [AsistenciaController::class, 'exportarPdf']);