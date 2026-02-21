<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * C1: Tabla auditorias (trazabilidad meticulosa). user_id = quien lo hizo.
     */
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->string('accion', 50); // create, update, delete, restore, force_delete, toggle_activo, login, etc.
            $table->string('tabla');
            $table->string('registro_id', 100)->nullable();
            $table->string('route', 255)->nullable();
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('cambios_antes')->nullable();
            $table->json('cambios_despues')->nullable();
            $table->timestamp('ocurrido_en');
            $table->json('metadata')->nullable();

            $table->index(['tabla', 'accion', 'ocurrido_en']);
            $table->index('user_id');
            $table->index('empresa_id');
        });

        if (Schema::hasTable('usuarios')) {
            Schema::table('auditorias', function (Blueprint $table) {
                $table->foreign('user_id')->references('id_usuario')->on('usuarios')->nullOnDelete();
            });
        }
        if (Schema::hasTable('empresas')) {
            Schema::table('auditorias', function (Blueprint $table) {
                $table->foreign('empresa_id')->references('id')->on('empresas')->nullOnDelete();
            });
        }
        if (Schema::hasTable('sucursales')) {
            Schema::table('auditorias', function (Blueprint $table) {
                $table->foreign('sucursal_id')->references('id')->on('sucursales')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('auditorias', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['empresa_id']);
            $table->dropForeign(['sucursal_id']);
        });
        Schema::dropIfExists('auditorias');
    }
};
