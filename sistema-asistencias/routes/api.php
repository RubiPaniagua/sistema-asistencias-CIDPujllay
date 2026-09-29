<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AsistenciaController;
use App\Http\Controllers\Api\SesionRemotaController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\AuthController;

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
Route::middleware('auth:sanctum')->group(function () {

    // Usuario autenticado / Cierre de sesión
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Generar código de sesión remota
    Route::post('/sesion-remota', [SesionRemotaController::class, 'generar']);

    // Módulo de Reportes
    Route::get('/reportes', [ReporteController::class, 'index']);
    Route::get('/reportes/excel', [ReporteController::class, 'excel']);
    Route::get('/reportes/pdf', [ReporteController::class, 'pdf']);
});