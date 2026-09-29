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

        return response()->json($usuarios, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8|unique:usuarios,dni',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'rol' => 'required|in:admin,practicante',
            'carrera_id' => 'nullable|exists:carreras,id',
            'institucion_id' => 'nullable|exists:instituciones,id',
            'modalidad' => 'nullable|in:presencial,remoto',
            'email' => 'nullable|email|unique:usuarios,email',
            'password' => 'nullable|string|min:6',
        ]);

        $usuario = Usuario::create([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'carrera_id' => $request->carrera_id,
            'institucion_id' => $request->institucion_id,
            'rol' => $request->rol,
            'modalidad' => $request->rol === 'admin'
                ? null
                : $request->modalidad,
            'activo' => true,
            'email' => $request->email,
            'password' => $request->rol === 'admin'
                ? Hash::make($request->password ?? 'password')
                : null,
        ]);

        return response()->json([
            'ok' => true,
            'usuario' => $usuario
        ], 201);
    }

    public function update(Usuario $usuario, Request $request)
    {
        $usuario->update(
            $request->only([
                'nombres',
                'apellidos',
                'carrera_id',
                'institucion_id',
                'modalidad',
            ])
        );

        return response()->json([
            'ok' => true,
            'usuario' => $usuario
        ], 200);
    }

    public function destroy(Usuario $usuario)
    {
        // Baja lógica
        $usuario->update([
            'activo' => false
        ]);

        return response()->json([
            'ok' => true
        ], 200);
    }
}