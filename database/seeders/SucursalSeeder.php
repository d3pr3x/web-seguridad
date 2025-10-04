<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sucursal;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sucursal::create([
            'nombre' => 'Sucursal Central',
            'codigo' => 'CENTRAL',
            'direccion' => 'Av. Principal 123, Santiago',
            'ciudad' => 'Santiago',
            'region' => 'Metropolitana',
            'telefono' => '+56 2 2345 6789',
            'email' => 'central@empresa.com',
            'activa' => true,
        ]);

        Sucursal::create([
            'nombre' => 'Sucursal Norte',
            'codigo' => 'NORTE',
            'direccion' => 'Calle Secundaria 456, Valparaíso',
            'ciudad' => 'Valparaíso',
            'region' => 'Valparaíso',
            'telefono' => '+56 32 1234 5678',
            'email' => 'norte@empresa.com',
            'activa' => true,
        ]);

        Sucursal::create([
            'nombre' => 'Sucursal Sur',
            'codigo' => 'SUR',
            'direccion' => 'Plaza Mayor 789, Concepción',
            'ciudad' => 'Concepción',
            'region' => 'Biobío',
            'telefono' => '+56 41 2345 6789',
            'email' => 'sur@empresa.com',
            'activa' => true,
        ]);

        Sucursal::create([
            'nombre' => 'Sucursal Oriente',
            'codigo' => 'ORIENTE',
            'direccion' => 'Av. Las Condes 321, Las Condes',
            'ciudad' => 'Las Condes',
            'region' => 'Metropolitana',
            'telefono' => '+56 2 3456 7890',
            'email' => 'oriente@empresa.com',
            'activa' => true,
        ]);
    }
}
