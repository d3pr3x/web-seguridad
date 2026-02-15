<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Base de personas: vincula RUT con nombre y datos para completar desde el escáner.
     */
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('rut', 12)->unique()->comment('RUT normalizado 12.345.678-9');
            $table->string('nombre', 100);
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('empresa', 100)->nullable();
            $table->text('notas')->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable()->comment('Opcional: persona asociada a sucursal');
            $table->timestamps();
        });

        Schema::table('personas', function (Blueprint $table) {
            $table->foreign('sucursal_id')->references('id')->on('sucursales')->nullOnDelete();
            $table->index('rut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
