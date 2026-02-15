<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Crea la tabla usuarios si no existe (requerida por la aplicación).
     * rol_id y sucursal_id sin FK para no depender de roles_usuario/sucursales.
     */
    public function up(): void
    {
        if (Schema::hasTable('usuarios')) {
            return;
        }

        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('run', 20)->unique();
            $table->string('nombre_completo', 200);
            $table->string('rango', 80)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verificado_en')->nullable();
            $table->string('clave');
            $table->date('fecha_nacimiento')->nullable();
            $table->text('domicilio')->nullable();
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->string('browser_fingerprint', 255)->nullable();
            $table->boolean('dispositivo_verificado')->default(false);
            $table->rememberToken();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

        // Usuario de demostración para login y seeders
        \DB::table('usuarios')->insert([
            'run' => '11111111-1',
            'nombre_completo' => 'Usuario Demo',
            'rango' => 'Supervisor',
            'email' => 'demo@emacof.cl',
            'clave' => Hash::make('123456'),
            'rol_id' => null,
            'sucursal_id' => null,
            'dispositivo_verificado' => false,
            'creado_en' => now(),
            'actualizado_en' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
