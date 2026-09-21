<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // 1. Validação dos campos
        $request->validate([
            'identificacion' => 'required|string|max:20',
            'tipo'           => 'required|in:tardanza,inasistencia',
            'fecha'          => 'required|date',
            'motivo'         => 'required|string|max:1000',
            'evidencia'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // 2. Upload do arquivo de evidência (se houver)
        $rutaEvidencia = null;
        if ($request->hasFile('evidencia')) {
            $rutaEvidencia = $request->file('evidencia')->store('evidencias', 'public');
        }

        // 3. Salvar no banco de dados (exemplo de lógica)
        /*
        Justificacion::create([
            'identificacion' => $request->identificacion,
            'tipo'           => $request->tipo,
            'fecha'          => $request->fecha,
            'motivo'         => $request->motivo,
            'evidencia_path' => $rutaEvidencia,
            'estado'         => 'pendiente',
        ]);
        */

        return redirect()->back()->with('success', 'Sua justificativa foi enviada com sucesso e aguarda análise.');
    }
}