<?php

namespace Database\Seeders;

use App\Models\Sector;
use App\Models\Sucursal;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sucursales = Sucursal::all();

        foreach ($sucursales as $sucursal) {
            // Crear sectores genéricos para cada sucursal
            Sector::create([
                'sucursal_id' => $sucursal->id,
                'nombre' => 'Sector A - Acceso Principal',
                'descripcion' => 'Área de acceso principal y recepción',
                'activo' => true,
            ]);

            Sector::create([
                'sucursal_id' => $sucursal->id,
                'nombre' => 'Sector B - Estacionamiento',
                'descripcion' => 'Área de estacionamiento vehicular',
                'activo' => true,
            ]);

            Sector::create([
                'sucursal_id' => $sucursal->id,
                'nombre' => 'Sector C - Perímetro Exterior',
                'descripcion' => 'Perímetro exterior del edificio',
                'activo' => true,
            ]);

            Sector::create([
                'sucursal_id' => $sucursal->id,
                'nombre' => 'Sector D - Piso 1',
                'descripcion' => 'Primer piso del edificio',
                'activo' => true,
            ]);

            Sector::create([
                'sucursal_id' => $sucursal->id,
                'nombre' => 'Sector E - Piso 2',
                'descripcion' => 'Segundo piso del edificio',
                'activo' => false, // Un sector inactivo como ejemplo
            ]);
        }
    }
}




