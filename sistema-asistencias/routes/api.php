<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AsistenciaController;
use App\Http\Controllers\Api\SesionRemotaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Ruta para autocompletar practicante por DNI o código
Route::get('/practicante/buscar', [AsistenciaController::class, 'buscarPorDni']);

// Rutas de asistencia presencial (Entrada y Salida)
Route::post('/asistencia/entrada', [AsistenciaController::class, 'marcarEntrada']);
Route::post('/asistencia/salida', [AsistenciaController::class, 'marcarSalida']);

// Ruta de asistencia remota
Route::post('/asistencia/remota', [AsistenciaController::class, 'marcarRemoto']);

// Ruta para que el administrador genere el código temporal de sesión remota
Route::post('/sesion-remota', [AsistenciaController::class, 'generarSesionRemota']);

// Ruta de inicio de sesión de administradores
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por token (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/me', function (Request $request) {
        return response()->json($request->user());
    });

    // Rutas para consultar y actualizar los reportes de asistencia
    Route::get('/reportes', [AsistenciaController::class, 'reportes']);
    Route::put('/asistencias/{id}', [AsistenciaController::class, 'update']);
    Route::patch('/asistencias/{id}/salida', [AsistenciaController::class, 'updateSalida']);

    // Ruta específica para la auditoría de marcas remotas en tiempo real del dashboard
    Route::get('/auditoria-remota', [AsistenciaController::class, 'auditoriaRemota']);

    // Rutas CRUD de Usuarios para el panel de administración
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    Route::patch('/usuarios/{id}/toggle', [UsuarioController::class, 'toggleEstado']);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
});