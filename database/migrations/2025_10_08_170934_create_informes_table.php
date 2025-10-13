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
        Schema::create('informes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained()->onDelete('cascade');
            $table->integer('numero_informe')->unique();
            $table->time('hora');
            $table->text('descripcion');
            $table->text('lesionados');
            $table->json('acciones_inmediatas');
            $table->json('conclusiones');
            $table->json('fotografias')->nullable();
            $table->string('estado')->default('generado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informes');
    }
};
