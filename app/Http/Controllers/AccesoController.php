<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccesoController extends Controller
{
    public function seleccionar()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        if (!$user->tieneAmbosAccesos()) {
            if ($user->puedeAdmin()) return redirect('/admin/dashboard');
            if ($user->puedeVirtual()) return redirect('/virtual/dashboard');
            return redirect('/login');
        }

        return view('auth.seleccionar-acceso', compact('user'));
    }

    public function entrar(Request $request, $modo)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        if ($modo === 'admin' && $user->puedeAdmin()) {
            session(['modo_acceso' => 'admin']);
            return redirect('/admin/dashboard');
        }

        if ($modo === 'virtual' && $user->puedeVirtualReal()) {
            session(['modo_acceso' => 'virtual']);
            return redirect('/virtual/dashboard');
        }

        // Si pidió "virtual" pero no es docente ni estudiante, redirigir al admin si corresponde
        if ($modo === 'virtual' && $user->puedeAdmin()) {
            return redirect('/admin/dashboard')->with('error', 'Tu cuenta no tiene perfil de estudiante ni docente, por lo que no puede ingresar al Portal Virtual.');
        }

        return redirect()->route('seleccionar-acceso')->with('error', 'No tienes ese tipo de acceso.');
    }

    public function cambiar()
    {
        $user = Auth::user();
        if (!$user) return redirect('/login');

        if (!$user->tieneAmbosAccesos()) {
            return redirect($user->urlInicio());
        }

        session()->forget('modo_acceso');
        return redirect()->route('seleccionar-acceso');
    }
}
