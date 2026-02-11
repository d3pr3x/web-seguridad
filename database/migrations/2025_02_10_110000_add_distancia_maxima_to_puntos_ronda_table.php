<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Distancia máxima en metros para validar el escaneo (configurable por punto).
     */
    public function up(): void
    {
        Schema::table('puntos_ronda', function (Blueprint $table) {
            $table->unsignedSmallInteger('distancia_maxima_metros')->default(10)->after('lng')->comment('Radio máximo en metros para aceptar el escaneo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('puntos_ronda', function (Blueprint $table) {
            $table->dropColumn('distancia_maxima_metros');
        });
    }
};
