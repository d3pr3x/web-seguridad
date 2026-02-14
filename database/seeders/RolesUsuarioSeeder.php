<?php

namespace Database\Seeders;

use App\Models\RolUsuario;
use Illuminate\Database\Seeder;

class RolesUsuarioSeeder extends Seeder
{
    /**
     * Roles: ADMIN, SUPERVISOR, USUARIO, GUARDIA.
     */
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador', 'slug' => 'ADMIN', 'descripcion' => 'Acceso total'],
            ['nombre' => 'Supervisor', 'slug' => 'SUPERVISOR', 'descripcion' => 'Supervisión de reportes y usuarios'],
            ['nombre' => 'Supervisor-Usuario', 'slug' => 'SUPERVISOR_USUARIO', 'descripcion' => 'Supervisor con capacidad de reportar'],
            ['nombre' => 'Usuario', 'slug' => 'USUARIO', 'descripcion' => 'Usuario operativo'],
            ['nombre' => 'Guardia control acceso', 'slug' => 'GUARDIA', 'descripcion' => 'Control de acceso peatonal/vehicular'],
        ];

        foreach ($roles as $r) {
            RolUsuario::firstOrCreate(['slug' => $r['slug']], $r);
        }
    }
}
