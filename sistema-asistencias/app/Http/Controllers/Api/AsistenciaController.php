<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Asistencia;
use App\Models\SesionRemota;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    /**
     * Buscar practicante por DNI para autocompletar en el formulario.
     */
    public function buscarPorDni(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ]);

        $dni = $request->query('dni');

        // evita la busqueda por la columna inexistente
        $usuario = Usuario::with('carrera', 'institucion')
            ->where('dni', $dni)
            ->where('activo', true)
            ->first();

        if ($usuario) {
            return response()->json([
                'encontrado' => true,
                'nombres' => $usuario->nombre_completo,
                'especialidad' => $usuario->carrera->nombre ?? 'N/A',
                'institucion' => $usuario->institucion->nombre ?? 'N/A'
            ], 200);
        }

        return response()->json([
            'encontrado' => false
        ], 404);
    }

    /**
     * Marcar entrada presencial.
     */
    public function marcarEntrada(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
            'actividad' => 'nullable|string|max:1000',
        ]);

        // cambio codigo por dni
        $dni = $request->input('dni');
        $actividad = $request->input('actividad');

        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        // buscar usuario activo por DNI
        $usuario = Usuario::with('carrera')
            ->where('dni', $dni)
            ->where('activo', true)
            ->first();

        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'error' => 'DNI no válido'
            ], 404);
        }

        // Verificar si ya existe asistencia hoy
        $existeEntrada = Asistencia::where('usuario_id', $usuario->id)
            ->where('fecha', $hoy)
            ->exists();

        if ($existeEntrada) {
            return response()->json([
                'ok' => false,
                'error' => 'Ya existe un registro de entrada para el día de hoy'
            ], 400);
        }

        // Determinar si llegó a tiempo o tarde
        $horaLimite = Carbon::createFromTimeString(
            config('asistencia.hora_limite_tardanza'),
            'America/Lima'
        );

        $estado = $ahora->greaterThan($horaLimite)
            ? 'tardanza'
            : 'a_tiempo';

        // Guardar asistencia
        Asistencia::create([
            'usuario_id' => $usuario->id,
            'carrera_id' => $usuario->carrera_id,
            'fecha' => $hoy,
            'hora_entrada' => $ahora->toTimeString(),
            'modalidad' => 'presencial',
            'actividad' => $actividad,
            'estado' => $estado,
        ]);

        return response()->json([
            'ok' => true,
            'nombre' => $usuario->nombre_completo,
            'carrera' => $usuario->carrera->nombre ?? 'N/A',
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }

    /**
     * Registrar asistencia remota mediante código de sesión virtual.
     */
    public function marcarRemoto(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
            'codigo_sesion' => 'required|string',
            'actividad' => 'nullable|string|max:1000',
        ]);

        $dni = $request->input('dni');

        $codigoSesion = strtoupper(
            trim($request->input('codigo_sesion'))
        );

        $actividad = $request->input('actividad');

        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        $horaLimite = Carbon::createFromTimeString(
            config('asistencia.hora_limite_tardanza'),
            'America/Lima'
        );

        $estado = $ahora->lessThanOrEqualTo($horaLimite)
            ? 'a_tiempo'
            : 'tardanza';

        // 1. Validar la sesión virtual
        $sesion = SesionRemota::where(
            'codigo_temporal',
            $codigoSesion
        )->first();

        if (!$sesion || !$sesion->esValida()) {
            return response()->json([
                'ok' => false,
                'error' => 'Código de sesión vencido o datos incorrectos'
            ], 400);
        }

        // 2. Buscar usuario activo
        $usuario = Usuario::with('carrera')
            ->where('dni', $dni)
            ->where('activo', true)
            ->first();

        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'error' => 'Usuario no encontrado'
            ], 404);
        }

        // 3. Verificar duplicados en el día
        $existe = Asistencia::where('usuario_id', $usuario->id)
            ->where('fecha', $hoy)
            ->exists();

        if ($existe) {
            return response()->json([
                'ok' => false,
                'error' => 'Ya registraste tu asistencia el día de hoy'
            ], 400);
        }

        // 4. Guardar asistencia remota
        Asistencia::create([
            'usuario_id' => $usuario->id,
            'carrera_id' => $usuario->carrera_id,
            'fecha' => $hoy,
            'hora_entrada' => $ahora->toTimeString(),
            'modalidad' => 'remoto',
            'actividad' => $actividad,
            'estado' => $estado,
        ]);

        return response()->json([
            'ok' => true,
            'nombre' => $usuario->nombre_completo,
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }

    /**
     * Registrar salida presencial o remota.
     */
    public function marcarSalida(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ]);

        $dni = $request->input('dni');

        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        // Buscar usuario activo
        $usuario = Usuario::where('dni', $dni)
            ->where('activo', true)
            ->first();

        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'error' => 'DNI no válido'
            ], 404);
        }

        // Buscar asistencia del día
        $asistencia = Asistencia::where('usuario_id', $usuario->id)
            ->where('fecha', $hoy)
            ->first();

        if (!$asistencia) {
            return response()->json([
                'ok' => false,
                'error' => 'No se registra entrada para el día de hoy'
            ], 400);
        }

        // Evitar registrar salida dos veces
        if ($asistencia->hora_salida !== null) {
            return response()->json([
                'ok' => false,
                'error' => 'Ya se registró la salida para el día de hoy'
            ], 400);
        }

        // Actualizar hora de salida
        $asistencia->update([
            'hora_salida' => $ahora->toTimeString()
        ]);

        return response()->json([
            'ok' => true,
            'nombre' => $usuario->nombre_completo,
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }
}