<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Seeder;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [
            ['nombre' => 'Ver reportes', 'slug' => 'reportes.ver', 'descripcion' => 'Ver listado de reportes'],
            ['nombre' => 'Crear reportes', 'slug' => 'reportes.crear', 'descripcion' => 'Crear nuevos reportes'],
            ['nombre' => 'Administrar usuarios', 'slug' => 'usuarios.admin', 'descripcion' => 'Gestionar usuarios'],
            ['nombre' => 'Ver reuniones', 'slug' => 'reuniones.ver', 'descripcion' => 'Ver reuniones'],
        ];

        foreach ($permisos as $p) {
            Permiso::firstOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
