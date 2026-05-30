<?php

namespace App\Http\Middleware;

use App\Models\Modulo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDocenteModule
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $moduloId = $request->route('moduloId');
        $docente  = $user->persona?->docente;

        if ($docente && $moduloId) {
            $owns = Modulo::where('id', (int) $moduloId)
                ->where('docente_id', $docente->id)
                ->exists();
            if ($owns) {
                return $next($request);
            }
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['error' => 'No autorizado para este módulo.'], 403);
        }

        abort(403, 'No tienes permiso para acceder a este módulo.');
    }
}
