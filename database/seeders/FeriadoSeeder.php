<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feriado;

class FeriadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Feriados 2024
        $feriados2024 = [
            ['nombre' => 'Año Nuevo', 'fecha' => '2024-01-01', 'irrenunciable' => true],
            ['nombre' => 'Viernes Santo', 'fecha' => '2024-03-29', 'irrenunciable' => true],
            ['nombre' => 'Sábado Santo', 'fecha' => '2024-03-30', 'irrenunciable' => false],
            ['nombre' => 'Día del Trabajador', 'fecha' => '2024-05-01', 'irrenunciable' => true],
            ['nombre' => 'Día de las Glorias Navales', 'fecha' => '2024-05-21', 'irrenunciable' => true],
            ['nombre' => 'San Pedro y San Pablo', 'fecha' => '2024-06-29', 'irrenunciable' => false],
            ['nombre' => 'Día de la Virgen del Carmen', 'fecha' => '2024-07-16', 'irrenunciable' => true],
            ['nombre' => 'Asunción de la Virgen', 'fecha' => '2024-08-15', 'irrenunciable' => true],
            ['nombre' => 'Fiestas Patrias', 'fecha' => '2024-09-18', 'irrenunciable' => true],
            ['nombre' => 'Glorias del Ejército', 'fecha' => '2024-09-19', 'irrenunciable' => true],
            ['nombre' => 'Encuentro de Dos Mundos', 'fecha' => '2024-10-12', 'irrenunciable' => false],
            ['nombre' => 'Día de Todos los Santos', 'fecha' => '2024-11-01', 'irrenunciable' => true],
            ['nombre' => 'Inmaculada Concepción', 'fecha' => '2024-12-08', 'irrenunciable' => true],
            ['nombre' => 'Navidad', 'fecha' => '2024-12-25', 'irrenunciable' => true],
        ];

        // Feriados 2025
        $feriados2025 = [
            ['nombre' => 'Año Nuevo', 'fecha' => '2025-01-01', 'irrenunciable' => true],
            ['nombre' => 'Viernes Santo', 'fecha' => '2025-04-18', 'irrenunciable' => true],
            ['nombre' => 'Sábado Santo', 'fecha' => '2025-04-19', 'irrenunciable' => false],
            ['nombre' => 'Día del Trabajador', 'fecha' => '2025-05-01', 'irrenunciable' => true],
            ['nombre' => 'Día de las Glorias Navales', 'fecha' => '2025-05-21', 'irrenunciable' => true],
            ['nombre' => 'San Pedro y San Pablo', 'fecha' => '2025-06-29', 'irrenunciable' => false],
            ['nombre' => 'Día de la Virgen del Carmen', 'fecha' => '2025-07-16', 'irrenunciable' => true],
            ['nombre' => 'Asunción de la Virgen', 'fecha' => '2025-08-15', 'irrenunciable' => true],
            ['nombre' => 'Fiestas Patrias', 'fecha' => '2025-09-18', 'irrenunciable' => true],
            ['nombre' => 'Glorias del Ejército', 'fecha' => '2025-09-19', 'irrenunciable' => true],
            ['nombre' => 'Encuentro de Dos Mundos', 'fecha' => '2025-10-12', 'irrenunciable' => false],
            ['nombre' => 'Día de Todos los Santos', 'fecha' => '2025-11-01', 'irrenunciable' => true],
            ['nombre' => 'Inmaculada Concepción', 'fecha' => '2025-12-08', 'irrenunciable' => true],
            ['nombre' => 'Navidad', 'fecha' => '2025-12-25', 'irrenunciable' => true],
        ];

        // Crear/actualizar feriados (idempotente para poder ejecutar db:seed varias veces)
        foreach (array_merge($feriados2024, $feriados2025) as $feriado) {
            Feriado::updateOrCreate(
                ['fecha' => $feriado['fecha']],
                [
                    'nombre' => $feriado['nombre'],
                    'irrenunciable' => $feriado['irrenunciable'],
                    'activo' => true,
                ]
            );
        }
    }
}
