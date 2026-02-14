<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RolUsuario;
use App\Models\Sucursal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Usuarios de ejemplo. run (ej. 987403M), rango opcional, roles ADMIN, SUPERVISOR, etc.
 */
class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $sucursalCentral = Sucursal::where('codigo', 'CENTRAL')->first();
        $sucursalNorte = Sucursal::where('codigo', 'NORTE')->first();
        $sucursalSur = Sucursal::where('codigo', 'SUR')->first();

        $rolAdmin = RolUsuario::where('slug', 'ADMIN')->first();
        $rolSupervisor = RolUsuario::where('slug', 'SUPERVISOR')->first();
        $rolSupervisorUsuario = RolUsuario::where('slug', 'SUPERVISOR_USUARIO')->first();
        $rolUsuario = RolUsuario::where('slug', 'USUARIO')->first();
        $rolGuardia = RolUsuario::where('slug', 'GUARDIA')->first();

        User::updateOrCreate(
            ['run' => '987403M'],
            [
                'nombre_completo' => 'Roberto Silva',
                'rango' => 'Lieutenant',
                'email' => 'roberto.silva@empresa.com',
                'clave' => Hash::make('123456'),
                'fecha_nacimiento' => '1985-05-15',
                'domicilio' => 'Av. Principal 123, Santiago',
                'rol_id' => $rolAdmin?->id ?? 1,
                'sucursal_id' => $sucursalCentral?->id,
            ]
        );

        User::updateOrCreate(
            ['run' => '12345678-9'],
            [
                'nombre_completo' => 'Roberto Silva (RUT)',
                'rango' => 'Captain',
                'email' => 'roberto.silva2@empresa.com',
                'clave' => Hash::make('123456'),
                'fecha_nacimiento' => '1985-05-15',
                'domicilio' => 'Av. Principal 123, Santiago',
                'rol_id' => $rolAdmin?->id ?? 1,
                'sucursal_id' => $sucursalCentral?->id,
            ]
        );

        User::updateOrCreate(
            ['run' => '98765432-1'],
            [
                'nombre_completo' => 'María González',
                'rango' => 'Sergeant',
                'email' => 'maria.gonzalez@empresa.com',
                'clave' => Hash::make('123456'),
                'fecha_nacimiento' => '1990-08-22',
                'domicilio' => 'Calle Secundaria 456, Valparaíso',
                'rol_id' => $rolSupervisor?->id ?? 2,
                'sucursal_id' => $sucursalNorte?->id,
            ]
        );

        User::updateOrCreate(
            ['run' => '11223344-5'],
            [
                'nombre_completo' => 'Carlos Rodríguez',
                'rango' => 'Corporal',
                'email' => 'carlos.rodriguez@empresa.com',
                'clave' => Hash::make('123456'),
                'fecha_nacimiento' => '1988-12-10',
                'domicilio' => 'Plaza Mayor 789, Concepción',
                'rol_id' => $rolSupervisorUsuario?->id ?? 3,
                'sucursal_id' => $sucursalSur?->id,
            ]
        );

        User::updateOrCreate(
            ['run' => '22334455-6'],
            [
                'nombre_completo' => 'Ana Martínez',
                'rango' => null,
                'email' => 'ana.martinez@empresa.com',
                'clave' => Hash::make('123456'),
                'fecha_nacimiento' => '1992-03-18',
                'domicilio' => 'Calle Los Aromos 321, Santiago',
                'rol_id' => $rolUsuario?->id ?? 4,
                'sucursal_id' => $sucursalCentral?->id,
            ]
        );

        User::updateOrCreate(
            ['run' => '55667788-9'],
            [
                'nombre_completo' => 'Luis Guardia',
                'rango' => null,
                'email' => 'luis.guardia@empresa.com',
                'clave' => Hash::make('123456'),
                'fecha_nacimiento' => '1990-01-10',
                'domicilio' => 'Av. Control 100, Santiago',
                'rol_id' => $rolGuardia?->id ?? 5,
                'sucursal_id' => $sucursalCentral?->id,
            ]
        );
    }
}
