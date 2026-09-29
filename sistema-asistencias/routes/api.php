<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AsistenciaController;

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

// Ruta de asistencia remota
Route::post('/asistencia/remoto', [AsistenciaController::class, 'marcarRemoto']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');