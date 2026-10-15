<?php

namespace App\Http\Controllers;

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
        return view('admin.usuarios.index');
    }

    public function create()
    {
        $carreras = Carrera::all();
        $instituciones = Institucion::all();
        return view('admin.usuarios.create', compact('carreras', 'instituciones'));
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $carreras = Carrera::all(); // Cargamos todas las carreras de la base de datos
        $instituciones = Institucion::all();
        
        return view('admin.usuarios.edit', compact('usuario', 'carreras', 'instituciones'));
    }
}