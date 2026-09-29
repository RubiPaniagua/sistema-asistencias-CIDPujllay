<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\SesionRemota;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    /**
     * Buscar practicante por DNI para autocompletar en el formulario.
     */
    public function buscarPorDni(Request $request)
    {
        $dni = $request->query('dni');

        if (!$dni) {
            return response()->json(['encontrado' => false], 400);
        }

        $usuario = Usuario::with('carrera')
            ->where(function($query) use ($dni) {
                $query->where('codigo', $dni)
                      ->orWhere('dni', $dni);
            })
            ->where('activo', true)
            ->first();

        if ($usuario) {
            $nombreCompleto = trim($usuario->nombres . ' ' . $usuario->apellidos);

            $nombreInstitucion = 'SENATI';
            if ($usuario->institucion_id) {
                $inst = DB::table('instituciones')->where('id', $usuario->institucion_id)->first();
                if ($inst) {
                    $nombreInstitucion = strtoupper($inst->nombre);
                }
            }

            return response()->json([
                'encontrado' => true,
                'nombres' => $nombreCompleto,
                'especialidad' => $usuario->carrera->nombre ?? 'N/A',
                'institucion' => $nombreInstitucion
            ], 200);
        }

        return response()->json(['encontrado' => false], 404);
    }

    /**
     * Marcar entrada presencial.
     */
    public function marcarEntrada(Request $request)
    {
        $dni = $request->input('dni') ?? $request->input('codigo'); // Soporte temporal por compatibilidad
        $actividad = $request->input('actividad');
        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        if (!$dni) {
            return response()->json(['ok' => false, 'error' => 'El DNI es requerido'], 400);
        }

        $usuario = Usuario::with('carrera')
            ->where(function($query) use ($dni) {
                $query->where('codigo', $dni)
                      ->orWhere('dni', $dni);
            })
            ->where('activo', true)
            ->first();

        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'error' => 'DNI no válido en el sistema'
            ], 404);
        }

        // Verificamos si ya existe asistencia hoy
        $existeEntrada = DB::table('asistencias')
            ->where('usuario_id', $usuario->id)
            ->where('fecha', $hoy)
            ->exists();

        if ($existeEntrada) {
            return response()->json([
                'ok' => false,
                'error' => 'Ya existe un registro de entrada para el día de hoy'
            ], 400);
        }

        $horaLimite = Carbon::createFromTimeString('08:10:00', 'America/Lima');
        $estado = $ahora->greaterThan($horaLimite) ? 'tardanza' : 'a_tiempo';

        DB::table('asistencias')->insert([
            'usuario_id' => $usuario->id,
            'carrera_id' => $usuario->carrera_id,
            'fecha' => $hoy,
            'hora_entrada' => $ahora->toDateTimeString(),
            'modalidad' => 'presencial',
            'actividad' => $actividad,
            'estado' => $estado,
            'created_at' => $ahora,
            'updated_at' => $ahora,
        ]);

        $nombreCompleto = trim($usuario->nombres . ' ' . $usuario->apellidos);

        return response()->json([
            'ok' => true,
            'nombre' => $nombreCompleto,
            'carrera' => $usuario->carrera->nombre ?? 'N/A',
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }

    /**
     * Marcar salida presencial.
     */
    public function marcarSalida(Request $request)
    {
        $dni = $request->input('dni') ?? $request->input('codigo');
        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        if (!$dni) {
            return response()->json(['ok' => false, 'error' => 'El DNI es requerido'], 400);
        }

        $usuario = Usuario::with('carrera')
            ->where(function($query) use ($dni) {
                $query->where('codigo', $dni)
                      ->orWhere('dni', $dni);
            })
            ->where('activo', true)
            ->first();

        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'error' => 'DNI no válido en el sistema'
            ], 404);
        }

        // Buscamos el registro de asistencia de hoy para actualizar su salida
        $asistencia = DB::table('asistencias')
            ->where('usuario_id', $usuario->id)
            ->where('fecha', $hoy)
            ->first();

        if (!$asistencia) {
            return response()->json([
                'ok' => false,
                'error' => 'No se encontró un registro de entrada para el día de hoy.'
            ], 400);
        }

        if (!empty($asistencia->hora_salida)) {
            return response()->json([
                'ok' => false,
                'error' => 'Ya se registró la salida para el día de hoy.'
            ], 400);
        }

        DB::table('asistencias')
            ->where('id', $asistencia->id)
            ->update([
                'hora_salida' => $ahora->toDateTimeString(),
                'updated_at' => $ahora,
            ]);

        $nombreCompleto = trim($usuario->nombres . ' ' . $usuario->apellidos);

        return response()->json([
            'ok' => true,
            'nombre' => $nombreCompleto,
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }

    /**
     * Registrar asistencia remota mediante código de sesión virtual.
     */
    public function marcarRemoto(Request $request)
    {
        $dni = $request->input('dni') ?? $request->input('codigo');
        $codigoSesion = strtoupper(trim($request->input('codigo_sesion')));
        $actividad = $request->input('actividad');
        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        $sesion = SesionRemota::where('codigo', $codigoSesion)->first();

        if (!$sesion || !$sesion->esValida()) {
            return response()->json([
                'ok' => false,
                'error' => 'Código de sesión vencido o datos incorrectos'
            ], 400);
        }

        $usuario = Usuario::with('carrera')
            ->where(function($query) use ($dni) {
                $query->where('codigo', $dni)
                      ->orWhere('dni', $dni);
            })
            ->where('activo', true)
            ->first();

        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'error' => 'Usuario no encontrado'
            ], 404);
        }

        $existe = DB::table('asistencias')
            ->where('usuario_id', $usuario->id)
            ->where('fecha', $hoy)
            ->exists();

        if ($existe) {
            return response()->json([
                'ok' => false,
                'error' => 'Ya registraste tu asistencia el día de hoy'
            ], 400);
        }

        DB::table('asistencias')->insert([
            'usuario_id' => $usuario->id,
            'carrera_id' => $usuario->carrera_id,
            'fecha' => $hoy,
            'hora_entrada' => $ahora->toDateTimeString(),
            'modalidad' => 'remoto',
            'actividad' => $actividad,
            'estado' => 'a_tiempo',
            'created_at' => $ahora,
            'updated_at' => $ahora,
        ]);

        $nombreCompleto = trim($usuario->nombres . ' ' . $usuario->apellidos);

        return response()->json([
            'ok' => true,
            'nombre' => $nombreCompleto,
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }
}