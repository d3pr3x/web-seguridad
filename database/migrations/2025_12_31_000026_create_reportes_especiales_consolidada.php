<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_especiales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->foreignId('accion_id')->nullable()->constrained('acciones')->nullOnDelete();
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->foreignId('sector_id')->nullable()->constrained('sectores')->onDelete('set null');
            $table->foreignId('tipo_incidente_id')->nullable()->constrained('tipos_incidente')->nullOnDelete();
            $table->enum('tipo', ['incidentes', 'denuncia', 'detenido', 'accion_sospechosa']);
            $table->date('dia');
            $table->time('hora');
            $table->text('novedad')->nullable();
            $table->text('accion')->nullable();
            $table->text('resultado')->nullable();
            $table->json('imagenes')->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->decimal('precision', 8, 2)->nullable();
            $table->enum('estado', ['pendiente', 'en_revision', 'completado', 'rechazado'])->default('pendiente');
            $table->text('comentarios_admin')->nullable();
            $table->unsignedBigInteger('leido_por_id')->nullable();
            $table->timestamp('fecha_lectura')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('reportes_especiales', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('leido_por_id')->references('id_usuario')->on('usuarios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_especiales');
    }
};
