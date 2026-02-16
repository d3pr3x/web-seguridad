<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Punto 6: novedad (acción) elevable a reporte formal; relación origen.
     */
    public function up(): void
    {
        Schema::table('reportes_especiales', function (Blueprint $table) {
            $table->unsignedBigInteger('accion_id')->nullable()->after('id_usuario');
        });
        Schema::table('reportes_especiales', function (Blueprint $table) {
            $table->foreign('accion_id')->references('id')->on('acciones')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reportes_especiales', function (Blueprint $table) {
            $table->dropForeign(['accion_id']);
        });
    }
};
