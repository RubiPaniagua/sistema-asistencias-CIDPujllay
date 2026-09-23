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
            return response()->json($carreras);
        }

        return view('carreras.index', compact('carreras'));
    }

    public function show(Carrera $carrera)
    {
        if ($request->wantsJson()) {
            return response()->json($carrera);
        }

        return view('carreras.show', compact('carrera'));
    }

    public function store(Request $request)
    {
        Carrera::create([
            "nombre" => $request->nombre,
        ]);

        // si es json
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Carrera creado correctamente'
            ]);
        }

        return view('carreras.index', [
            'mensaje' => 'Carrera creado correctamente'
        ]);
    }

    public function update(Carrera $carrera, Request $request)
    {
        $carrera->nombre = $request->nombre;
        $carrera->save();
    }

    public function destroy(Carrera $carrera)
    {
        $carrera->delete();
        // $carrera->destroy();
    }

}
