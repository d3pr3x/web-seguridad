<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tipos de incidente por grupo.
     */
    public function up(): void
    {
        Schema::create('tipos_incidente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos_incidentes')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->string('slug', 80);
            $table->unsignedTinyInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['grupo_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_incidente');
    }
};
