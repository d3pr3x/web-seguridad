<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla empresas (clientes): jerarquía superior. Las instalaciones (sucursales) pertenecen a una empresa.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jerarquia_id')->nullable()->constrained('jerarquias')->nullOnDelete();
            $table->string('nombre');
            $table->string('codigo', 50)->unique()->nullable();
            $table->string('razon_social', 200)->nullable();
            $table->string('rut', 20)->nullable();
            $table->text('direccion')->nullable();
            $table->string('comuna', 100)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
