<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Carrera;
use App\Models\Institucion;

class UsuarioController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     * La modificación de datos se realiza mediante la API.
     */
    public function index()
    {
        $usuarios = Usuario::with([
            'carrera',
            'institucion'
        ])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un usuario.
     *
     * El formulario debe enviar los datos mediante fetch
     * a POST /api/usuarios.
     */
    public function create()
    {
        $carreras = Carrera::all();
        $instituciones = Institucion::all();

        return view(
            'admin.usuarios.create',
            compact('carreras', 'instituciones')
        );
    }

    /**
     * Muestra el formulario para editar un usuario.
     *
     * La actualización debe realizarse mediante fetch
     * a PUT /api/usuarios/{usuario}.
     */
    public function edit(Usuario $usuario)
    {
        $carreras = Carrera::all();
        $instituciones = Institucion::all();

        return view(
            'admin.usuarios.edit',
            compact(
                'usuario',
                'carreras',
                'instituciones'
            )
        );
    }
}