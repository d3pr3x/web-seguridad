<?php

namespace Database\Seeders;

use App\Models\RolUsuario;
use Illuminate\Database\Seeder;

/**
 * Roles con slugs exactos para menú y gates. Idempotente por slug.
 * Slugs: usuario, usuario_supervisor, supervisor_usuario, supervisor, admin_contrato, admin, guardia.
 */
class RolesUsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador', 'slug' => 'admin', 'descripcion' => 'Acceso total'],
            ['nombre' => 'Admin contrato', 'slug' => 'admin_contrato', 'descripcion' => 'Administrador de contrato'],
            ['nombre' => 'Supervisor', 'slug' => 'supervisor', 'descripcion' => 'Supervisión de reportes y usuarios'],
            ['nombre' => 'Supervisor-Usuario', 'slug' => 'supervisor_usuario', 'descripcion' => 'Más funciones de supervisor; puede reportar'],
            ['nombre' => 'Usuario-Supervisor', 'slug' => 'usuario_supervisor', 'descripcion' => 'Más funciones de usuario; con acceso a supervisión'],
            ['nombre' => 'Usuario', 'slug' => 'usuario', 'descripcion' => 'Usuario operativo'],
            ['nombre' => 'Guardia control acceso', 'slug' => 'guardia', 'descripcion' => 'Control de acceso peatonal/vehicular'],
        ];

        foreach ($roles as $r) {
            RolUsuario::updateOrCreate(['slug' => $r['slug']], $r);
        }
    }
}
