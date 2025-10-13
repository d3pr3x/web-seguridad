<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class VerificarSucursal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        /** @var User|null $user */
        $user = $request->user();
        
        if ($user) {
            // Solo los usuarios regulares necesitan tener sucursal asignada
            // Los administradores pueden acceder sin sucursal
            if (!$user->esAdministrador() && !$user->tieneSucursal()) {
                return redirect()->route('profile.index')
                    ->with('error', 'Debe estar asignado a una sucursal para acceder al sistema. Por favor, contacte al administrador.');
            }
        }
        
        return $next($request);
    }
}
