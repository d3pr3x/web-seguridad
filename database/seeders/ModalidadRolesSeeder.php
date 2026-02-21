<?php

namespace Database\Seeders;

use App\Models\ModalidadJerarquia;
use App\Models\RolUsuario;
use Illuminate\Database\Seeder;

class ModalidadRolesSeeder extends Seeder
{
    /**
     * Asocia roles a cada modalidad con orden (menor = más importante en menú/flujo).
     * Slugs esperados: usuario, usuario_supervisor, supervisor_usuario, supervisor, admin_contrato, admin.
     */
    public function run(): void
    {
        $this->normalizarSlugsRoles();

        $modalidadDirecta = ModalidadJerarquia::where('nombre', 'directa')->first();
        $modalidadJefeTurno = ModalidadJerarquia::where('nombre', 'con_jefe_turno')->first();
        $modalidadCustom = ModalidadJerarquia::where('nombre', 'custom')->first();

        $ordenPorSlug = [
            'admin' => 0,
            'admin_contrato' => 1,
            'ADMIN' => 0,
            'supervisor' => 2,
            'SUPERVISOR' => 2,
            'supervisor_usuario' => 3,
            'SUPERVISOR_USUARIO' => 3,
            'usuario_supervisor' => 4,
            'USUARIO_SUPERVISOR' => 4,
            'usuario' => 5,
            'USUARIO' => 5,
            'guardia' => 6,
            'GUARDIA' => 6,
        ];

        foreach (ModalidadJerarquia::all() as $modalidad) {
            foreach (RolUsuario::all() as $rol) {
                $orden = $ordenPorSlug[$rol->slug] ?? $ordenPorSlug[strtolower($rol->slug)] ?? 99;
                $modalidad->rolesOrdenados()->syncWithoutDetaching([
                    $rol->id => ['orden' => $orden, 'activo' => true],
                ]);
            }
        }
    }

    /**
     * Asegura que todos los slugs (minúsculas) tengan orden. RolesUsuarioSeeder ya crea admin_contrato.
     */
    private function normalizarSlugsRoles(): void
    {
        // No-op: roles se crean en RolesUsuarioSeeder con slugs en minúsculas.
    }
}
