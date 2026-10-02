<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\SesionRemota;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SesionRemotaController extends Controller
{
    public function generar(Request $request)
    {
        $dniAdmin = $request->input('dni');

        $admin = Usuario::where('dni', $dniAdmin)
            ->where('rol', 'admin')
            ->where('activo', true)
            ->first();

        if (!$admin) {
            return response()->json(['ok' => false, 'error' => 'No autorizado'], 403);
        }

        do {
            $codigo = strtoupper(Str::random(6));
        } while (SesionRemota::where('codigo_temporal', $codigo)->exists());

        $sesion = SesionRemota::create([
            'codigo_temporal' => $codigo,
            'generado_por' => $admin->id,
            'expira_en' => Carbon::now('America/Lima')->addMinutes(15),
            'usado' => false,
        ]);

        return response()->json([
            'ok' => true,
            'codigo_temporal' => $sesion->codigo_temporal,
            'expira_en' => $sesion->expira_en->toIso8601String(),
        ], 200);
    }
}