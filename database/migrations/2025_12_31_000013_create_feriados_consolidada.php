<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feriados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha');
            $table->boolean('irrenunciable')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->unique('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feriados');
    }
};
