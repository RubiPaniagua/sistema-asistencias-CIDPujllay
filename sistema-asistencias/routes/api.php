<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AsistenciaController;
use App\Http\Controllers\Api\SesionRemotaController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\JustificacionController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\InstitucionController;

// -------------------------
// RUTAS PÚBLICAS
// -------------------------

// Autenticación pública
Route::post('/login', [AuthController::class, 'login']);

// Buscar practicante por DNI
Route::get('/practicante/buscar', [AsistenciaController::class, 'buscarPorDni']);

// Marcado de asistencia (Presencial y Remoto)
Route::post('/asistencia/entrada', [AsistenciaController::class, 'marcarEntrada']);
Route::post('/asistencia/salida', [AsistenciaController::class, 'marcarSalida']);
Route::post('/asistencia/remoto', [AsistenciaController::class, 'marcarRemoto']);


// -------------------------
// RUTAS PROTEGIDAS (Sanctum)
// -------------------------

// Usuario autenticado / Cierre de sesión
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


// -------------------------
// RUTAS PROTEGIDAS SOLO ADMIN
// -------------------------

Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Generar código de sesión remota
    Route::post('/sesion-remota', [SesionRemotaController::class, 'generar']);
    
    // Funcion de asistencias / justificaciones
    Route::get('/usuarios/{usuario}/asistencias', [UsuarioController::class, 'asistencias']);
    Route::get('/justificaciones', [JustificacionController::class, 'index']);

    // Módulo de Reportes
    Route::get('/reportes', [ReporteController::class, 'index']);
    Route::get('/reportes/excel', [ReporteController::class, 'excel']);
    Route::get('/reportes/pdf', [ReporteController::class, 'pdf']);

    // agregado
    // CRUD API de Instituciones
    Route::get('/instituciones', [InstitucionController::class, 'index']);
    Route::post('/instituciones', [InstitucionController::class, 'store']);
    Route::get('/instituciones/{institucion}', [InstitucionController::class, 'show']);
    Route::put('/instituciones/{institucion}', [InstitucionController::class, 'update']);
    Route::delete('/instituciones/{institucion}', [InstitucionController::class, 'destroy']);
    
    // CRUD API de Carreras
    Route::get('/carreras', [CarreraController::class, 'index']);
    Route::post('/carreras', [CarreraController::class, 'store']);
    Route::get('/carreras/{carrera}', [CarreraController::class, 'show']);
    Route::put('/carreras/{carrera}', [CarreraController::class, 'update']);
    Route::delete('/carreras/{carrera}', [CarreraController::class, 'destroy']);

    // CRUD API de Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy']);
});