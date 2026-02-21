<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Empresas usan modalidad de jerarquía (instalaciones heredan desde empresa).
     */
    public function up(): void
    {
        if (!Schema::hasTable('empresas')) {
            return;
        }
        if (Schema::hasColumn('empresas', 'modalidad_id')) {
            return;
        }
        Schema::table('empresas', function (Blueprint $table) {
            $table->foreignId('modalidad_id')->nullable()->after('id')->constrained('modalidades_jerarquia')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('empresas') || !Schema::hasColumn('empresas', 'modalidad_id')) {
            return;
        }
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['modalidad_id']);
        });
    }
};
