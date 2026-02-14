<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20)->comment('peatonal|vehicular');
            $table->string('rut', 12)->index();
            $table->string('nombre', 100);
            $table->string('patente', 10)->nullable()->index();
            $table->unsignedBigInteger('id_guardia');
            $table->timestamp('fecha_ingreso')->useCurrent();
            $table->timestamp('fecha_salida')->nullable();
            $table->string('estado', 20)->default('ingresado');
            $table->boolean('alerta_blacklist')->default(false);
            $table->string('ip_ingreso', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
        Schema::table('ingresos', function (Blueprint $table) {
            $table->foreign('id_guardia')->references('id_usuario')->on('usuarios')->cascadeOnDelete();
            $table->index('fecha_ingreso');
            $table->index(['estado', 'fecha_ingreso']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
