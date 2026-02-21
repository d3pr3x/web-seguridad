<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: usuarios (español). PK: id_usuario. Identificador: run (ej. 987403M), rango opcional.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('run', 20)->unique()->comment('Ej: 987403M');
            $table->string('nombre_completo', 200);
            $table->string('rango', 80)->nullable()->comment('Cargo o grado');
            $table->string('email')->nullable()->unique();
            $table->string('telefono', 30)->nullable();
            $table->timestamp('email_verificado_en')->nullable();
            $table->string('clave');
            $table->date('fecha_nacimiento')->nullable();
            $table->text('domicilio')->nullable();
            $table->foreignId('rol_id')->nullable()->constrained('roles_usuario')->onDelete('set null');
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->onDelete('set null');
            $table->string('browser_fingerprint', 255)->nullable();
            $table->boolean('dispositivo_verificado')->default(false);
            $table->rememberToken();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
