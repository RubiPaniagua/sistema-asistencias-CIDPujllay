<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AsistenciaController;
use App\Http\Controllers\Api\SesionRemotaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsuarioController;

// ==============================
// RUTAS PÚBLICAS
// ==============================

// Login del administrador
Route::post('/login', [AuthController::class, 'login']);

// Buscar practicante por DNI
Route::get('/practicante/buscar', [AsistenciaController::class, 'buscarPorDni']);

// Asistencias
Route::post('/asistencia/entrada', [AsistenciaController::class, 'marcarEntrada']);
Route::post('/asistencia/salida', [AsistenciaController::class, 'marcarSalida']);
Route::post('/asistencia/remoto', [AsistenciaController::class, 'marcarRemoto']);


// ==============================
// RUTAS PROTEGIDAS CON SANCTUM
// ==============================

Route::middleware('auth:sanctum')->group(function () {

    // Datos del administrador autenticado
    Route::get('/me', [AuthController::class, 'me']);

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // Generar código de sesión remota
    Route::post('/sesion-remota', [SesionRemotaController::class, 'generar']);

    // Futuras rutas protegidas
    // Route::get('/reportes', ...);
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    // Route::put('/usuarios/{id}', ...);
    // Route::delete('/usuarios/{id}', ...);
});