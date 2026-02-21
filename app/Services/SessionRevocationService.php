<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Invalidación de sesiones en cambios críticos (password, rol/sucursal, desactivación).
 */
class SessionRevocationService
{
    /**
     * Revoca todas las demás sesiones del usuario (mantiene la actual si coincide).
     * Requiere que la tabla sessions tenga columna user_id y que se actualice en Login.
     */
    public static function revokeOtherSessionsForUser(int $userId, ?string $reason = null): int
    {
        $table = config('session.table', 'sesiones');
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'user_id')) {
            return 0;
        }
        $currentId = request()->session()->getId();
        $deleted = DB::table($table)
            ->where('user_id', $userId)
            ->where('id', '!=', $currentId)
            ->delete();
        if ($deleted > 0 && $reason !== null) {
            AuditoriaService::registrar('sessions_revoked', 'sistema', (string) $userId, null, null, [
                'reason' => $reason,
                'count' => $deleted,
            ]);
        }
        return $deleted;
    }
}
