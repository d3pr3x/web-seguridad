<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ubicación del punto QR para validar que el escaneo sea in situ (máx. 10 m).
     */
    public function up(): void
    {
        Schema::table('puntos_ronda', function (Blueprint $table) {
            $table->decimal('lat', 10, 8)->nullable()->after('orden')->comment('Latitud del punto físico del QR');
            $table->decimal('lng', 11, 8)->nullable()->after('lat')->comment('Longitud del punto físico del QR');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('puntos_ronda', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng']);
        });
    }
};
