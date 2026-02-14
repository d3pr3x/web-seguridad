<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: reuniones (español).
     */
    public function up(): void
    {
        Schema::create('reuniones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_reunion')->nullable();
            $table->string('ubicacion', 255)->nullable();
            $table->unsignedBigInteger('id_usuario_creador')->nullable();
            $table->string('estado', 40)->default('programada');
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
        Schema::table('reuniones', function (Blueprint $table) {
            $table->foreign('id_usuario_creador')->references('id_usuario')->on('usuarios')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reuniones');
    }
};
