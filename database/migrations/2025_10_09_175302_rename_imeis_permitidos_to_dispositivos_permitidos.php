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
        // Renombrar la tabla
        Schema::rename('imeis_permitidos', 'dispositivos_permitidos');
        
        // Modificar la estructura de la tabla
        Schema::table('dispositivos_permitidos', function (Blueprint $table) {
            $table->renameColumn('imei', 'browser_fingerprint');
        });
        
        // Cambiar el tipo de dato
        Schema::table('dispositivos_permitidos', function (Blueprint $table) {
            $table->string('browser_fingerprint', 255)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositivos_permitidos', function (Blueprint $table) {
            $table->renameColumn('browser_fingerprint', 'imei');
        });
        
        Schema::table('dispositivos_permitidos', function (Blueprint $table) {
            $table->string('imei', 15)->unique()->change();
        });
        
        Schema::rename('dispositivos_permitidos', 'imeis_permitidos');
    }
};
