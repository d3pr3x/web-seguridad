<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tarea;

class TareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tarea: Reportar Suceso
        Tarea::create([
            'nombre' => 'Reportar Suceso',
            'descripcion' => 'Reportar incidentes, accidentes o sucesos importantes que requieran atención',
            'icono' => 'fas fa-exclamation-triangle',
            'color' => '#dc3545',
            'activa' => true,
        ]);

        // Tarea: Inspección de Seguridad
        Tarea::create([
            'nombre' => 'Inspección de Seguridad',
            'descripcion' => 'Realizar inspecciones de seguridad en las instalaciones',
            'icono' => 'fas fa-search',
            'color' => '#28a745',
            'activa' => true,
        ]);

        // Tarea: Control de Acceso
        Tarea::create([
            'nombre' => 'Control de Acceso',
            'descripcion' => 'Registrar y controlar el acceso de personas y vehículos',
            'icono' => 'fas fa-key',
            'color' => '#007bff',
            'activa' => true,
        ]);

        // Tarea: Ronda de Vigilancia
        Tarea::create([
            'nombre' => 'Ronda de Vigilancia',
            'descripcion' => 'Realizar rondas de vigilancia en las áreas asignadas',
            'icono' => 'fas fa-walking',
            'color' => '#6f42c1',
            'activa' => true,
        ]);

        // Tarea: Mantenimiento Preventivo
        Tarea::create([
            'nombre' => 'Mantenimiento Preventivo',
            'descripcion' => 'Realizar mantenimiento preventivo de equipos y sistemas',
            'icono' => 'fas fa-tools',
            'color' => '#fd7e14',
            'activa' => true,
        ]);

        // Tarea: Reporte de Actividades
        Tarea::create([
            'nombre' => 'Reporte de Actividades',
            'descripcion' => 'Reportar actividades realizadas durante el turno',
            'icono' => 'fas fa-clipboard-list',
            'color' => '#20c997',
            'activa' => true,
        ]);
    }
}
