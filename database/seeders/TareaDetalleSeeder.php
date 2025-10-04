<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tarea;
use App\Models\TareaDetalle;

class TareaDetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Detalles para "Reportar Suceso"
        $reportarSuceso = Tarea::where('nombre', 'Reportar Suceso')->first();
        if ($reportarSuceso) {
            TareaDetalle::create([
                'tarea_id' => $reportarSuceso->id,
                'campo_nombre' => 'Tipo de Suceso',
                'tipo_campo' => 'select',
                'opciones' => ['Accidente', 'Incidente', 'Robo', 'Vandalismo', 'Emergencia médica', 'Otro'],
                'requerido' => true,
                'orden' => 1,
            ]);

            TareaDetalle::create([
                'tarea_id' => $reportarSuceso->id,
                'campo_nombre' => 'Ubicación',
                'tipo_campo' => 'text',
                'requerido' => true,
                'orden' => 2,
            ]);

            TareaDetalle::create([
                'tarea_id' => $reportarSuceso->id,
                'campo_nombre' => 'Descripción del suceso',
                'tipo_campo' => 'textarea',
                'requerido' => true,
                'orden' => 3,
            ]);

            TareaDetalle::create([
                'tarea_id' => $reportarSuceso->id,
                'campo_nombre' => 'Fecha y hora del suceso',
                'tipo_campo' => 'date',
                'requerido' => true,
                'orden' => 4,
            ]);

            TareaDetalle::create([
                'tarea_id' => $reportarSuceso->id,
                'campo_nombre' => 'Personas involucradas',
                'tipo_campo' => 'textarea',
                'requerido' => false,
                'orden' => 5,
            ]);
        }

        // Detalles para "Inspección de Seguridad"
        $inspeccion = Tarea::where('nombre', 'Inspección de Seguridad')->first();
        if ($inspeccion) {
            TareaDetalle::create([
                'tarea_id' => $inspeccion->id,
                'campo_nombre' => 'Área inspeccionada',
                'tipo_campo' => 'select',
                'opciones' => ['Entrada principal', 'Estacionamiento', 'Oficinas', 'Almacén', 'Área de producción', 'Exterior'],
                'requerido' => true,
                'orden' => 1,
            ]);

            TareaDetalle::create([
                'tarea_id' => $inspeccion->id,
                'campo_nombre' => 'Estado general',
                'tipo_campo' => 'select',
                'opciones' => ['Excelente', 'Bueno', 'Regular', 'Malo', 'Crítico'],
                'requerido' => true,
                'orden' => 2,
            ]);

            TareaDetalle::create([
                'tarea_id' => $inspeccion->id,
                'campo_nombre' => 'Observaciones',
                'tipo_campo' => 'textarea',
                'requerido' => false,
                'orden' => 3,
            ]);

            TareaDetalle::create([
                'tarea_id' => $inspeccion->id,
                'campo_nombre' => 'Acciones correctivas necesarias',
                'tipo_campo' => 'textarea',
                'requerido' => false,
                'orden' => 4,
            ]);
        }

        // Detalles para "Control de Acceso"
        $controlAcceso = Tarea::where('nombre', 'Control de Acceso')->first();
        if ($controlAcceso) {
            TareaDetalle::create([
                'tarea_id' => $controlAcceso->id,
                'campo_nombre' => 'Tipo de acceso',
                'tipo_campo' => 'select',
                'opciones' => ['Persona', 'Vehículo', 'Mercancía'],
                'requerido' => true,
                'orden' => 1,
            ]);

            TareaDetalle::create([
                'tarea_id' => $controlAcceso->id,
                'campo_nombre' => 'Nombre/Razón social',
                'tipo_campo' => 'text',
                'requerido' => true,
                'orden' => 2,
            ]);

            TareaDetalle::create([
                'tarea_id' => $controlAcceso->id,
                'campo_nombre' => 'RUT/Documento',
                'tipo_campo' => 'text',
                'requerido' => true,
                'orden' => 3,
            ]);

            TareaDetalle::create([
                'tarea_id' => $controlAcceso->id,
                'campo_nombre' => 'Motivo de la visita',
                'tipo_campo' => 'textarea',
                'requerido' => true,
                'orden' => 4,
            ]);

            TareaDetalle::create([
                'tarea_id' => $controlAcceso->id,
                'campo_nombre' => 'Hora de entrada',
                'tipo_campo' => 'text',
                'requerido' => true,
                'orden' => 5,
            ]);
        }

        // Detalles para "Ronda de Vigilancia"
        $ronda = Tarea::where('nombre', 'Ronda de Vigilancia')->first();
        if ($ronda) {
            TareaDetalle::create([
                'tarea_id' => $ronda->id,
                'campo_nombre' => 'Ruta de la ronda',
                'tipo_campo' => 'select',
                'opciones' => ['Ruta A - Perímetro', 'Ruta B - Interior', 'Ruta C - Completa', 'Ruta personalizada'],
                'requerido' => true,
                'orden' => 1,
            ]);

            TareaDetalle::create([
                'tarea_id' => $ronda->id,
                'campo_nombre' => 'Hora de inicio',
                'tipo_campo' => 'text',
                'requerido' => true,
                'orden' => 2,
            ]);

            TareaDetalle::create([
                'tarea_id' => $ronda->id,
                'campo_nombre' => 'Hora de finalización',
                'tipo_campo' => 'text',
                'requerido' => true,
                'orden' => 3,
            ]);

            TareaDetalle::create([
                'tarea_id' => $ronda->id,
                'campo_nombre' => 'Incidencias encontradas',
                'tipo_campo' => 'textarea',
                'requerido' => false,
                'orden' => 4,
            ]);
        }

        // Detalles para "Mantenimiento Preventivo"
        $mantenimiento = Tarea::where('nombre', 'Mantenimiento Preventivo')->first();
        if ($mantenimiento) {
            TareaDetalle::create([
                'tarea_id' => $mantenimiento->id,
                'campo_nombre' => 'Equipo/Sistema',
                'tipo_campo' => 'select',
                'opciones' => ['Cámaras de seguridad', 'Sistema de alarma', 'Iluminación', 'Cercos eléctricos', 'Sistema de acceso', 'Otro'],
                'requerido' => true,
                'orden' => 1,
            ]);

            TareaDetalle::create([
                'tarea_id' => $mantenimiento->id,
                'campo_nombre' => 'Tipo de mantenimiento',
                'tipo_campo' => 'select',
                'opciones' => ['Limpieza', 'Calibración', 'Revisión', 'Reparación menor', 'Reemplazo de piezas'],
                'requerido' => true,
                'orden' => 2,
            ]);

            TareaDetalle::create([
                'tarea_id' => $mantenimiento->id,
                'campo_nombre' => 'Estado del equipo',
                'tipo_campo' => 'select',
                'opciones' => ['Funcionando correctamente', 'Requiere atención', 'Fuera de servicio'],
                'requerido' => true,
                'orden' => 3,
            ]);

            TareaDetalle::create([
                'tarea_id' => $mantenimiento->id,
                'campo_nombre' => 'Observaciones',
                'tipo_campo' => 'textarea',
                'requerido' => false,
                'orden' => 4,
            ]);
        }

        // Detalles para "Reporte de Actividades"
        $reporteActividades = Tarea::where('nombre', 'Reporte de Actividades')->first();
        if ($reporteActividades) {
            TareaDetalle::create([
                'tarea_id' => $reporteActividades->id,
                'campo_nombre' => 'Turno',
                'tipo_campo' => 'select',
                'opciones' => ['Mañana (06:00 - 14:00)', 'Tarde (14:00 - 22:00)', 'Noche (22:00 - 06:00)'],
                'requerido' => true,
                'orden' => 1,
            ]);

            TareaDetalle::create([
                'tarea_id' => $reporteActividades->id,
                'campo_nombre' => 'Actividades realizadas',
                'tipo_campo' => 'textarea',
                'requerido' => true,
                'orden' => 2,
            ]);

            TareaDetalle::create([
                'tarea_id' => $reporteActividades->id,
                'campo_nombre' => 'Incidencias del turno',
                'tipo_campo' => 'textarea',
                'requerido' => false,
                'orden' => 3,
            ]);

            TareaDetalle::create([
                'tarea_id' => $reporteActividades->id,
                'campo_nombre' => 'Recomendaciones',
                'tipo_campo' => 'textarea',
                'requerido' => false,
                'orden' => 4,
            ]);
        }
    }
}
