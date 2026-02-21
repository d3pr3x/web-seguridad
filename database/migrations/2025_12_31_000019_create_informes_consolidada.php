<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('reportes')->onDelete('cascade');
            $table->integer('numero_informe')->unique();
            $table->time('hora');
            $table->text('descripcion');
            $table->text('lesionados');
            $table->json('acciones_inmediatas');
            $table->json('conclusiones');
            $table->json('fotografias')->nullable();
            $table->string('estado')->default('pendiente_revision');
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->string('aprobado_por')->nullable();
            $table->text('comentarios_aprobacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informes');
    }
};
