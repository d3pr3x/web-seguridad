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
        Schema::create('ronda_escaneos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('punto_ronda_id')->constrained('puntos_ronda')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('escaneado_en');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->timestamps();
        });

        Schema::table('ronda_escaneos', function (Blueprint $table) {
            $table->index(['punto_ronda_id', 'escaneado_en']);
            $table->index(['user_id', 'escaneado_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ronda_escaneos');
    }
};
