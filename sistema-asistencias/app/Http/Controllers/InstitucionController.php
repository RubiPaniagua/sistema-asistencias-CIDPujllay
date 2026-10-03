<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Illuminate\Http\Request;

class InstitucionController extends Controller
{
    public function index()
    {
        $instituciones = Institucion::orderBy('nombre')->get();

        return response()->json($instituciones, 200);
    }

    public function show(Institucion $institucion)
    {
        return response()->json($institucion, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:instituciones,nombre',
        ]);

        $institucion = Institucion::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Institución creada correctamente',
            'institucion' => $institucion
        ], 201);
    }

    public function update(Request $request, Institucion $institucion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:instituciones,nombre,' . $institucion->id,
        ]);

        $institucion->update([
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Institución actualizada correctamente',
            'institucion' => $institucion
        ], 200);
    }

    public function destroy(Institucion $institucion)
    {
        if ($institucion->usuarios()->exists()) {
            return response()->json([
                'ok' => false,
                'message' => 'No se puede eliminar la institución porque tiene usuarios asociados.'
            ], 409);
        }
    
        $institucion->delete();
    
        return response()->json([
            'ok' => true,
            'message' => 'Institución eliminada correctamente'
        ], 200);
    }
}