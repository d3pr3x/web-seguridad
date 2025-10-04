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
        Schema::create('tarea_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained()->onDelete('cascade');
            $table->string('campo_nombre'); // Nombre del campo (ej: "Ubicación", "Descripción")
            $table->string('tipo_campo'); // text, textarea, select, date, etc.
            $table->text('opciones')->nullable(); // Para campos select, JSON con opciones
            $table->boolean('requerido')->default(false);
            $table->integer('orden')->default(0); // Orden de aparición en el formulario
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarea_detalles');
    }
};
