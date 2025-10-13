<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentos_personales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('tipo_documento', ['cedula_identidad', 'licencia_conductor', 'certificado_antecedentes', 'certificado_os10']);
            $table->string('imagen_frente')->nullable();
            $table->string('imagen_reverso')->nullable();
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('motivo_rechazo')->nullable();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('aprobado_en')->nullable();
            $table->boolean('es_cambio')->default(false); // Indica si es un cambio de documento existente
            $table->foreignId('documento_anterior_id')->nullable()->constrained('documentos_personales')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_personales');
    }
};
