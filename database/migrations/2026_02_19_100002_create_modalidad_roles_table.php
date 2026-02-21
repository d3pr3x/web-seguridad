<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot: orden de roles por modalidad (precedencia menú/flujo).
     */
    public function up(): void
    {
        Schema::create('modalidad_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modalidad_id')->constrained('modalidades_jerarquia')->cascadeOnDelete();
            $table->foreignId('rol_id')->constrained('roles_usuario')->cascadeOnDelete();
            $table->unsignedInteger('orden')->default(0); // menor = más importante
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['modalidad_id', 'rol_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modalidad_roles');
    }
};
