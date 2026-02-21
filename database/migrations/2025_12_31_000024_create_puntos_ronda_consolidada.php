<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puntos_ronda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->foreignId('sector_id')->nullable()->constrained('sectores')->onDelete('set null');
            $table->string('nombre');
            $table->string('codigo', 32)->unique();
            $table->text('descripcion')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->unsignedSmallInteger('distancia_maxima_metros')->default(10);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puntos_ronda');
    }
};
