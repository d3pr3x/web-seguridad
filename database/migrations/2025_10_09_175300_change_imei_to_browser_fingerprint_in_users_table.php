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
            // Renombrar columnas de IMEI a browser_fingerprint
            $table->renameColumn('imei', 'browser_fingerprint');
            $table->renameColumn('imei_verificado', 'dispositivo_verificado');
        });
        
        // Cambiar el tipo de dato para soportar fingerprints más largos
        Schema::table('users', function (Blueprint $table) {
            $table->string('browser_fingerprint', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('browser_fingerprint', 'imei');
            $table->renameColumn('dispositivo_verificado', 'imei_verificado');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('imei', 15)->nullable()->change();
        });
    }
};
