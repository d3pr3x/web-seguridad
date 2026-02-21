<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadUserRole
{
    /**
     * Asegura que el usuario autenticado tenga cargada la relación 'rol'
     * para que esAdministrador(), puedeVerGestion(), etc. funcionen en vistas y controladores.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && !$user->relationLoaded('rol')) {
            $user->loadMissing('rol');
        }

        return $next($request);
    }
}
