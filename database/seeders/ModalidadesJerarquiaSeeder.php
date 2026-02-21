<?php

namespace Database\Seeders;

use App\Models\ModalidadJerarquia;
use Illuminate\Database\Seeder;

class ModalidadesJerarquiaSeeder extends Seeder
{
    public function run(): void
    {
        $modalidades = [
            [
                'nombre' => 'directa',
                'descripcion' => 'Supervisor → Usuario (sin jefe de turno intermedio)',
                'activo' => true,
            ],
            [
                'nombre' => 'con_jefe_turno',
                'descripcion' => 'Supervisor → Jefe de turno → Usuario',
                'activo' => true,
            ],
            [
                'nombre' => 'custom',
                'descripcion' => 'Jerarquía personalizada por cliente',
                'activo' => true,
            ],
        ];

        foreach ($modalidades as $m) {
            ModalidadJerarquia::firstOrCreate(
                ['nombre' => $m['nombre']],
                $m
            );
        }
    }
}
