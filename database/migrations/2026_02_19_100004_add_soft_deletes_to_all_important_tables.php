<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * B1: Soft delete en todas las tablas importantes. Si ya tienen deleted_at, se omite.
     */
    public function up(): void
    {
        $tables = [
            'empresas', 'sucursales', 'sectores', 'usuarios', 'roles_usuario', 'permisos', 'rol_permiso',
            'tareas', 'detalles_tarea', 'reportes', 'informes',
            'acciones', 'reportes_especiales',
            'puntos_ronda', 'escaneos_ronda',
            'ingresos', 'blacklists', 'personas',
            'documentos', 'reuniones',
            'dias_trabajados', 'configuraciones_sueldo', 'feriados',
            'ubicaciones_permitidas', 'dispositivos_permitidos',
            'grupos_incidentes', 'tipos_incidente', 'modalidades_jerarquia', 'modalidad_roles',
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            if (Schema::hasColumn($tableName, 'deleted_at')) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'empresas', 'sucursales', 'sectores', 'usuarios', 'roles_usuario', 'permisos', 'rol_permiso',
            'tareas', 'detalles_tarea', 'reportes', 'informes',
            'acciones', 'reportes_especiales',
            'puntos_ronda', 'escaneos_ronda',
            'ingresos', 'blacklists', 'personas',
            'documentos', 'reuniones',
            'dias_trabajados', 'configuraciones_sueldo', 'feriados',
            'ubicaciones_permitidas', 'dispositivos_permitidos',
            'grupos_incidentes', 'tipos_incidente', 'modalidades_jerarquia', 'modalidad_roles',
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, 'deleted_at')) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
