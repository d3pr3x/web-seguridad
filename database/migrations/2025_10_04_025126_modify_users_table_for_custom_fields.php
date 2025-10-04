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
        Schema::table('users', function (Blueprint $table) {
            // Agregar campos personalizados
            $table->string('rut')->unique()->after('id');
            $table->string('apellido')->after('name');
            $table->date('fecha_nacimiento')->after('apellido');
            $table->text('domicilio')->after('fecha_nacimiento');
            $table->string('sucursal')->after('domicilio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar campos personalizados
            $table->dropColumn(['rut', 'apellido', 'fecha_nacimiento', 'domicilio', 'sucursal']);
        });
    }
};
