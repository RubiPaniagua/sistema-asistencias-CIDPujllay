<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Carrera;
use App\Models\Institucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with(['carrera', 'institucion'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'ok' => true,
            'usuarios' => $usuarios
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|size:8|unique:usuarios,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'rol' => 'required|in:admin,practicante,monitor',
            'modalidad' => 'nullable|in:presencial,remoto',
        ]);

        // Manejo de Carrera (ID existente o Nombre nuevo)
        $carreraId = $request->carrera_id;
        if (!is_numeric($carreraId) && !empty($request->nueva_carrera)) {
            $carrera = Carrera::firstOrCreate(['nombre' => trim($request->nueva_carrera)]);
            $carreraId = $carrera->id;
        } elseif (!is_numeric($carreraId)) {
            return response()->json(['ok' => false, 'message' => 'Debe seleccionar o especificar una carrera.'], 422);
        }

        // Manejo de Institución (ID existente o Nombre nuevo)
        $institucionId = $request->institucion_id;
        if (!is_numeric($institucionId) && !empty($request->nueva_institucion)) {
            $inst = Institucion::firstOrCreate(['nombre' => trim($request->nueva_institucion)]);
            $institucionId = $inst->id;
        } elseif (!is_numeric($institucionId)) {
            return response()->json(['ok' => false, 'message' => 'Debe seleccionar o especificar una institución.'], 422);
        }

        $usuario = Usuario::create([
            'codigo' => $request->dni,
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'carrera_id' => $carreraId,
            'institucion_id' => $institucionId,
            'rol' => $request->rol,
            'modalidad' => $request->modalidad ?? 'presencial',
            'activo' => true,
        ]);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Usuario creado correctamente',
            'usuario' => $usuario
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['ok' => false, 'error' => 'Usuario no encontrado'], 404);
        }

        $request->validate([
            'dni' => 'required|string|size:8|unique:usuarios,dni,' . $id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'rol' => 'required|in:admin,practicante,monitor',
            'modalidad' => 'nullable|in:presencial,remoto',
        ]);

        // Manejo de Carrera
        $carreraId = $request->carrera_id;
        if (!is_numeric($carreraId) && !empty($request->nueva_carrera)) {
            $carrera = Carrera::firstOrCreate(['nombre' => trim($request->nueva_carrera)]);
            $carreraId = $carrera->id;
        }

        // Manejo de Institución
        $institucionId = $request->institucion_id;
        if (!is_numeric($institucionId) && !empty($request->nueva_institucion)) {
            $inst = Institucion::firstOrCreate(['nombre' => trim($request->nueva_institucion)]);
            $institucionId = $inst->id;
        }

        $usuario->update([
            'codigo' => $request->dni,
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'carrera_id' => $carreraId,
            'institucion_id' => $institucionId,
            'rol' => $request->rol,
            'modalidad' => $request->modalidad,
        ]);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Usuario actualizado correctamente',
            'usuario' => $usuario
        ], 200);
    }

    public function toggleEstado($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) return response()->json(['ok' => false, 'error' => 'Usuario no encontrado'], 404);

        $usuario->activo = !$usuario->activo;
        $usuario->save();

        return response()->json(['ok' => true, 'activo' => $usuario->activo]);
    }

    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) return response()->json(['ok' => false, 'error' => 'Usuario no encontrado'], 404);

        $usuario->delete();
        return response()->json(['ok' => true]);
    }
}