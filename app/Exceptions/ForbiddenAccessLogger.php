<?php

namespace App\Exceptions;

use App\Services\AuditoriaService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Registra en log security y tabla auditorías los accesos denegados (403 / AuthorizationException).
 */
class ForbiddenAccessLogger
{
    public static function logIfForbidden(Throwable $e, Request $request): void
    {
        $status = null;
        if ($e instanceof HttpException && $e->getStatusCode() === 403) {
            $status = 403;
        } elseif ($e instanceof AuthorizationException) {
            $status = 403;
        }
        if ($status !== 403) {
            return;
        }

        $user = $request->user();
        $metadata = [
            'ruta' => $request->path(),
            'metodo' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];
        if ($user) {
            $metadata['user_id'] = $user->id_usuario;
            $metadata['rol_id'] = $user->rol_id ?? null;
            if ($user->sucursal) {
                $metadata['sucursal_id'] = $user->sucursal_id;
                $metadata['empresa_id'] = $user->sucursal->empresa_id ?? null;
            }
        }
        if ($e instanceof AuthorizationException && $e->getMessage()) {
            $metadata['message'] = $e->getMessage();
        }

        Log::channel('security')->warning('forbidden_access', $metadata);
        AuditoriaService::registrar('forbidden_access', 'sistema', null, null, null, $metadata);
    }
}
