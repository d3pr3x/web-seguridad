<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar la nueva columna perfil como integer
        Schema::table('users', function (Blueprint $table) {
            $table->integer('perfil')->default(4)->after('rut')->comment('1=Admin, 2=Supervisor, 3=Supervisor-Usuario, 4=Usuario');
        });
        
        // Migrar los datos de rol a perfil
        DB::table('users')->where('rol', 'administrador')->update(['perfil' => 1]);
        DB::table('users')->where('rol', 'supervisor')->update(['perfil' => 2]);
        DB::table('users')->where('rol', 'supervisor-usuario')->update(['perfil' => 3]);
        DB::table('users')->where('rol', 'usuario')->update(['perfil' => 4]);
        
        // Eliminar la columna rol antigua
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('perfil');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['usuario', 'supervisor', 'supervisor-usuario', 'administrador'])
                  ->default('usuario')
                  ->after('rut');
        });
    }
};
