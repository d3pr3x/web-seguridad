<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener sucursales
        $sucursalCentral = \App\Models\Sucursal::where('codigo', 'CENTRAL')->first();
        $sucursalNorte = \App\Models\Sucursal::where('codigo', 'NORTE')->first();
        $sucursalSur = \App\Models\Sucursal::where('codigo', 'SUR')->first();

        // Usuario administrador
        User::create([
            'name' => 'Juan',
            'email' => 'juan.perez@empresa.com',
            'rut' => '12345678-9',
            'apellido' => 'Pérez',
            'fecha_nacimiento' => '1985-05-15',
            'domicilio' => 'Av. Principal 123, Santiago',
            'sucursal_id' => $sucursalCentral->id,
            'password' => Hash::make('123456'),
        ]);

        // Usuario de prueba
        User::create([
            'name' => 'María',
            'email' => 'maria.gonzalez@empresa.com',
            'rut' => '98765432-1',
            'apellido' => 'González',
            'fecha_nacimiento' => '1990-08-22',
            'domicilio' => 'Calle Secundaria 456, Valparaíso',
            'sucursal_id' => $sucursalNorte->id,
            'password' => Hash::make('123456'),
        ]);

        // Usuario adicional
        User::create([
            'name' => 'Carlos',
            'email' => 'carlos.rodriguez@empresa.com',
            'rut' => '11223344-5',
            'apellido' => 'Rodríguez',
            'fecha_nacimiento' => '1988-12-10',
            'domicilio' => 'Plaza Mayor 789, Concepción',
            'sucursal_id' => $sucursalSur->id,
            'password' => Hash::make('123456'),
        ]);
    }
}
