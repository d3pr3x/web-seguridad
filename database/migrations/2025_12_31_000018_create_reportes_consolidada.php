<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->foreignId('sector_id')->nullable()->constrained('sectores')->nullOnDelete();
            $table->json('datos');
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
        Schema::table('reportes', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('leido_por_id')->references('id_usuario')->on('usuarios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
