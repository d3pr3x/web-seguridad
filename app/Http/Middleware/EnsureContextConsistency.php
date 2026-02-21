<?php

namespace App\Http\Middleware;

use App\Models\Sector;
use App\Models\Sucursal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Anti-fugas: valida que sector_id / sucursal_id / empresa_id del request
 * formen una cadena coherente y que usuarios no-admin no operen fuera de su contexto.
 */
class EnsureContextConsistency
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        $sector = $request->route('sector');
        $sucursal = $request->route('sucursal');
        $sectorId = $request->input('sector_id') ?? (is_object($sector) ? $sector->id : $sector);
        $sucursalId = $request->input('sucursal_id') ?? (is_object($sucursal) ? $sucursal->id : $sucursal);
        $empresaId = $request->input('empresa_id');
        $cliente = $request->route('cliente');
        if (! $empresaId && $cliente) {
            $empresaId = is_object($cliente) ? $cliente->id : $cliente;
        }

        if ($sectorId) {
            $sector = Sector::find($sectorId);
            if ($sector && $sucursalId && (int) $sector->sucursal_id !== (int) $sucursalId) {
                abort(403, 'El sector no pertenece a la sucursal indicada.');
            }
            if ($sector && ! $user->esAdministrador() && $user->sucursal_id && (int) $sector->sucursal_id !== (int) $user->sucursal_id) {
                abort(403, 'No puede operar sobre sectores de otra sucursal.');
            }
        }

        if ($sucursalId) {
            $sucursal = Sucursal::find($sucursalId);
            if ($sucursal && $empresaId && (int) $sucursal->empresa_id !== (int) $empresaId) {
                abort(403, 'La sucursal no pertenece a la empresa indicada.');
            }
            if ($sucursal && ! $user->esAdministrador() && $user->sucursal_id && (int) $sucursal->id !== (int) $user->sucursal_id) {
                abort(403, 'No puede operar sobre otra sucursal.');
            }
        }

        return $next($request);
    }
}
