<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Módulos pagados por empresa: empresas.modulos_activos (JSONB).
 * Si null = la empresa tiene todos los módulos globalmente habilitados.
 * Si array = solo las claves listadas están habilitadas para esa empresa.
 * Lógica final: module_enabled_global && empresaPermiteModulo(empresa, clave).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('empresas')) {
            return;
        }
        if (Schema::hasColumn('empresas', 'modulos_activos')) {
            return;
        }
        Schema::table('empresas', function (Blueprint $table) {
            if (config('database.default') === 'pgsql') {
                $table->jsonb('modulos_activos')->nullable()->comment('Claves de módulos habilitados para esta empresa; null = todos los globales');
            } else {
                $table->json('modulos_activos')->nullable()->comment('Claves de módulos habilitados para esta empresa; null = todos los globales');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('empresas', 'modulos_activos')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->dropColumn('modulos_activos');
            });
        }
    }
};
