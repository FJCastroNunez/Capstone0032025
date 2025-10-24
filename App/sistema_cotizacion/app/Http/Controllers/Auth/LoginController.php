<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user->rol == 1) {
            return route('usuarios.index'); // admin
        } elseif ($user->rol == 2) {
            return route('cotizaciones.index'); // vendedor
        }

        return route('login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
