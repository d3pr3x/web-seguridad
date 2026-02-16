<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Punto 14: teléfono en usuarios. Punto 18: pasaporte en personas e ingresos.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (!Schema::hasColumn('usuarios', 'telefono')) {
                $table->string('telefono', 30)->nullable()->after('email');
            }
        });

        Schema::table('personas', function (Blueprint $table) {
            if (!Schema::hasColumn('personas', 'pasaporte')) {
                $table->string('pasaporte', 30)->nullable()->after('rut');
            }
        });
        Schema::table('personas', function (Blueprint $table) {
            if (Schema::hasColumn('personas', 'pasaporte')) {
                $table->index('pasaporte');
            }
        });

        Schema::table('ingresos', function (Blueprint $table) {
            if (!Schema::hasColumn('ingresos', 'pasaporte')) {
                $table->string('pasaporte', 30)->nullable()->after('rut');
            }
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('telefono');
        });
        Schema::table('personas', function (Blueprint $table) {
            $table->dropIndex(['pasaporte']);
            $table->dropColumn('pasaporte');
        });
        Schema::table('ingresos', function (Blueprint $table) {
            $table->dropColumn('pasaporte');
        });
    }
};
