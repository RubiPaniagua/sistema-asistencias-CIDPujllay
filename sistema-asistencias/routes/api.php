<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AsistenciaController;

Route::post('/asistencia/entrada', [AsistenciaController::class, 'marcarEntrada']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
