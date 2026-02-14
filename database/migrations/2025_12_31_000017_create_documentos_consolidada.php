<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: documentos (español). Reemplaza documentos_personales. FK id_usuario → usuarios.
     */
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->enum('tipo_documento', ['cedula_identidad', 'licencia_conductor', 'certificado_antecedentes', 'certificado_os10']);
            $table->string('imagen_frente')->nullable();
            $table->string('imagen_reverso')->nullable();
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('motivo_rechazo')->nullable();
            $table->unsignedBigInteger('aprobado_por')->nullable();
            $table->timestamp('aprobado_en')->nullable();
            $table->boolean('es_cambio')->default(false);
            $table->unsignedBigInteger('documento_anterior_id')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
        Schema::table('documentos', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('aprobado_por')->references('id_usuario')->on('usuarios')->onDelete('set null');
            $table->foreign('documento_anterior_id')->references('id')->on('documentos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
