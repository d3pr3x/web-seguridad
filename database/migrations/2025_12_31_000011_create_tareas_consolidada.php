<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('categoria')->default('novedades_servicio');
            $table->text('descripcion')->nullable();
            $table->string('icono')->nullable();
            $table->string('color')->default('#007bff');
            $table->boolean('activa')->default(true);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->softDeletes();
        });
        Schema::table('tareas', function (Blueprint $table) {
            $table->index('categoria');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
