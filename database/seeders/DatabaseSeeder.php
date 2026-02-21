<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Orden por dependencias (español). Padres antes que hijos.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesUsuarioSeeder::class,
            ModalidadesJerarquiaSeeder::class,
            ModalidadRolesSeeder::class,
            PermisosSeeder::class,
            EmpresaSeeder::class,
            SucursalSeeder::class,
            SectorSeeder::class,
            TareaSeeder::class,
            GruposIncidentesSeeder::class,
            UbicacionPermitidaSeeder::class,
            UsuariosSeeder::class,
            TareaNovedadesSeeder::class,
            TareaIncidentesSeeder::class,
            TareaSeguridadSeeder::class,
            TareaDetalleSeeder::class,
            FeriadoSeeder::class,
            ConfiguracionSueldoSeeder::class,
            DispositivoPermitidoSeeder::class,
            ReunionesSeeder::class,
            ControlAccesoSeeder::class,
            DemoDatosOperacionalesSeeder::class,
        ]);
    }
}
