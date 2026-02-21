<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Trigger en PostgreSQL: bloquea UPDATE y DELETE en auditorias.
 * Solo se permite INSERT (auditoría inmutable).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }
        if (!\Illuminate\Support\Facades\Schema::hasTable('auditorias')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE OR REPLACE FUNCTION prevent_auditorias_update_delete()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'UPDATE' THEN
        RAISE EXCEPTION 'No está permitido modificar registros de auditoría (UPDATE bloqueado).';
    ELSIF TG_OP = 'DELETE' THEN
        RAISE EXCEPTION 'No está permitido eliminar registros de auditoría (DELETE bloqueado).';
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql
SQL
        );
        DB::unprepared('DROP TRIGGER IF EXISTS auditorias_immutable_trigger ON auditorias');
        DB::unprepared('CREATE TRIGGER auditorias_immutable_trigger BEFORE UPDATE OR DELETE ON auditorias FOR EACH ROW EXECUTE FUNCTION prevent_auditorias_update_delete()');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS auditorias_immutable_trigger ON auditorias;');
        DB::unprepared('DROP FUNCTION IF EXISTS prevent_auditorias_update_delete();');
    }
};
