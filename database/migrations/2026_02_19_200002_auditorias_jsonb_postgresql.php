<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * En PostgreSQL, usar JSONB para cambios_antes y cambios_despues (mejor rendimiento e índices).
 * Solo se ejecuta con driver pgsql.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql' || ! Schema::hasTable('auditorias')) {
            return;
        }
        if (Schema::hasColumn('auditorias', 'cambios_antes')) {
            DB::statement('ALTER TABLE auditorias ALTER COLUMN cambios_antes TYPE jsonb USING cambios_antes::jsonb');
        }
        if (Schema::hasColumn('auditorias', 'cambios_despues')) {
            DB::statement('ALTER TABLE auditorias ALTER COLUMN cambios_despues TYPE jsonb USING cambios_despues::jsonb');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql' || ! Schema::hasTable('auditorias')) {
            return;
        }
        DB::statement('ALTER TABLE auditorias ALTER COLUMN cambios_antes TYPE json USING cambios_antes::json');
        DB::statement('ALTER TABLE auditorias ALTER COLUMN cambios_despues TYPE json USING cambios_despues::json');
    }
};
