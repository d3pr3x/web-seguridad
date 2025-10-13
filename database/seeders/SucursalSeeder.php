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
        Sucursal::updateOrCreate(
            ['codigo' => 'CENTRAL'],
            [
                'nombre' => 'Sucursal Central',
                'empresa' => 'Empresa de Seguridad S.A.',
                'direccion' => 'Av. Principal 123',
                'comuna' => 'Santiago Centro',
                'ciudad' => 'Santiago',
                'region' => 'Metropolitana',
                'telefono' => '+56 2 2345 6789',
                'email' => 'central@empresa.com',
                'activa' => true,
            ]
        );

        Sucursal::updateOrCreate(
            ['codigo' => 'NORTE'],
            [
                'nombre' => 'Sucursal Norte',
                'empresa' => 'Empresa de Seguridad S.A.',
                'direccion' => 'Calle Secundaria 456',
                'comuna' => 'Valparaíso',
                'ciudad' => 'Valparaíso',
                'region' => 'Valparaíso',
                'telefono' => '+56 32 1234 5678',
                'email' => 'norte@empresa.com',
                'activa' => true,
            ]
        );

        Sucursal::updateOrCreate(
            ['codigo' => 'SUR'],
            [
                'nombre' => 'Sucursal Sur',
                'empresa' => 'Empresa de Seguridad S.A.',
                'direccion' => 'Plaza Mayor 789',
                'comuna' => 'Concepción',
                'ciudad' => 'Concepción',
                'region' => 'Biobío',
                'telefono' => '+56 41 2345 6789',
                'email' => 'sur@empresa.com',
                'activa' => true,
            ]
        );

        Sucursal::updateOrCreate(
            ['codigo' => 'ORIENTE'],
            [
                'nombre' => 'Sucursal Oriente',
                'empresa' => 'Empresa de Seguridad S.A.',
                'direccion' => 'Av. Las Condes 321',
                'comuna' => 'Las Condes',
                'ciudad' => 'Las Condes',
                'region' => 'Metropolitana',
                'telefono' => '+56 2 3456 7890',
                'email' => 'oriente@empresa.com',
                'activa' => true,
            ]
        );
    }
}
