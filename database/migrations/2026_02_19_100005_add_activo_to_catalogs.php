<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * B2: Columna activo (boolean default true) en catálogos y entidades administrables.
     * Si la tabla usa "activa" en lugar de "activo", no se duplica.
     */
    public function up(): void
    {
        $tablesColumn = [
            'roles_usuario' => 'activo',
            'permisos' => 'activo',
            'tareas' => 'activo',
            'puntos_ronda' => 'activo',
            'ubicaciones_permitidas' => 'activo',
            'dispositivos_permitidos' => 'activo',
            'grupos_incidentes' => 'activo',
        ];

        foreach ($tablesColumn as $tableName => $col) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            if (Schema::hasColumn($tableName, $col) || Schema::hasColumn($tableName, 'activa')) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) use ($col) {
                $table->boolean($col)->default(true);
            });
        }

        if (Schema::hasTable('tipos_incidente') && !Schema::hasColumn('tipos_incidente', 'activo')) {
            Schema::table('tipos_incidente', function (Blueprint $table) {
                $table->boolean('activo')->default(true);
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'roles_usuario', 'permisos', 'tareas', 'puntos_ronda',
            'ubicaciones_permitidas', 'dispositivos_permitidos',
            'grupos_incidentes', 'tipos_incidente',
        ];
        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, 'activo')) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('activo');
            });
        }
    }
};
