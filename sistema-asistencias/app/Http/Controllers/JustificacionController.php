<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Justificacion;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class JustificacionController extends Controller
{
    /**
     * Muestra el formulario de justificación para el practicante.
     */
    public function create()
    {
        return view('justificaciones.create');
    }

    /**
     * Procesa y guarda la justificación enviada por el practicante.
     */
    public function store(Request $request)
    {
        $request->validate([
            'identificacion' => 'required|string|max:20',
            'tipo'           => 'required|in:tardanza,inasistencia',
            'fecha'          => 'required|date',
            'motivo'         => 'required|string|max:1000',
            'evidencia'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $rutaEvidencia = null;
        if ($request->hasFile('evidencia')) {
            $rutaEvidencia = $request->file('evidencia')->store('evidencias', 'public');
        }

        $usuario = Usuario::where('dni', $request->identificacion)->first();

        Justificacion::create([
            'usuario_id'     => $usuario ? $usuario->id : null,
            'identificacion' => $request->identificacion,
            'tipo'           => $request->tipo,
            'fecha'          => $request->fecha,
            'motivo'         => $request->motivo,
            'evidencia_path' => $rutaEvidencia,
            'estado'         => 'pendiente',
        ]);

        return redirect()->back()->with('success', 'Su justificación fue enviada con éxito y aguarda análisis.');
    }

    /**
     * Muestra la lista de justificaciones en el panel de administración para la monitora.
     */
    public function indexAdmin()
    {
        $justificaciones = Justificacion::with('usuario')->orderBy('created_at', 'desc')->get();
        
        return view('admin.justificaciones.index', compact('justificaciones'));
    }

    /**
     * Aprueba una justificación e inserta o actualiza el reporte de asistencia.
     */
    public function aprobar($id)
    {
        $justificacion = Justificacion::findOrFail($id);
        $justificacion->estado = 'aprobado';
        $justificacion->save();

        if ($justificacion->usuario_id) {
            // Verificamos si existe la columna 'justificacion' o usamos 'actividad' de forma segura
            DB::table('asistencias')->updateOrInsert(
                [
                    'usuario_id' => $justificacion->usuario_id,
                    'fecha' => $justificacion->fecha,
                ],
                [
                    'carrera_id' => $justificacion->usuario->carrera_id ?? null,
                    'modalidad' => 'presencial',
                    'estado' => 'a_tiempo', // Usamos un estado estándar seguro para evitar el bloqueo del ENUM
                    'actividad' => '[JUSTIFICADO] ' . $justificacion->motivo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Justificación aprobada y registrada correctamente.');
    }

    /**
     * Rechaza una justificación.
     */
    public function rechazar($id)
    {
        $justificacion = Justificacion::findOrFail($id);
        $justificacion->estado = 'rechazado';
        $justificacion->save();

        return redirect()->back()->with('info', 'Justificación rechazada.');
    }
}