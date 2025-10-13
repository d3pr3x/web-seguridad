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
        Schema::create('ubicaciones_permitidas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre descriptivo de la ubicación (ej: "Oficina Central", "Sucursal Norte")
            $table->decimal('latitud', 10, 8); // Latitud con precisión de 8 decimales (~1mm de precisión)
            $table->decimal('longitud', 11, 8); // Longitud con precisión de 8 decimales
            $table->integer('radio')->default(50); // Radio en metros (por defecto 50m)
            $table->boolean('activa')->default(true);
            $table->text('descripcion')->nullable();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubicaciones_permitidas');
    }
};
