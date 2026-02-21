<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_tarea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->string('campo_nombre');
            $table->string('tipo_campo');
            $table->text('opciones')->nullable();
            $table->boolean('requerido')->default(false);
            $table->integer('orden')->default(0);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_tarea');
    }
};
