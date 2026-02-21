<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot: rol_permiso (roles_usuario ↔ permisos).
     */
    public function up(): void
    {
        Schema::create('rol_permiso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_id')->constrained('roles_usuario')->onDelete('cascade');
            $table->foreignId('permiso_id')->constrained('permisos')->onDelete('cascade');
            $table->timestamp('creado_en')->nullable();
            $table->unique(['rol_id', 'permiso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rol_permiso');
    }
};
