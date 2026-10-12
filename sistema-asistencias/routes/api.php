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
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Ruta para autocompletar practicante por DNI o código
Route::get('/practicante/buscar', [AsistenciaController::class, 'buscarPorDni']);

// Rutas de asistencia presencial (Entrada y Salida)
Route::post('/asistencia/entrada', [AsistenciaController::class, 'marcarEntrada']);
Route::post('/asistencia/salida', [AsistenciaController::class, 'marcarSalida']);

// Ruta de asistencia remota (corregida a /remota para coincidir con el frontend)
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

    // Ruta para verificar el token actual (añadida para evitar redirecciones fallidas en el dashboard)
    Route::get('/me', function (Request $request) {
        return response()->json($request->user());
    });

    // Ruta para consultar los reportes de asistencia con filtros
    Route::get('/reportes', [AsistenciaController::class, 'reportes']);
});