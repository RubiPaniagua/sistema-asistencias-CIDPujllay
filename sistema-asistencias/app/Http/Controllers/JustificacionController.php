<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Asistencia;
use App\Models\Justificacion;

class JustificacionController extends Controller
{
    /**
     * Muestra el formulario de justificación.
     */
    public function create()
    {
        return view('justificaciones.create');
    }

    /**
     * Guarda una justificación.
     */
    public function store(Request $request)
    {
        $request->validate([
            'identificacion' => 'required|string|max:20',
            'tipo' => 'required|in:tardanza,inasistencia',
            'fecha' => 'required|date',
            'motivo' => 'required|string|max:1000',
            'evidencia' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Buscar usuario activo por DNI
        $usuario = Usuario::where('dni', $request->identificacion)
            ->where('activo', true)
            ->first();

        if (!$usuario) {
            return redirect()->back()
                ->withErrors([
                    'identificacion' => 'No se encontró un usuario activo con ese DNI.'
                ])
                ->withInput();
        }

        // Buscar asistencia del usuario para esa fecha
        $asistencia = Asistencia::where('usuario_id', $usuario->id)
            ->whereDate('fecha', $request->fecha)
            ->first();

        /*
         * Para justificar una tardanza:
         * - debe existir una asistencia
         * - esa asistencia debe tener estado "tardanza"
         */
        if (
            $request->tipo === 'tardanza' &&
            (!$asistencia || $asistencia->estado !== 'tardanza')
        ) {
            return redirect()->back()
                ->withErrors([
                    'fecha' => 'No existe una tardanza registrada para esa fecha.'
                ])
                ->withInput();
        }

        /*
         * Evitar que el mismo usuario registre
         * dos justificaciones del mismo tipo
         * para la misma fecha.
         */
        $yaExiste = Justificacion::where('usuario_id', $usuario->id)
            ->whereDate('fecha', $request->fecha)
            ->where('tipo', $request->tipo)
            ->exists();

        if ($yaExiste) {
            return redirect()->back()
                ->withErrors([
                    'fecha' => 'Ya existe una justificación de este tipo para esa fecha.'
                ])
                ->withInput();
        }

        // Guardar evidencia si existe
        $rutaEvidencia = null;

        if ($request->hasFile('evidencia')) {
            $rutaEvidencia = $request->file('evidencia')
                ->store('evidencias', 'public');
        }

        // Crear justificación
        Justificacion::create([
            'usuario_id' => $usuario->id,
            'asistencia_id' => $asistencia?->id,
            'fecha' => $request->fecha,
            'tipo' => $request->tipo,
            'motivo' => $request->motivo,
            'evidencia_path' => $rutaEvidencia,
        ]);

        return redirect()->back()
            ->with('success', 'Justificación enviada correctamente.');
    }

    /**
     * Lista las justificaciones para el administrador.
     */
    public function index()
    {
        $justificaciones = Justificacion::with([
            'usuario.carrera',
            'usuario.institucion',
            'asistencia'
        ])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $justificaciones
        ], 200);
    }
}