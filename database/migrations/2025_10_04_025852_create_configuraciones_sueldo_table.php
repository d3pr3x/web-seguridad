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
        Schema::create('configuraciones_sueldo', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_dia'); // 'habil', 'inhabil', 'feriado', 'domingo'
            $table->decimal('multiplicador', 3, 2); // 1.00 = normal, 1.50 = día y medio, 2.00 = doble
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones_sueldo');
    }
};
