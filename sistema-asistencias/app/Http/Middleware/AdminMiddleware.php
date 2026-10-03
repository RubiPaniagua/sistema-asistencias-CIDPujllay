<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if (!$usuario || $usuario->rol !== 'admin' || !$usuario->activo) {
            return response()->json([
                'ok' => false,
                'error' => 'Acceso no autorizado'
            ], 403);
        }

        return $next($request);
    }
}