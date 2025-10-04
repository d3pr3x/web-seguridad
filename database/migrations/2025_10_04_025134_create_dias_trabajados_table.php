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
        Schema::create('dias_trabajados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('ponderacion', 3, 2)->default(1.00); // 1.00 = día normal, 1.50 = día y medio, etc.
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'fecha']); // Un usuario no puede tener dos registros el mismo día
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_trabajados');
    }
};
