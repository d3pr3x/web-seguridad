<?php

namespace Database\Seeders;

use App\Models\Blacklist;
use App\Models\Ingreso;
use App\Models\User;
use Illuminate\Database\Seeder;

class ControlAccesoSeeder extends Seeder
{
    /**
     * 5 ingresos de prueba y 2 blacklists.
     */
    public function run(): void
    {
        $guardia = User::query()->first();
        if (!$guardia) {
            return;
        }

        $ingresos = [
            [
                'tipo' => 'peatonal',
                'rut' => '11111111-1',
                'nombre' => 'Juan Pérez',
                'patente' => null,
                'guardia_id' => $guardia->id,
                'estado' => 'ingresado',
                'alerta_blacklist' => false,
            ],
            [
                'tipo' => 'peatonal',
                'rut' => '22222222-2',
                'nombre' => 'Ana López',
                'patente' => null,
                'guardia_id' => $guardia->id,
                'estado' => 'salida',
                'alerta_blacklist' => false,
            ],
            [
                'tipo' => 'vehicular',
                'rut' => '33333333-3',
                'nombre' => 'Pedro Soto',
                'patente' => 'ABCD12',
                'guardia_id' => $guardia->id,
                'estado' => 'ingresado',
                'alerta_blacklist' => false,
            ],
            [
                'tipo' => 'vehicular',
                'rut' => '44444444-4',
                'nombre' => null,
                'patente' => 'XYZW89',
                'guardia_id' => $guardia->id,
                'estado' => 'salida',
                'alerta_blacklist' => false,
            ],
            [
                'tipo' => 'peatonal',
                'rut' => '55555555-5',
                'nombre' => 'Persona bloqueada',
                'patente' => null,
                'guardia_id' => $guardia->id,
                'estado' => 'bloqueado',
                'alerta_blacklist' => true,
            ],
        ];

        foreach ($ingresos as $idx => $i) {
            Ingreso::firstOrCreate(
                [
                    'rut' => $i['rut'],
                    'guardia_id' => $guardia->id,
                    'fecha_ingreso' => now()->subHours(count($ingresos) - $idx),
                ],
                array_merge($i, [
                    'ip_ingreso' => '127.0.0.1',
                    'user_agent' => 'Seeder',
                ])
            );
        }

        Blacklist::updateOrCreate(
            ['rut' => '55555555-5'],
            [
                'patente' => null,
                'motivo' => 'Prueba blacklist - no autorizado',
                'fecha_inicio' => now()->toDateString(),
                'fecha_fin' => null,
                'activo' => true,
                'created_by' => $guardia->id,
            ]
        );
        if (!Blacklist::where('patente', 'ZZZZ99')->exists()) {
            Blacklist::create([
                'rut' => '99999999-9',
                'patente' => 'ZZZZ99',
                'motivo' => 'Vehículo restringido',
                'fecha_inicio' => now()->toDateString(),
                'fecha_fin' => null,
                'activo' => true,
                'created_by' => $guardia->id,
            ]);
        }
    }
}
