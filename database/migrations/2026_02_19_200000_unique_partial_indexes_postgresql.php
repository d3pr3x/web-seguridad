<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Índices únicos parciales (PostgreSQL): permiten reutilizar códigos tras soft delete.
 * - Parcial (WHERE deleted_at IS NULL): empresas.codigo, sucursales.codigo, puntos_ronda.codigo.
 * - Histórico (unique normal, no reutilizable): usuarios.run, personas.rut — no se modifican aquí.
 *
 * Solo se ejecuta cuando el driver es pgsql.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        // empresas.codigo: quitar unique, crear parcial
        if (Schema::hasTable('empresas') && Schema::hasColumn('empresas', 'deleted_at')) {
            $this->dropUniqueIfExists('empresas', 'empresas_codigo_unique');
            DB::statement('CREATE UNIQUE INDEX empresas_codigo_unique_where_not_deleted ON empresas (codigo) WHERE deleted_at IS NULL');
        }

        // sucursales.codigo
        if (Schema::hasTable('sucursales') && Schema::hasColumn('sucursales', 'deleted_at')) {
            $this->dropUniqueIfExists('sucursales', 'sucursales_codigo_unique');
            DB::statement('CREATE UNIQUE INDEX sucursales_codigo_unique_where_not_deleted ON sucursales (codigo) WHERE deleted_at IS NULL');
        }

        // puntos_ronda.codigo
        if (Schema::hasTable('puntos_ronda') && Schema::hasColumn('puntos_ronda', 'deleted_at')) {
            $this->dropUniqueIfExists('puntos_ronda', 'puntos_ronda_codigo_unique');
            DB::statement('CREATE UNIQUE INDEX puntos_ronda_codigo_unique_where_not_deleted ON puntos_ronda (codigo) WHERE deleted_at IS NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        if (Schema::hasTable('empresas')) {
            $this->dropIndexIfExists('empresas', 'empresas_codigo_unique_where_not_deleted');
            Schema::table('empresas', function ($table) {
                $table->unique('codigo');
            });
        }
        if (Schema::hasTable('sucursales')) {
            $this->dropIndexIfExists('sucursales', 'sucursales_codigo_unique_where_not_deleted');
            Schema::table('sucursales', function ($table) {
                $table->unique('codigo');
            });
        }
        if (Schema::hasTable('puntos_ronda')) {
            $this->dropIndexIfExists('puntos_ronda', 'puntos_ronda_codigo_unique_where_not_deleted');
            Schema::table('puntos_ronda', function ($table) {
                $table->unique('codigo');
            });
        }
    }

    private function dropUniqueIfExists(string $table, string $hintName): void
    {
        // En PostgreSQL, UNIQUE puede ser constraint o índice; buscar por nombre que contenga la columna
        $constraints = DB::select(
            "SELECT conname FROM pg_constraint WHERE conrelid = ?::regclass AND contype = 'u'",
            [$table]
        );
        foreach ($constraints as $c) {
            if (stripos($c->conname, 'codigo') !== false || $c->conname === $hintName) {
                DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS " . $c->conname);
                return;
            }
        }
        DB::statement("DROP INDEX IF EXISTS {$hintName}");
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        DB::statement("DROP INDEX IF EXISTS {$indexName}");
    }
};
