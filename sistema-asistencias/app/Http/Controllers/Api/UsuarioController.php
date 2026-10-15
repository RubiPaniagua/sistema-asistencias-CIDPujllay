<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
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
            'data' => $usuarios
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:usuarios,codigo',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|unique:usuarios,dni',
            'rol' => 'required|in:practicante,monitor,admin',
            'modalidad' => 'required|in:presencial,remoto',
            'carrera_id' => 'required|exists:carreras,id',
        ]);

        $usuario = Usuario::create([
            'codigo' => $request->codigo,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'dni' => $request->dni,
            'rol' => $request->rol,
            'modalidad' => $request->modalidad,
            'carrera_id' => $request->carrera_id,
            'institucion_id' => $request->institucion_id ?? 1,
            'activo' => true,
            'password' => $request->password ? Hash::make($request->password) : Hash::make('12345678'),
        ]);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Usuario registrado exitosamente',
            'usuario' => $usuario
        ], 201);
    }

    public function destroy($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['ok' => false, 'error' => 'Usuario no encontrado'], 404);
        }

        // Eliminación permanente de la base de datos
        $usuario->delete();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Usuario eliminado permanentemente'
        ], 200);
    }

    public function toggleEstado($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['ok' => false, 'error' => 'Usuario no encontrado'], 404);
        }

        // Alternar el estado activo / inactivo
        $usuario->activo = !$usuario->activo;
        $usuario->save();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Estado del usuario actualizado correctamente',
            'activo' => $usuario->activo
        ], 200);
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
            'carrera_id' => 'required|exists:carreras,id',
            'rol' => 'required|in:admin,practicante,monitor',
            'modalidad' => 'nullable|in:presencial,remoto',
        ]);

        $usuario->update([
            'codigo' => $request->dni,
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'carrera_id' => $request->carrera_id,
            'rol' => $request->rol,
            'modalidad' => $request->modalidad,
        ]);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Usuario actualizado correctamente',
            'usuario' => $usuario
        ], 200);
    }
}