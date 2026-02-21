<?php

namespace App\Services;

use App\Models\Auditoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

class AuditoriaService
{
    public static function registrar(
        string $accion,
        string $tabla,
        $registroId = null,
        ?array $cambiosAntes = null,
        ?array $cambiosDespues = null,
        ?array $metadata = null
    ): void {
        if (!Schema::hasTable('auditorias')) {
            return;
        }

        $user = auth()->user();
        $empresaId = $user && $user->sucursal && $user->sucursal->empresa_id
            ? $user->sucursal->empresa_id
            : null;
        $sucursalId = $user ? $user->sucursal_id : null;

        Auditoria::create([
            'user_id' => $user?->id_usuario,
            'empresa_id' => $empresaId,
            'sucursal_id' => $sucursalId,
            'accion' => $accion,
            'tabla' => $tabla,
            'registro_id' => $registroId !== null ? (string) $registroId : null,
            'route' => Request::path(),
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'cambios_antes' => $cambiosAntes,
            'cambios_despues' => $cambiosDespues,
            'ocurrido_en' => now(),
            'metadata' => $metadata,
        ]);
    }

    public static function desdeModelo(Model $model, string $accion, ?array $antes = null, ?array $despues = null): void
    {
        $tabla = $model->getTable();
        $id = $model->getKey();
        self::registrar($accion, $tabla, $id, $antes, $despues);
    }
}
