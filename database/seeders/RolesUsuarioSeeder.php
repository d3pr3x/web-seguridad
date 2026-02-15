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
            ['nombre' => 'Supervisor-Usuario', 'slug' => 'SUPERVISOR_USUARIO', 'descripcion' => 'Más funciones de supervisor; puede reportar'],
            ['nombre' => 'Usuario-Supervisor', 'slug' => 'USUARIO_SUPERVISOR', 'descripcion' => 'Más funciones de usuario; con acceso a supervisión'],
            ['nombre' => 'Usuario', 'slug' => 'USUARIO', 'descripcion' => 'Usuario operativo'],
            ['nombre' => 'Guardia control acceso', 'slug' => 'GUARDIA', 'descripcion' => 'Control de acceso peatonal/vehicular'],
        ];

        foreach ($roles as $r) {
            RolUsuario::firstOrCreate(['slug' => $r['slug']], $r);
        }
    }
}
