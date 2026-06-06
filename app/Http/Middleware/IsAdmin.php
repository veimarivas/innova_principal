<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Debes iniciar sesión.');
        }

        if (!$user->puedeAdmin()) {
            return redirect('/')->with('error', 'No tienes acceso a esta sección.');
        }

        if ($user->tieneAmbosAccesos() && !session('modo_acceso')) {
            return redirect()->route('seleccionar-acceso');
        }

        return $next($request);
    }
}
