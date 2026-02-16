<?php

namespace Database\Seeders;

use App\Models\GrupoIncidente;
use App\Models\TipoIncidente;
use Illuminate\Database\Seeder;

/**
 * Punto 2: Datos iniciales para grupos de delitos/incidentes y tipos.
 */
class GruposIncidentesSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            [
                'nombre' => 'Delitos contra la propiedad',
                'slug' => 'delitos-propiedad',
                'orden' => 1,
                'tipos' => [
                    ['nombre' => 'Robo', 'slug' => 'robo'],
                    ['nombre' => 'Hurto', 'slug' => 'hurto'],
                    ['nombre' => 'Daño a la propiedad', 'slug' => 'dano-propiedad'],
                ],
            ],
            [
                'nombre' => 'Delitos contra las personas',
                'slug' => 'delitos-personas',
                'orden' => 2,
                'tipos' => [
                    ['nombre' => 'Agresión', 'slug' => 'agresion'],
                    ['nombre' => 'Amenazas', 'slug' => 'amenazas'],
                    ['nombre' => 'Lesiones', 'slug' => 'lesiones'],
                ],
            ],
            [
                'nombre' => 'Desórdenes y conductas',
                'slug' => 'desordenes',
                'orden' => 3,
                'tipos' => [
                    ['nombre' => 'Alteración del orden', 'slug' => 'alteracion-orden'],
                    ['nombre' => 'Intrusión', 'slug' => 'intrusion'],
                    ['nombre' => 'Conducta sospechosa', 'slug' => 'conducta-sospechosa'],
                ],
            ],
        ];

        foreach ($grupos as $idx => $g) {
            $tipos = $g['tipos'];
            unset($g['tipos']);
            $grupo = GrupoIncidente::firstOrCreate(
                ['slug' => $g['slug']],
                ['nombre' => $g['nombre'], 'orden' => $g['orden'] ?? ($idx + 1), 'activo' => true]
            );
            foreach ($tipos as $i => $t) {
                TipoIncidente::firstOrCreate(
                    ['grupo_id' => $grupo->id, 'slug' => $t['slug']],
                    ['nombre' => $t['nombre'], 'orden' => $i + 1, 'activo' => true]
                );
            }
        }
    }
}
