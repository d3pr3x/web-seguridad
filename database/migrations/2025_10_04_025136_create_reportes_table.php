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
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tarea_id')->constrained()->onDelete('cascade');
            $table->json('datos'); // Datos específicos de la tarea en formato JSON
            $table->json('imagenes')->nullable(); // Array de rutas de imágenes
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
        Schema::dropIfExists('reportes');
    }
};
