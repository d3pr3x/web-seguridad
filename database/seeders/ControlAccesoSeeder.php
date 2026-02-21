<?php

namespace Database\Seeders;

use App\Models\Blacklist;
use App\Models\Ingreso;
use App\Models\RolUsuario;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * 5 ingresos de prueba y 2 blacklists. Idempotente por (rut, fecha_ingreso).
 */
class ControlAccesoSeeder extends Seeder
{
    public function run(): void
    {
        $rolGuardia = RolUsuario::where('slug', 'guardia')->first();
        $guardia = $rolGuardia
            ? User::where('rol_id', $rolGuardia->id)->first()
            : User::query()->first();
        if (!$guardia) {
            return;
        }

        $fechaBase = now()->startOfDay()->addHours(8);
        $ingresos = [
            ['tipo' => 'peatonal', 'rut' => '20111222-4', 'nombre' => 'Juan Pérez', 'patente' => null, 'estado' => 'ingresado', 'alerta_blacklist' => false, 'offset_h' => 0],
            ['tipo' => 'peatonal', 'rut' => '20222333-5', 'nombre' => 'Ana López', 'patente' => null, 'estado' => 'salida', 'alerta_blacklist' => false, 'offset_h' => 1],
            ['tipo' => 'vehicular', 'rut' => '20333444-6', 'nombre' => 'Pedro Soto', 'patente' => 'ABCD12', 'estado' => 'ingresado', 'alerta_blacklist' => false, 'offset_h' => 2],
            ['tipo' => 'vehicular', 'rut' => '20444555-7', 'nombre' => 'Vehículo XYZW89', 'patente' => 'XYZW89', 'estado' => 'salida', 'alerta_blacklist' => false, 'offset_h' => 3],
            ['tipo' => 'peatonal', 'rut' => '20555666-8', 'nombre' => 'Persona bloqueada', 'patente' => null, 'estado' => 'bloqueado', 'alerta_blacklist' => true, 'offset_h' => 4],
        ];

        foreach ($ingresos as $i) {
            $offset_h = $i['offset_h'];
            unset($i['offset_h']);
            $fecha = $fechaBase->copy()->addHours($offset_h);
            Ingreso::updateOrCreate(
                [
                    'rut' => $i['rut'],
                    'fecha_ingreso' => $fecha,
                ],
                array_merge($i, [
                    'id_guardia' => $guardia->id_usuario,
                    'ip_ingreso' => '127.0.0.1',
                    'user_agent' => 'Seeder',
                ])
            );
        }

        Blacklist::updateOrCreate(
            ['rut' => '20555666-8'],
            [
                'patente' => null,
                'motivo' => 'Prueba blacklist - no autorizado',
                'fecha_inicio' => now()->toDateString(),
                'fecha_fin' => null,
                'activo' => true,
                'creado_por' => $guardia->id_usuario,
            ]
        );
        Blacklist::updateOrCreate(
            ['rut' => '20999888-1', 'patente' => 'ZZZZ99'],
            [
                'motivo' => 'Vehículo restringido demo',
                'fecha_inicio' => now()->toDateString(),
                'fecha_fin' => null,
                'activo' => true,
                'creado_por' => $guardia->id_usuario,
            ]
        );
    }
}
