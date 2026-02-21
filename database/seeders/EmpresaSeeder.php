<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\ModalidadJerarquia;
use Illuminate\Database\Seeder;

/**
 * Empresas demo: 2 empresas con modalidades y módulos distintos (idempotente por codigo).
 */
class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $modalidadJefeTurno = ModalidadJerarquia::where('nombre', 'con_jefe_turno')->first();
        $modalidadDirecta = ModalidadJerarquia::where('nombre', 'directa')->first();

        Empresa::updateOrCreate(
            ['codigo' => 'EMP1'],
            [
                'nombre' => 'Empresa Seguridad Norte',
                'razon_social' => 'Empresa Seguridad Norte SpA',
                'rut' => '76123456-7',
                'direccion' => 'Av. Principal 100',
                'comuna' => 'Santiago Centro',
                'ciudad' => 'Santiago',
                'region' => 'Metropolitana',
                'telefono' => '+56 2 2345 6700',
                'email' => 'contacto@empresa1.demo',
                'activa' => true,
                'modalidad_id' => $modalidadJefeTurno?->id,
                'modulos_activos' => [
                    'control_acceso',
                    'rondas_qr',
                    'documentos_guardias',
                    'reportes_diarios',
                    'calculo_sueldos',
                ],
            ]
        );

        Empresa::updateOrCreate(
            ['codigo' => 'EMP2'],
            [
                'nombre' => 'Empresa Vigilancia Sur',
                'razon_social' => 'Empresa Vigilancia Sur Ltda',
                'rut' => '76987654-3',
                'direccion' => 'Calle Sur 200',
                'comuna' => 'Concepción',
                'ciudad' => 'Concepción',
                'region' => 'Biobío',
                'telefono' => '+56 41 234 5600',
                'email' => 'contacto@empresa2.demo',
                'activa' => true,
                'modalidad_id' => $modalidadDirecta?->id,
                'modulos_activos' => [
                    'control_acceso',
                    'rondas_qr',
                ],
            ]
        );
    }
}
