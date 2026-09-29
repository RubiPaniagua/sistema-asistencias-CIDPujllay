<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Carrera;
use App\Models\Institucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index(Request $request)
    {
        $usuarios = Usuario::with(['carrera', 'institucion'])->get();

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'data' => $usuarios
            ], 200);
        }

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario (solo para Blade).
     */
    public function create()
    {
        $carreras = Carrera::all();
        $instituciones = Institucion::all();

        return view('admin.usuarios.create', compact('carreras', 'instituciones'));
    }

    /**
     * Guarda un usuario recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8|unique:usuarios,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'carrera_id' => 'nullable|exists:carreras,id',
            'institucion_id' => 'nullable|exists:instituciones,id',
            'rol' => 'required|in:admin,practicante',
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
            'modalidad' => $request->rol === 'admin' ? null : $request->modalidad,
            'activo' => true,
            'email' => $request->email,
            'password' => $request->rol === 'admin' 
                ? Hash::make($request->password ?? 'password') 
                : null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Usuario creado correctamente',
                'data' => $usuario
            ], 201);
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario creado correctamente');
    }

    /**
     * Muestra el formulario para editar un usuario (solo para Blade).
     */
    public function edit(Usuario $usuario)
    {
        $carreras = Carrera::all();
        $instituciones = Institucion::all();

        return view('admin.usuarios.edit', compact('usuario', 'carreras', 'instituciones'));
    }

    /**
     * Actualiza un usuario existente en la base de datos.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'dni' => 'required|digits:8|unique:usuarios,dni,' . $usuario->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'carrera_id' => 'nullable|exists:carreras,id',
            'institucion_id' => 'nullable|exists:instituciones,id',
            'rol' => 'required|in:admin,practicante',
            'modalidad' => 'nullable|in:presencial,remoto',
            'email' => 'nullable|email|unique:usuarios,email,' . $usuario->id,
        ]);

        $usuario->update([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'carrera_id' => $request->carrera_id,
            'institucion_id' => $request->institucion_id,
            'rol' => $request->rol,
            'modalidad' => $request->rol === 'admin' ? null : $request->modalidad,
            'email' => $request->email,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Usuario actualizado correctamente',
                'data' => $usuario
            ], 200);
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario actualizado correctamente');
    }

    /**
     * Cambia el estado activo/inactivo (baja lógica para conservar asistencias).
     */
    public function toggleActivo(Request $request, Usuario $usuario)
    {
        $usuario->update([
            'activo' => !$usuario->activo
        ]);

        $mensaje = $usuario->activo ? 'Usuario activado' : 'Usuario desactivado';

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $mensaje,
                'activo' => $usuario->activo
            ], 200);
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('mensaje', $mensaje);
    }

    /**
     * Elimina el usuario físicamente de la base de datos.
     */
    public function destroy(Request $request, Usuario $usuario)
    {
        $usuario->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Usuario eliminado correctamente'
            ], 200);
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario eliminado');
    }
}