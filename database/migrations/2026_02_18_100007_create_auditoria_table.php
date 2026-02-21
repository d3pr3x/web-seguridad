<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla auditoría: quién, qué tabla, tipo de cambio, datos anterior/nuevo, cuándo.
     */
    public function up(): void
    {
        Schema::create('auditoria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('tabla');
            $table->string('tipo_cambio', 20); // creacion, actualizacion, eliminacion
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->timestamps();

            $table->index('tabla');
            $table->index('created_at');
        });

        if (Schema::hasTable('usuarios')) {
            Schema::table('auditoria', function (Blueprint $table) {
                $table->foreign('usuario_id')->references('id_usuario')->on('usuarios')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('auditoria') && Schema::hasTable('usuarios')) {
            Schema::table('auditoria', function (Blueprint $table) {
                $table->dropForeign(['usuario_id']);
            });
        }
        Schema::dropIfExists('auditoria');
    }
};
