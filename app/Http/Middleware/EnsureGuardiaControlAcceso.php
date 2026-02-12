<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuardiaControlAcceso
{
    /**
     * Solo permitir acceso a guardias con perfil control_acceso (perfil 5).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->esGuardiaControlAcceso() && !auth()->user()->esAdministrador()) {
            abort(403, 'No tiene permiso para acceder al control de acceso. Solo guardias de control de acceso o administradores.');
        }

        return $next($request);
    }
}
