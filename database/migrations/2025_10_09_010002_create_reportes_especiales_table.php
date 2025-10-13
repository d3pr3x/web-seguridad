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
        Schema::create('reportes_especiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->foreignId('sector_id')->nullable()->constrained('sectores')->onDelete('set null');
            $table->enum('tipo', [
                'incidentes',
                'denuncia',
                'detenido',
                'accion_sospechosa'
            ]);
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_especiales');
    }
};




