<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SesionRemota;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SesionRemotaController extends Controller
{
    public function generar(Request $request)
    {
        // 1. Obtiene el usuario autenticado por Sanctum
        $admin = $request->user();

        // 2. Valida que el usuario exista, sea admin y esté activo
        if (!$admin || $admin->rol !== 'admin' || !$admin->activo) {
            return response()->json([
                'ok' => false,
                'error' => 'No autorizado'
            ], 403);
        }

        // 3. Genera un código aleatorio único de 6 caracteres
        do {
            $codigo = strtoupper(Str::random(6));
        } while (SesionRemota::where('codigo_temporal', $codigo)->exists());

        // 4. Registra la sesión remota en la base de datos
        $sesion = SesionRemota::create([
            'codigo_temporal' => $codigo,
            'generado_por' => $admin->id,
            'expira_en' => Carbon::now('America/Lima')->addMinutes(15),
            'usado' => false,
        ]);

        // 5. Retorna la respuesta en formato JSON
        return response()->json([
            'ok' => true,
            'codigo_temporal' => $sesion->codigo_temporal,
            'expira_en' => $sesion->expira_en->toIso8601String(),
        ], 200);
    }
}