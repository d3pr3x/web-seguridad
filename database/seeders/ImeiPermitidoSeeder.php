<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ImeiPermitido;

class ImeiPermitidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imeis = [
            [
                'imei' => '123456789012345',
                'descripcion' => 'Dispositivo de prueba',
                'activo' => true,
            ],
            [
                'imei' => '987654321098765',
                'descripcion' => 'Teléfono administrativo',
                'activo' => true,
            ],
        ];

        foreach ($imeis as $imei) {
            ImeiPermitido::create($imei);
        }
    }
}
