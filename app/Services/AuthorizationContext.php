<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Reglas centrales de autorización multi-empresa/sucursal.
 * Anti-fugas: supervisor solo dentro de su empresa; usuario solo propios + sucursal según reglas.
 */
class AuthorizationContext
{
    /**
     * El usuario debe pertenecer a la misma empresa que el recurso (vía sucursal).
     * Admin se considera con acceso global (no se lanza).
     */
    public static function assertSameEmpresa(User $user, ?int $resourceEmpresaId): void
    {
        if ($user->esAdministrador()) {
            return;
        }
        if ($resourceEmpresaId === null) {
            return;
        }
        $user->loadMissing('sucursal');
        $userEmpresaId = $user->sucursal?->empresa_id;
        if ($userEmpresaId === null) {
            throw new AuthorizationException('Sin empresa asignada.');
        }
        if ((int) $userEmpresaId !== (int) $resourceEmpresaId) {
            throw new AuthorizationException('No pertenece a la empresa del recurso.');
        }
    }

    /**
     * El usuario debe pertenecer a la misma sucursal que el recurso (o ser admin).
     */
    public static function assertSameSucursal(User $user, ?int $resourceSucursalId): void
    {
        if ($user->esAdministrador()) {
            return;
        }
        if ($resourceSucursalId === null) {
            return;
        }
        if ((int) $user->sucursal_id !== (int) $resourceSucursalId) {
            throw new AuthorizationException('No pertenece a la sucursal del recurso.');
        }
    }

    /**
     * Obtiene empresa_id del usuario (vía sucursal).
     */
    public static function getUserEmpresaId(User $user): ?int
    {
        $user->loadMissing('sucursal');
        $id = $user->sucursal?->empresa_id;
        return $id !== null ? (int) $id : null;
    }
}
