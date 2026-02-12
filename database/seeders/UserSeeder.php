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

        // Usuario administrador - Perfil 1
        User::updateOrCreate(
            ['rut' => '12345678-9'],
            [
                'name' => 'Roberto',
                'email' => 'roberto.silva@empresa.com',
                'perfil' => 1,
                'apellido' => 'Silva',
                'fecha_nacimiento' => '1985-05-15',
                'domicilio' => 'Av. Principal 123, Santiago',
                'sucursal_id' => $sucursalCentral->id,
                'password' => Hash::make('123456'),
            ]
        );

        // Usuario supervisor - Perfil 2
        User::updateOrCreate(
            ['rut' => '98765432-1'],
            [
                'name' => 'María',
                'email' => 'maria.gonzalez@empresa.com',
                'perfil' => 2,
                'apellido' => 'González',
                'fecha_nacimiento' => '1990-08-22',
                'domicilio' => 'Calle Secundaria 456, Valparaíso',
                'sucursal_id' => $sucursalNorte->id,
                'password' => Hash::make('123456'),
            ]
        );

        // Usuario supervisor-usuario - Perfil 3
        User::updateOrCreate(
            ['rut' => '11223344-5'],
            [
                'name' => 'Carlos',
                'email' => 'carlos.rodriguez@empresa.com',
                'perfil' => 3,
                'apellido' => 'Rodríguez',
                'fecha_nacimiento' => '1988-12-10',
                'domicilio' => 'Plaza Mayor 789, Concepción',
                'sucursal_id' => $sucursalSur->id,
                'password' => Hash::make('123456'),
            ]
        );

        // Usuario regular - Perfil 4
        User::updateOrCreate(
            ['rut' => '22334455-6'],
            [
                'name' => 'Ana',
                'email' => 'ana.martinez@empresa.com',
                'perfil' => 4,
                'apellido' => 'Martínez',
                'fecha_nacimiento' => '1992-03-18',
                'domicilio' => 'Calle Los Aromos 321, Santiago',
                'sucursal_id' => $sucursalCentral->id,
                'password' => Hash::make('123456'),
            ]
        );

        // Usuario sin sucursal - Perfil 4 (para probar la validación)
        User::updateOrCreate(
            ['rut' => '33445566-7'],
            [
                'name' => 'Pedro',
                'email' => 'pedro.lopez@empresa.com',
                'perfil' => 4,
                'apellido' => 'López',
                'fecha_nacimiento' => '1987-11-25',
                'domicilio' => 'Av. Los Pinos 654, Viña del Mar',
                'sucursal_id' => null,
                'password' => Hash::make('123456'),
            ]
        );

        // Guardia control de acceso - Perfil 5 (QR cédula + OCR patente)
        User::updateOrCreate(
            ['rut' => '55667788-9'],
            [
                'name' => 'Luis',
                'email' => 'luis.guardia@empresa.com',
                'perfil' => 5,
                'apellido' => 'Guardia',
                'fecha_nacimiento' => '1990-01-10',
                'domicilio' => 'Av. Control 100, Santiago',
                'sucursal_id' => $sucursalCentral->id,
                'password' => Hash::make('123456'),
            ]
        );
    }
}
