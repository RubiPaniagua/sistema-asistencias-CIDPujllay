<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = Usuario::where('email', $request->email)
            ->where('rol', 'admin')
            ->where('activo', true)
            ->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'ok' => false,
                'error' => 'Credenciales inválidas',
            ], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'ok' => true,
            'token' => $token,
            'nombre' => $admin->nombre_completo,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['ok' => true], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'ok' => true,
            'nombre' => $request->user()->nombre_completo,
            'email' => $request->user()->email,
        ], 200);
    }
}