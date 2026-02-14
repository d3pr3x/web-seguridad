<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('rut', 12)->index();
            $table->string('patente', 10)->nullable()->index();
            $table->text('motivo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('creado_por')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('blacklists', function (Blueprint $table) {
            $table->foreign('creado_por')->references('id_usuario')->on('usuarios')->nullOnDelete();
            $table->index(['activo', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklists');
    }
};
