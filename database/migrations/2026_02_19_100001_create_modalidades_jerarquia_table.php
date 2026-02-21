<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Modalidades de jerarquía por empresa (directa, con_jefe_turno, custom).
     * La jerarquía depende de empresa; las instalaciones heredan la modalidad.
     */
    public function up(): void
    {
        Schema::create('modalidades_jerarquia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // ej: directa, con_jefe_turno, custom
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modalidades_jerarquia');
    }
};
