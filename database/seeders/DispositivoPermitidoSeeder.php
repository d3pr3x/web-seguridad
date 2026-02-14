<?php

namespace Database\Seeders;

use App\Models\DispositivoPermitido;
use Illuminate\Database\Seeder;

class DispositivoPermitidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Dispositivos permitidos (browser_fingerprint). Reemplaza ImeiPermitidoSeeder para schema consolidado.
     */
    public function run(): void
    {
        $dispositivos = [
            [
                'browser_fingerprint' => '123456789012345',
                'descripcion' => 'Dispositivo de prueba',
                'activo' => true,
                'requiere_ubicacion' => true,
            ],
            [
                'browser_fingerprint' => '987654321098765',
                'descripcion' => 'Teléfono administrativo',
                'activo' => true,
                'requiere_ubicacion' => false,
            ],
        ];

        foreach ($dispositivos as $d) {
            DispositivoPermitido::firstOrCreate(
                ['browser_fingerprint' => $d['browser_fingerprint']],
                $d
            );
        }
    }
}
