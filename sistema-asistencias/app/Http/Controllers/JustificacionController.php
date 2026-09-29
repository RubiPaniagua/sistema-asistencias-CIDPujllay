<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Asistencia;

class JustificacionController extends Controller
{
    /**
     * Exibe o formulário de justificativa.
     */
    public function create()
    {
        return view('justificaciones.create');
    }

    /**
     * Processa o salvamento da justificativa.
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
            $rutaEvidencia = $request->file('evidencia')
                ->store('evidencias', 'public');
        }
    
        $usuario = Usuario::where('dni', $request->identificacion)->first();
    
        if (!$usuario) {
            return redirect()->back()
                ->withErrors(['identificacion' => 'No se encontró un usuario con ese DNI.'])
                ->withInput();
        }
    
        $asistencia = Asistencia::where('usuario_id', $usuario->id)
            ->whereDate('fecha', $request->fecha)
            ->first();
    
        if (!$asistencia) {
            return redirect()->back()
                ->withErrors(['fecha' => 'No se encontró una asistencia para esa fecha.'])
                ->withInput();
        }
    
        $asistencia->update([
            'justificacion' => $request->motivo,
            'evidencia_path' => $rutaEvidencia,

        ]);
    
        return redirect()->back()
            ->with('success', 'Justificación enviada correctamente.');
    }

}