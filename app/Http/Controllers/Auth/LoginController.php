<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/virtual/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request): array
    {
        $login = $request->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        return [$field => $login, 'password' => $request->input('password')];
    }

    protected function authenticated(Request $request, $user)
    {
        session()->forget('modo_acceso');

        if ($user->tieneAmbosAccesos()) {
            return redirect()->route('seleccionar-acceso');
        }
        if ($user->puedeAdmin()) {
            return redirect('/admin/dashboard');
        }
        if ($user->puedeVirtual()) {
            return redirect('/virtual/dashboard');
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Su cuenta no tiene accesos asignados. Contacte al administrador.');
    }

    public function redirectPath()
    {
        $user = $this->guard()->user();
        if (!$user) return '/login';
        return $user->urlInicio();
    }

    protected function loggedOut(Request $request)
    {
        session()->forget('modo_acceso');
        return redirect()->route('login');
    }
}
