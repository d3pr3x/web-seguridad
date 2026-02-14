<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->foreignId('sector_id')->nullable()->constrained('sectores')->onDelete('set null');
            $table->enum('tipo', [
                'inicio_servicio', 'rondas', 'constancias',
                'concurrencia_autoridades', 'concurrencia_servicios', 'entrega_servicio'
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
        Schema::table('acciones', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acciones');
    }
};
