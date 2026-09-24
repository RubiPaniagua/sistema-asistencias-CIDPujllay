<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AsistenciaController;
use App\Http\Controllers\Api\SesionRemotaController;


Route::get('/practicante/buscar', [AsistenciaController::class, 'buscarPorDni']);
Route::post('/asistencia/entrada', [AsistenciaController::class, 'marcarEntrada']);
Route::post('/asistencia/salida', [AsistenciaController::class, 'marcarSalida']);
Route::post('/asistencia/remoto', [AsistenciaController::class, 'marcarRemoto']);
Route::post('/sesion-remota', [SesionRemotaController::class, 'generar']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');