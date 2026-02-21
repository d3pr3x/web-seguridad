<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    /**
     * Si el módulo no está activado, responde 404 para no exponer la funcionalidad.
     *
     * @param  string  $module  Clave del módulo en config('modules.modules').
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (! module_enabled($module)) {
            abort(404);
        }
        if (! module_enabled_for_empresa($module)) {
            abort(404);
        }

        return $next($request);
    }
}
