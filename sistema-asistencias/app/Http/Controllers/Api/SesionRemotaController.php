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
        $admin = $request->user();
    
        if (!$admin || $admin->rol !== 'admin' || !$admin->activo) {
            return response()->json([
                'ok' => false,
                'error' => 'No autorizado'
            ], 403);
        }
    
        $ahora = Carbon::now('America/Lima');
    
        // Buscar una sesión todavía vigente
        $sesionActiva = SesionRemota::where('expira_en', '>=', $ahora)
            ->orderBy('expira_en', 'desc')
            ->first();
    
        // Si existe, reutilizar el mismo código
        if ($sesionActiva) {
            return response()->json([
                'ok' => true,
                'codigo_temporal' => $sesionActiva->codigo_temporal,
                'expira_en' => $sesionActiva->expira_en->toIso8601String(),
                'reutilizada' => true,
            ], 200);
        }
    
        // Si no existe una sesión vigente, crear una nueva
        do {
            $codigo = strtoupper(Str::random(6));
        } while (
            SesionRemota::where('codigo_temporal', $codigo)->exists()
        );
    
        $sesion = SesionRemota::create([
            'codigo_temporal' => $codigo,
            'generado_por' => $admin->id,
            'expira_en' => $ahora->copy()->addMinutes(15),
            'usado' => false,
        ]);
    
        return response()->json([
            'ok' => true,
            'codigo_temporal' => $sesion->codigo_temporal,
            'expira_en' => $sesion->expira_en->toIso8601String(),
            'reutilizada' => false,
        ], 201);
    }
}