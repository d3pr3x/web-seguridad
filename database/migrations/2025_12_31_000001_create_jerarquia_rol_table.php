<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot: jerarquía ↔ roles con orden.
     */
    public function up(): void
    {
        Schema::create('jerarquia_rol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jerarquia_id')->constrained('jerarquias')->cascadeOnDelete();
            $table->unsignedBigInteger('rol_id');
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();

            $table->unique(['jerarquia_id', 'rol_id']);
        });

        if (Schema::hasTable('roles_usuario')) {
            Schema::table('jerarquia_rol', function (Blueprint $table) {
                $table->foreign('rol_id')->references('id')->on('roles_usuario')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jerarquia_rol');
    }
};
