<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ConfiguracionSueldo;

class ConfiguracionSueldoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConfiguracionSueldo::create([
            'tipo_dia' => 'habil',
            'multiplicador' => 1.00,
            'descripcion' => 'Días hábiles (lunes a viernes, no feriados)',
            'activo' => true,
        ]);

        ConfiguracionSueldo::create([
            'tipo_dia' => 'sabado',
            'multiplicador' => 1.25,
            'descripcion' => 'Sábados - 25% adicional',
            'activo' => true,
        ]);

        ConfiguracionSueldo::create([
            'tipo_dia' => 'domingo',
            'multiplicador' => 1.50,
            'descripcion' => 'Domingos - 50% adicional',
            'activo' => true,
        ]);

        ConfiguracionSueldo::create([
            'tipo_dia' => 'feriado',
            'multiplicador' => 2.00,
            'descripcion' => 'Feriados - 100% adicional (doble sueldo)',
            'activo' => true,
        ]);
    }
}
