<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Al hacer login, actualiza la fila de sesión con user_id para poder revocar otras sesiones después.
 */
class UpdateSessionUserIdOnLogin
{
    public function handle(Login $event): void
    {
        $table = config('session.table', 'sesiones');
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'user_id')) {
            return;
        }
        $user = $event->user;
        $userId = $user->getAuthIdentifier();
        $sessionId = request()->session()->getId();
        DB::table($table)->where('id', $sessionId)->update(['user_id' => $userId]);
    }
}
