<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            Log::info("Usuario no autenticado. Redirigiendo a login.");
            return redirect()->route('login');
        }
        $userRole = Auth::user()->role;

        if (auth()->user()->role != $role) {
            Log::warning("Acceso denegado al usuario con rol: " . $userRole);
            abort(403, 'No tiene permisos para acceder a esta sección');
        }

        return $next($request);
    }
}
