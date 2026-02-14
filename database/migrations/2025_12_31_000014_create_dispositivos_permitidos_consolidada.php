<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispositivos_permitidos', function (Blueprint $table) {
            $table->id();
            $table->string('browser_fingerprint', 255)->unique();
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('requiere_ubicacion')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispositivos_permitidos');
    }
};
