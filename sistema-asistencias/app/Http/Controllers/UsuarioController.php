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
            'usuarios' => $usuarios
        ], 200);

        //aqui aqui
        $datos = $request->validate([
        'dni' => 'required|string|size:8|unique:usuarios,dni',
        'nombres' => 'required|string|max:100',
        'apellidos' => 'required|string|max:150',
        'carrera_id' => 'required|exists:carreras,id',
        'institucion_id' => 'required|exists:instituciones,id',
        'rol' => 'required|in:admin,practicante',
        'modalidad' => 'nullable|in:presencial,remoto',
        'email' => 'nullable|email|unique:usuarios,email',
        'password' => 'nullable|string|min:6',
    ]);

    $usuario = Usuario::create([
        'dni' => $datos['dni'],
        'nombres' => $datos['nombres'],
        'apellidos' => $datos['apellidos'],
        'carrera_id' => $datos['carrera_id'],
        'institucion_id' => $datos['institucion_id'],
        'rol' => $datos['rol'],
        'modalidad' => $datos['modalidad'] ?? null,
        'activo' => true,
        'email' => $datos['email'] ?? null,
        'password' => isset($datos['password'])
            ? Hash::make($datos['password'])
            : null,
    ]);

    return response()->json([
        'ok' => true,
        'mensaje' => 'Usuario creado correctamente',
        'usuario' => $usuario
    ], 201);

    }
}
