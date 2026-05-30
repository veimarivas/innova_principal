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

    public function redirectPath()
    {
        $user = $this->guard()->user();
        if ($user && $user->role === 'moodle') {
            return '/virtual/dashboard';
        }
        return '/admin/dashboard';
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }
}
