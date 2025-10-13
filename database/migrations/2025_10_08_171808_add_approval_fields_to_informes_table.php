<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('informes', function (Blueprint $table) {
            $table->string('estado')->default('pendiente_revision')->change();
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->string('aprobado_por')->nullable();
            $table->text('comentarios_aprobacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informes', function (Blueprint $table) {
            $table->string('estado')->default('generado')->change();
            $table->dropColumn(['fecha_aprobacion', 'aprobado_por', 'comentarios_aprobacion']);
        });
    }
};
