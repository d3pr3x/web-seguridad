<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escaneos_ronda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('punto_ronda_id')->constrained('puntos_ronda')->onDelete('cascade');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamp('escaneado_en');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('escaneos_ronda', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->index(['punto_ronda_id', 'escaneado_en']);
            $table->index(['id_usuario', 'escaneado_en']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escaneos_ronda');
    }
};
