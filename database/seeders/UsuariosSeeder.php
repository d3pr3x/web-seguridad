<?php

namespace Database\Seeders;

use App\Models\RolUsuario;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Usuarios demo para navegar todo el sistema. Idempotente por run.
 * Contraseña común: Demo2026!Demo2026!
 */
class UsuariosSeeder extends Seeder
{
    public const PASSWORD_DEMO = 'Demo.2026';

    public function run(): void
    {
        $rolAdmin = RolUsuario::where('slug', 'admin')->first();
        $rolAdminContrato = RolUsuario::where('slug', 'admin_contrato')->first();
        $rolSupervisor = RolUsuario::where('slug', 'supervisor')->first();
        $rolUsuarioSupervisor = RolUsuario::where('slug', 'usuario_supervisor')->first();
        $rolUsuario = RolUsuario::where('slug', 'usuario')->first();
        $rolGuardia = RolUsuario::where('slug', 'guardia')->first();

        $instA = Sucursal::where('codigo', 'INST-A')->first();
        $instB = Sucursal::where('codigo', 'INST-B')->first();
        $instC = Sucursal::where('codigo', 'INST-C')->first();
        $instD = Sucursal::where('codigo', 'INST-D')->first();

        if (!$instA || !$rolAdmin) {
            $this->command->warn('Ejecuta antes: EmpresaSeeder, SucursalSeeder, RolesUsuarioSeeder.');
            return;
        }

        $clave = Hash::make(self::PASSWORD_DEMO);

        // 1 admin
        User::updateOrCreate(
            ['run' => '11111111-1'],
            [
                'nombre_completo' => 'Admin Demo',
                'rango' => null,
                'email' => 'admin@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1980-01-15',
                'domicilio' => 'Av. Principal 100',
                'rol_id' => $rolAdmin->id,
                'sucursal_id' => $instA->id,
            ]
        );

        // 1 admin_contrato
        User::updateOrCreate(
            ['run' => '22222222-2'],
            [
                'nombre_completo' => 'Admin Contrato Demo',
                'rango' => null,
                'email' => 'admin.contrato@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1982-05-20',
                'domicilio' => 'Av. Principal 100',
                'rol_id' => $rolAdminContrato->id,
                'sucursal_id' => $instA->id,
            ]
        );

        // 1 supervisor Empresa 1 (INST-A)
        User::updateOrCreate(
            ['run' => '33333333-3'],
            [
                'nombre_completo' => 'Supervisor Empresa 1',
                'rango' => 'Supervisor',
                'email' => 'supervisor.emp1@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1985-08-10',
                'domicilio' => 'Calle Secundaria 456',
                'rol_id' => $rolSupervisor->id,
                'sucursal_id' => $instA->id,
            ]
        );

        // 1 supervisor Empresa 2 (INST-C)
        User::updateOrCreate(
            ['run' => '44444444-4'],
            [
                'nombre_completo' => 'Supervisor Empresa 2',
                'rango' => 'Supervisor',
                'email' => 'supervisor.emp2@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1987-03-22',
                'domicilio' => 'Plaza Mayor 789',
                'rol_id' => $rolSupervisor->id,
                'sucursal_id' => $instC->id,
            ]
        );

        // 1 usuario_supervisor (Empresa 1 - modalidad con_jefe_turno)
        User::updateOrCreate(
            ['run' => '55555555-5'],
            [
                'nombre_completo' => 'Jefe Turno Empresa 1',
                'rango' => 'Jefe de turno',
                'email' => 'jefe.turno.emp1@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1990-11-05',
                'domicilio' => 'Av. Norte 100',
                'rol_id' => $rolUsuarioSupervisor->id,
                'sucursal_id' => $instA->id,
            ]
        );

        // 1 usuario por instalación
        User::updateOrCreate(
            ['run' => '66666666-6'],
            [
                'nombre_completo' => 'Usuario Instalación A',
                'rango' => null,
                'email' => 'usuario.insta@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1992-02-14',
                'domicilio' => 'Calle A 1',
                'rol_id' => $rolUsuario->id,
                'sucursal_id' => $instA->id,
            ]
        );
        User::updateOrCreate(
            ['run' => '77777777-7'],
            [
                'nombre_completo' => 'Usuario Instalación B',
                'rango' => null,
                'email' => 'usuario.instb@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1993-07-08',
                'domicilio' => 'Calle B 2',
                'rol_id' => $rolUsuario->id,
                'sucursal_id' => $instB->id,
            ]
        );
        User::updateOrCreate(
            ['run' => '88888888-8'],
            [
                'nombre_completo' => 'Usuario Instalación C',
                'rango' => null,
                'email' => 'usuario.instc@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1991-12-01',
                'domicilio' => 'Calle C 3',
                'rol_id' => $rolUsuario->id,
                'sucursal_id' => $instC->id,
            ]
        );
        User::updateOrCreate(
            ['run' => '99999999-9'],
            [
                'nombre_completo' => 'Usuario Instalación D',
                'rango' => null,
                'email' => 'usuario.instd@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1994-04-30',
                'domicilio' => 'Calle D 4',
                'rol_id' => $rolUsuario->id,
                'sucursal_id' => $instD->id,
            ]
        );

        // 1 guardia control de acceso
        User::updateOrCreate(
            ['run' => '12121212-3'],
            [
                'nombre_completo' => 'Guardia Control Acceso',
                'rango' => null,
                'email' => 'guardia@demo.local',
                'clave' => $clave,
                'fecha_nacimiento' => '1988-09-12',
                'domicilio' => 'Av. Control 50',
                'rol_id' => $rolGuardia->id,
                'sucursal_id' => $instA->id,
            ]
        );
    }
}
