<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: roles_usuario (español). ADMIN, SUPERVISOR, USUARIO, etc.
     */
    public function up(): void
    {
        Schema::create('roles_usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80);
            $table->string('slug', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles_usuario');
    }
};
