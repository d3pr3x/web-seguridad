<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Renombrar columnas de usuarios (run_carabineros -> run, rango_carabineros -> rango)
     * y actualizar tipo en acciones (concurrencia_carabineros -> concurrencia_autoridades).
     */
    public function up(): void
    {
        if (Schema::hasTable('usuarios')) {
            if (Schema::hasColumn('usuarios', 'run_carabineros')) {
                DB::statement('ALTER TABLE usuarios RENAME COLUMN run_carabineros TO run');
            }
            if (Schema::hasColumn('usuarios', 'rango_carabineros')) {
                DB::statement('ALTER TABLE usuarios RENAME COLUMN rango_carabineros TO rango');
            }
        }

        if (Schema::hasTable('acciones')) {
            DB::table('acciones')
                ->where('tipo', 'concurrencia_carabineros')
                ->update(['tipo' => 'concurrencia_autoridades']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('usuarios')) {
            if (Schema::hasColumn('usuarios', 'run')) {
                DB::statement('ALTER TABLE usuarios RENAME COLUMN run TO run_carabineros');
            }
            if (Schema::hasColumn('usuarios', 'rango')) {
                DB::statement('ALTER TABLE usuarios RENAME COLUMN rango TO rango_carabineros');
            }
        }

        if (Schema::hasTable('acciones')) {
            DB::table('acciones')
                ->where('tipo', 'concurrencia_autoridades')
                ->update(['tipo' => 'concurrencia_carabineros']);
        }
    }
};
