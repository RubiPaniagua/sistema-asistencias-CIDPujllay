<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;

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
}