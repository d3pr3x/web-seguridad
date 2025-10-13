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
        Schema::create('acciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->foreignId('sector_id')->nullable()->constrained('sectores')->onDelete('set null');
            $table->enum('tipo', [
                'inicio_servicio',
                'rondas',
                'constancias',
                'concurrencia_carabineros',
                'concurrencia_servicios',
                'entrega_servicio'
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acciones');
    }
};




