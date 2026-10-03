<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    public function index(Request $request)
    {
        $carreras = Carrera::all();

        if ($request->wantsJson()) {
            return response()->json($carreras, 200);
        }

        return view('carreras.index', compact('carreras'));
    }

    public function show(Request $request, Carrera $carrera)
    {
        if ($request->wantsJson()) {
            return response()->json($carrera, 200);
        }

        return view('carreras.show', compact('carrera'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:carreras,nombre',
        ]);

        $carrera = Carrera::create([
            'nombre' => $request->nombre,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Carrera creada correctamente',
                'carrera' => $carrera
            ], 201);
        }

        return redirect()
            ->route('carreras.index')
            ->with('success', 'Carrera creada correctamente');
    }

    public function update(Request $request, Carrera $carrera)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:carreras,nombre,' . $carrera->id,
        ]);

        $carrera->update([
            'nombre' => $request->nombre,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Carrera actualizada correctamente',
                'carrera' => $carrera
            ], 200);
        }

        return redirect()
            ->route('carreras.index')
            ->with('success', 'Carrera actualizada correctamente');
    }

    public function destroy(Request $request, Carrera $carrera)
    {
        if ($carrera->usuarios()->exists()) {
    
            if ($request->wantsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No se puede eliminar la carrera porque tiene usuarios asociados.'
                ], 409);
            }
    
            return redirect()
                ->route('carreras.index')
                ->with('error', 'No se puede eliminar la carrera porque tiene usuarios asociados.');
        }
    
        $carrera->delete();
    
        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Carrera eliminada correctamente'
            ], 200);
        }
    
        return redirect()
            ->route('carreras.index')
            ->with('success', 'Carrera eliminada correctamente');
    }
}