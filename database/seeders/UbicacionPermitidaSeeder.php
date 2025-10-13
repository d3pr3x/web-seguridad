<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UbicacionPermitida;
use App\Models\Sucursal;

class UbicacionPermitidaSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Obtener sucursales existentes
        $sucursales = Sucursal::all();

        // Crear ubicaciones permitidas de ejemplo
        // NOTA: Estas son ubicaciones de ejemplo. Debes reemplazarlas con las coordenadas reales de tus sucursales
        
        $ubicaciones = [
            [
                'nombre' => 'Oficina Central - Santiago',
                'latitud' => -33.4489,  // Ejemplo: Santiago Centro
                'longitud' => -70.6693,
                'radio' => 50,
                'activa' => true,
                'descripcion' => 'Ubicación principal de la oficina central en Santiago',
                'sucursal_id' => $sucursales->first()->id ?? null,
            ],
            [
                'nombre' => 'Sucursal Norte - Valparaíso',
                'latitud' => -33.0472,  // Ejemplo: Valparaíso
                'longitud' => -71.6127,
                'radio' => 50,
                'activa' => true,
                'descripcion' => 'Sucursal ubicada en Valparaíso',
                'sucursal_id' => $sucursales->skip(1)->first()->id ?? null,
            ],
            [
                'nombre' => 'Sucursal Sur - Concepción',
                'latitud' => -36.8270,  // Ejemplo: Concepción
                'longitud' => -73.0498,
                'radio' => 50,
                'activa' => true,
                'descripcion' => 'Sucursal ubicada en Concepción',
                'sucursal_id' => $sucursales->skip(2)->first()->id ?? null,
            ],
            [
                'nombre' => 'Sucursal Oriente - Las Condes',
                'latitud' => -33.4172,  // Ejemplo: Las Condes
                'longitud' => -70.5927,
                'radio' => 50,
                'activa' => true,
                'descripcion' => 'Sucursal ubicada en el sector oriente de Santiago',
                'sucursal_id' => $sucursales->skip(3)->first()->id ?? null,
            ],
        ];

        foreach ($ubicaciones as $ubicacion) {
            UbicacionPermitida::create($ubicacion);
        }

        $this->command->info('✅ Ubicaciones permitidas creadas exitosamente.');
        $this->command->warn('⚠️  IMPORTANTE: Las coordenadas son de ejemplo. Actualízalas con las coordenadas reales de tus sucursales.');
    }
}
