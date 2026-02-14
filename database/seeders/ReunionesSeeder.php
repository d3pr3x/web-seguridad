<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReunionesSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::first();
        if (!$usuario) {
            return;
        }

        DB::table('reuniones')->insertOrIgnore([
            [
                'titulo' => 'Reunión de coordinación semanal',
                'descripcion' => 'Revisión de novedades y coordinación de turnos',
                'fecha_reunion' => now()->addDays(2),
                'ubicacion' => 'Sala de reuniones Central',
                'id_usuario_creador' => $usuario->id_usuario,
                'estado' => 'programada',
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'titulo' => 'Briefing de seguridad',
                'descripcion' => 'Briefing de seguridad con autoridades',
                'fecha_reunion' => now()->addDays(5),
                'ubicacion' => 'Comisaría',
                'id_usuario_creador' => $usuario->id_usuario,
                'estado' => 'programada',
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
        ]);
    }
}
