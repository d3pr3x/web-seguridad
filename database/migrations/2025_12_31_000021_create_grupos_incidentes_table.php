<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Grupos de delitos/incidentes (catálogo).
     */
    public function up(): void
    {
        Schema::create('grupos_incidentes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('slug', 80)->unique();
            $table->text('descripcion')->nullable();
            $table->unsignedTinyInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos_incidentes');
    }
};
