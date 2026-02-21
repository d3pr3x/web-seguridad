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
        $tareas = [
            ['nombre' => 'Reportar Suceso', 'descripcion' => 'Reportar incidentes, accidentes o sucesos importantes que requieran atención', 'icono' => 'fas fa-exclamation-triangle', 'color' => '#dc3545'],
            ['nombre' => 'Inspección de Seguridad', 'descripcion' => 'Realizar inspecciones de seguridad en las instalaciones', 'icono' => 'fas fa-search', 'color' => '#28a745'],
            ['nombre' => 'Control de Acceso', 'descripcion' => 'Registrar y controlar el acceso de personas y vehículos', 'icono' => 'fas fa-key', 'color' => '#007bff'],
            ['nombre' => 'Ronda de Vigilancia', 'descripcion' => 'Realizar rondas de vigilancia en las áreas asignadas', 'icono' => 'fas fa-walking', 'color' => '#6f42c1'],
            ['nombre' => 'Mantenimiento Preventivo', 'descripcion' => 'Realizar mantenimiento preventivo de equipos y sistemas', 'icono' => 'fas fa-tools', 'color' => '#fd7e14'],
            ['nombre' => 'Reporte de Actividades', 'descripcion' => 'Reportar actividades realizadas durante el turno', 'icono' => 'fas fa-clipboard-list', 'color' => '#20c997'],
        ];
        foreach ($tareas as $t) {
            Tarea::updateOrCreate(
                ['nombre' => $t['nombre']],
                array_merge($t, ['activa' => true])
            );
        }
    }
}
