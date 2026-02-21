<?php

namespace Database\Seeders;

use App\Models\Sector;
use App\Models\Sucursal;
use Illuminate\Database\Seeder;

/**
 * Sectores demo: 3–5 por instalación. Idempotente por (sucursal_id, nombre).
 */
class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $plantilla = [
            ['nombre' => 'Acceso principal', 'descripcion' => 'Área de acceso principal y recepción', 'activo' => true],
            ['nombre' => 'Bodega', 'descripcion' => 'Bodega y almacén', 'activo' => true],
            ['nombre' => 'Oficinas', 'descripcion' => 'Oficinas administrativas', 'activo' => true],
            ['nombre' => 'Perímetro exterior', 'descripcion' => 'Perímetro exterior del edificio', 'activo' => true],
            ['nombre' => 'Estacionamiento', 'descripcion' => 'Área de estacionamiento vehicular', 'activo' => true],
        ];

        foreach (Sucursal::all() as $sucursal) {
            foreach ($plantilla as $p) {
                Sector::updateOrCreate(
                    [
                        'sucursal_id' => $sucursal->id,
                        'nombre' => $p['nombre'],
                    ],
                    [
                        'empresa_id' => $sucursal->empresa_id,
                        'descripcion' => $p['descripcion'],
                        'activo' => $p['activo'],
                    ]
                );
            }
        }
    }
}





