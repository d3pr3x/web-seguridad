<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Punto 5: indicador de lectura por jefe (quién y cuándo).
     */
    public function up(): void
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->unsignedBigInteger('leido_por_id')->nullable()->after('comentarios_admin');
            $table->timestamp('fecha_lectura')->nullable()->after('leido_por_id');
        });
        Schema::table('reportes', function (Blueprint $table) {
            $table->foreign('leido_por_id')->references('id_usuario')->on('usuarios')->nullOnDelete();
        });

        Schema::table('reportes_especiales', function (Blueprint $table) {
            $table->unsignedBigInteger('leido_por_id')->nullable()->after('comentarios_admin');
            $table->timestamp('fecha_lectura')->nullable()->after('leido_por_id');
        });
        Schema::table('reportes_especiales', function (Blueprint $table) {
            $table->foreign('leido_por_id')->references('id_usuario')->on('usuarios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->dropForeign(['leido_por_id']);
            $table->dropColumn(['leido_por_id', 'fecha_lectura']);
        });
        Schema::table('reportes_especiales', function (Blueprint $table) {
            $table->dropForeign(['leido_por_id']);
            $table->dropColumn(['leido_por_id', 'fecha_lectura']);
        });
    }
};
