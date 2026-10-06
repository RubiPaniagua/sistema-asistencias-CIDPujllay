<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\SesionRemota;
use App\Models\Asistencia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

        return response()->json(['encontrado' => false], 404);
    }

    /**
     * Marcar entrada presencial.
     */
    public function marcarEntrada(Request $request)
    {
        $dni = $request->input('dni');
        $actividad = $request->input('actividad');
        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        if (!$dni) {
            return response()->json(['ok' => false, 'error' => 'El DNI es requerido'], 400);
        }

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

        return response()->json([
            'ok' => true,
            'nombre' => $usuario->nombre_completo,
            'carrera' => $usuario->carrera->nombre ?? 'N/A',
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }

    /**
     * Marcar salida presencial / general.
     */
    public function marcarSalida(Request $request)
    {
        $dni = $request->input('dni');
        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        if (!$dni) {
            return response()->json(['ok' => false, 'error' => 'El DNI es requerido'], 400);
        }

        $usuario = Usuario::where('dni', $dni)
            ->where('activo', true)
            ->first();

        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'error' => 'DNI no válido'
            ], 404);
        }

        $asistencia = Asistencia::where('usuario_id', $usuario->id)
            ->where('fecha', $hoy)
            ->first();

        if (!$asistencia) {
            return response()->json([
                'ok' => false,
                'error' => 'No se encuentra un registro de entrada para el día de hoy'
            ], 400);
        }

        if ($asistencia->hora_salida !== null) {
            return response()->json([
                'ok' => false,
                'error' => 'Ya se registró la salida para el día de hoy'
            ], 400);
        }

        $asistencia->update([
            'hora_salida' => $ahora->toTimeString(),
            'updated_at' => $ahora
        ]);

        return response()->json([
            'ok' => true,
            'nombre' => $usuario->nombre_completo,
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }

    /**
     * Registrar asistencia remota mediante código de sesión virtual.
     */
    public function marcarRemoto(Request $request)
    {
        $dni = $request->input('dni');
        $codigoSesion = strtoupper(trim($request->input('codigo_sesion')));
        $actividad = $request->input('actividad');
        $ahora = Carbon::now('America/Lima');
        $hoy = $ahora->toDateString();

        $sesion = SesionRemota::where('codigo_temporal', $codigoSesion)->first();

        if (!$sesion || !$sesion->esValida()) {
            return response()->json([
                'ok' => false,
                'error' => 'Código de sesión vencido o datos incorrectos'
            ], 400);
        }

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

        return response()->json([
            'ok' => true,
            'nombre' => $usuario->nombre_completo,
            'hora' => $ahora->format('H:i:s')
        ], 200);
    }

    public function generarSesionRemota(Request $request)
    {
        $codigo = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        $expiraEn = now()->addMinutes(15);

        return response()->json([
            'ok' => true,
            'codigo_temporal' => $codigo,
            'expira_en' => $expiraEn
        ]);
    }

    public function reportes(Request $request)
    {
        $query = Asistencia::with(['usuario.carrera']);

        if ($request->filled('usuario')) {
            $term = $request->usuario;
            $query->whereHas('usuario', function($q) use ($term) {
                $q->where('nombres', 'like', "%{$term}%")
                  ->orWhere('apellidos', 'like', "%{$term}%")
                  ->orWhere(DB::raw("CONCAT(nombres, ' ', apellidos)"), 'like', "%{$term}%");
            });
        }

        if ($request->filled('tipo_filtro')) {
            $tipo = $request->tipo_filtro;

            if ($tipo === 'fecha' && $request->filled('fecha')) {
                $query->whereDate('fecha', $request->fecha);
            } elseif ($tipo === 'semana') {
                $query->whereBetween('fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($tipo === 'mes') {
                $query->whereMonth('fecha', Carbon::now()->month)
                      ->whereYear('fecha', Carbon::now()->year);
            }
        }

        if ($request->filled('modalidad')) {
            $query->where('modalidad', $request->modalidad);
        }

        $registros = $query->orderBy('fecha', 'desc')->get()->map(function($item) {
            return [
                'usuario' => $item->usuario->nombre_completo ?? ($item->usuario->nombres ?? 'Desconocido'),
                'carrera' => $item->usuario->carrera->nombre ?? ($item->carrera->nombre ?? '-'),
                'fecha' => $item->fecha,
                'hora_entrada' => $item->hora_entrada,
                'hora_salida' => $item->hora_salida,
                'modalidad' => $item->modalidad,
                'estado' => $item->estado,
            ];
        });

        return response()->json([
            'ok' => true,
            'data' => $registros
        ]);
    }

    public function exportarPdf(Request $request)
    {
        $query = Asistencia::with(['usuario', 'usuario.carrera']);

        if ($request->filled('usuario')) {
            $usuario = $request->input('usuario');
            $query->whereHas('usuario', function($q) use ($usuario) {
                $q->where('nombres', 'like', "%{$usuario}%")
                  ->orWhere('apellidos', 'like', "%{$usuario}%");
            });
        }

        if ($request->filled('tipo_filtro')) {
            $tipo = $request->input('tipo_filtro');
            if ($tipo === 'fecha' && $request->filled('fecha')) {
                $query->whereDate('fecha', $request->input('fecha'));
            } elseif ($tipo === 'semana') {
                $query->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($tipo === 'mes') {
                $query->whereMonth('fecha', now()->month)
                      ->whereYear('fecha', now()->year);
            }
        }

        if ($request->filled('modalidad')) {
            $query->where('modalidad', $request->input('modalidad'));
        }

        $registros = $query->get();

        // Generar el archivo PDF con DomPDF
        $pdf = Pdf::loadView('admin.pdf-reporte', compact('registros'));
        
        // stream() muestra el PDF directamente en el visor integrado del navegador (como tu última imagen)
        return $pdf->stream('reporte-asistencias.pdf');
    }
}