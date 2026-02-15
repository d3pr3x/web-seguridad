<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * La app espera columna id_usuario en reportes; la tabla tiene user_id.
     * Añadimos id_usuario y copiamos user_id para compatibilidad.
     */
    public function up(): void
    {
        if (Schema::hasColumn('reportes', 'id_usuario')) {
            return;
        }

        Schema::table('reportes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario')->nullable()->after('id');
        });

        if (Schema::hasColumn('reportes', 'user_id')) {
            DB::table('reportes')->update(['id_usuario' => DB::raw('user_id')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reportes', 'id_usuario')) {
            Schema::table('reportes', function (Blueprint $table) {
                $table->dropColumn('id_usuario');
            });
        }
    }
};
