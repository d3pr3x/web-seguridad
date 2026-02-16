<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Puntos 1, 7, 15: tipo_hecho (filtrar novedades por hecho) e importancia (importante/cotidiana).
     */
    public function up(): void
    {
        Schema::table('acciones', function (Blueprint $table) {
            $table->string('tipo_hecho', 50)->nullable()->after('tipo')
                ->comment('incidente, observacion, informacion, delito, accidente');
            $table->string('importancia', 20)->nullable()->after('tipo_hecho')
                ->comment('importante, cotidiana, critica');
        });
    }

    public function down(): void
    {
        Schema::table('acciones', function (Blueprint $table) {
            $table->dropColumn(['tipo_hecho', 'importancia']);
        });
    }
};
