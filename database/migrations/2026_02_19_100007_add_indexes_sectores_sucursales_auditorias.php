<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * D2: Índices para consultas por sector, sucursal y auditorías.
     */
    public function up(): void
    {
        if (Schema::hasTable('sectores') && Schema::hasColumn('sectores', 'deleted_at')) {
            Schema::table('sectores', function (Blueprint $table) {
                $table->index(['sucursal_id', 'deleted_at']);
            });
        }

        if (Schema::hasTable('sucursales') && Schema::hasColumn('sucursales', 'deleted_at')) {
            Schema::table('sucursales', function (Blueprint $table) {
                $table->index(['empresa_id', 'activa', 'deleted_at']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sectores')) {
            Schema::table('sectores', function (Blueprint $table) {
                $table->dropIndex(['sucursal_id', 'deleted_at']);
            });
        }
        if (Schema::hasTable('sucursales')) {
            Schema::table('sucursales', function (Blueprint $table) {
                $table->dropIndex(['empresa_id', 'activa', 'deleted_at']);
            });
        }
    }
};
