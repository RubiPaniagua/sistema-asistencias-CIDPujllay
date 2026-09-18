<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Asistencia;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function marcarEntrada(Request $request)
    {
        $codigo = $request->input('codigo');
        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        // Reglas 1 y 3: Buscar usuario activo
        $usuario = Usuario::with('carrera')
            ->where('codigo', $codigo)
            ->where('activo', true)
            ->first();

        // Error genérico 404 si no existe o está desactivado (según contrato)
        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'error' => 'Código no válido'
            ], 404);
        }

        // Regla 4: No registrar dos entradas para el mismo día
        $existeEntrada = Asistencia::where('usuario_id', $usuario->id)
            ->where('fecha', $hoy)
            ->exists();

        if ($existeEntrada) {
            return response()->json([
                'ok' => false,
                'error' => 'Ya existe un registro de entrada para el día de hoy'
            ], 400);
        }

        // Regla de tardanza (Ejemplo: Tolerancia hasta las 08:10 AM)
        $horaLimite = Carbon::createFromTimeString('08:10:00', 'America/Lima');
        $estado = $ahora->greaterThan($horaLimite) ? 'tardanza' : 'a_tiempo';

        // Regla 7: Fecha y hora automáticas del servidor
        Asistencia::create([
            'usuario_id' => $usuario->id,
            'fecha' => $hoy,
            'hora_entrada' => $ahora->toTimeString(),
            'modalidad' => $usuario->modalidad,
            'estado' => $estado,
        ]);

        // Respuesta exacta según contrato API
        return response()->json([
            'ok' => true,
            'nombre' => $usuario->nombre,
            'carrera' => $usuario->carrera->nombre ?? 'N/A',
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }
}