<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Punto 2: grupos de delitos/incidentes y tipos asociados.
     */
    public function up(): void
    {
        Schema::create('grupos_incidentes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('slug', 80)->unique();
            $table->text('descripcion')->nullable();
            $table->unsignedTinyInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('tipos_incidente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos_incidentes')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->string('slug', 80);
            $table->unsignedTinyInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        Schema::table('tipos_incidente', function (Blueprint $table) {
            $table->unique(['grupo_id', 'slug']);
        });

        Schema::table('reportes_especiales', function (Blueprint $table) {
            if (!Schema::hasColumn('reportes_especiales', 'tipo_incidente_id')) {
                $table->unsignedBigInteger('tipo_incidente_id')->nullable()->after('tipo');
            }
        });
        Schema::table('reportes_especiales', function (Blueprint $table) {
            if (Schema::hasColumn('reportes_especiales', 'tipo_incidente_id')) {
                $table->foreign('tipo_incidente_id')->references('id')->on('tipos_incidente')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('reportes_especiales', 'tipo_incidente_id')) {
            Schema::table('reportes_especiales', function (Blueprint $table) {
                $table->dropForeign(['tipo_incidente_id']);
                $table->dropColumn('tipo_incidente_id');
            });
        }
        Schema::dropIfExists('tipos_incidente');
        Schema::dropIfExists('grupos_incidentes');
    }
};
