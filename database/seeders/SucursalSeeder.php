<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Database\Seeder;

/**
 * Instalaciones (sucursales) demo: 2 por empresa. Idempotente por codigo.
 * Debe ejecutarse después de EmpresaSeeder.
 */
class SucursalSeeder extends Seeder
{
    public function run(): void
    {
        $emp1 = Empresa::where('codigo', 'EMP1')->first();
        $emp2 = Empresa::where('codigo', 'EMP2')->first();

        if (!$emp1 || !$emp2) {
            $this->command->warn('Ejecuta primero EmpresaSeeder.');
            return;
        }

        // Empresa 1: Instalación A, Instalación B
        Sucursal::updateOrCreate(
            ['codigo' => 'INST-A'],
            [
                'empresa_id' => $emp1->id,
                'nombre' => 'Instalación A',
                'empresa' => $emp1->nombre,
                'direccion' => 'Av. Principal 123',
                'comuna' => 'Santiago Centro',
                'ciudad' => 'Santiago',
                'region' => 'Metropolitana',
                'telefono' => '+56 2 2345 6789',
                'email' => 'inst-a@empresa1.demo',
                'activa' => true,
            ]
        );
        Sucursal::updateOrCreate(
            ['codigo' => 'INST-B'],
            [
                'empresa_id' => $emp1->id,
                'nombre' => 'Instalación B',
                'empresa' => $emp1->nombre,
                'direccion' => 'Calle Secundaria 456',
                'comuna' => 'Providencia',
                'ciudad' => 'Santiago',
                'region' => 'Metropolitana',
                'telefono' => '+56 2 2345 6790',
                'email' => 'inst-b@empresa1.demo',
                'activa' => true,
            ]
        );

        // Empresa 2: Instalación C, Instalación D
        Sucursal::updateOrCreate(
            ['codigo' => 'INST-C'],
            [
                'empresa_id' => $emp2->id,
                'nombre' => 'Instalación C',
                'empresa' => $emp2->nombre,
                'direccion' => 'Plaza Mayor 789',
                'comuna' => 'Concepción',
                'ciudad' => 'Concepción',
                'region' => 'Biobío',
                'telefono' => '+56 41 2345 6789',
                'email' => 'inst-c@empresa2.demo',
                'activa' => true,
            ]
        );
        Sucursal::updateOrCreate(
            ['codigo' => 'INST-D'],
            [
                'empresa_id' => $emp2->id,
                'nombre' => 'Instalación D',
                'empresa' => $emp2->nombre,
                'direccion' => 'Av. Sur 100',
                'comuna' => 'Talcahuano',
                'ciudad' => 'Talcahuano',
                'region' => 'Biobío',
                'telefono' => '+56 41 234 5690',
                'email' => 'inst-d@empresa2.demo',
                'activa' => true,
            ]
        );
    }
}

