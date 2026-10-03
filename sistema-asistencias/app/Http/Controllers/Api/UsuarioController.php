<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

            'email' => 'required_if:rol,admin|nullable|email|unique:usuarios,email',
            'password' => 'required_if:rol,admin|nullable|string|min:6',
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
                ? Hash::make($request->password)
                : null,
        ]);

        $usuario->load(['carrera', 'institucion']);

        return response()->json([
            'ok' => true,
            'usuario' => $usuario
        ], 201);
    }

    public function update(Usuario $usuario, Request $request)
    {
        $request->validate([
            'dni' => [
                'sometimes',
                'digits:8',
                Rule::unique('usuarios', 'dni')->ignore($usuario->id),
            ],
    
            'nombres' => 'sometimes|required|string',
            'apellidos' => 'sometimes|required|string',
    
            'rol' => 'sometimes|required|in:admin,practicante',
    
            'carrera_id' => 'nullable|exists:carreras,id',
            'institucion_id' => 'nullable|exists:instituciones,id',
    
            'modalidad' => 'nullable|in:presencial,remoto',
    
            'email' => [
                'nullable',
                'email',
                Rule::unique('usuarios', 'email')->ignore($usuario->id),
            ],
    
            'activo' => 'sometimes|boolean',
    
            'password' => 'nullable|string|min:6',
        ]);
    
        // Si se quiere convertir a admin,
        // debe tener email y contraseña.
        if ($request->rol === 'admin') {
    
            $emailDisponible =
                $request->filled('email') || !empty($usuario->email);
    
            $passwordDisponible =
                $request->filled('password') || !empty($usuario->password);
    
            if (!$emailDisponible || !$passwordDisponible) {
                return response()->json([
                    'ok' => false,
                    'error' => 'Para convertir un usuario en admin debe tener email y contraseña.'
                ], 422);
            }
        }
    
        $datos = $request->only([
            'dni',
            'nombres',
            'apellidos',
            'carrera_id',
            'institucion_id',
            'rol',
            'modalidad',
            'email',
            'activo',
        ]);
    
        // Si el usuario pasa a ser admin, no necesita modalidad
        if (
            isset($datos['rol']) &&
            $datos['rol'] === 'admin'
        ) {
            $datos['modalidad'] = null;
        }
    
        // La contraseña solo cambia si se envía una nueva
        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }
    
        $usuario->update($datos);
    
        $usuario->load(['carrera', 'institucion']);
    
        return response()->json([
            'ok' => true,
            'usuario' => $usuario
        ], 200);
    }
}