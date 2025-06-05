<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // Redirigir según el rol del usuario
        if ($request->user()->role === 'admin') {
            return redirect()->route('dashboard');
        }
        if ($request->user()->role === 'user') {
            return redirect()->route('dashboard');
        }
        if ($request->user()->role === 'cliente') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
    }
}
