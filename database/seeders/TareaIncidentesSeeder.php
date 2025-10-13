<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarea;
use App\Models\TareaDetalle;

class TareaIncidentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reporte de Detenido
        $tarea1 = Tarea::create([
            'nombre' => 'Reporte de Detenido',
            'descripcion' => 'Registro de personas detenidas durante el servicio de seguridad',
            'icono' => 'fas fa-user-lock',
            'color' => '#dc3545',
            'categoria' => 'reporte_incidentes',
            'activa' => true,
        ]);

        // Detalles para Reporte de Detenido
        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Hora de la detención',
            'tipo_campo' => 'time',
            'opciones' => null,
            'requerido' => true,
            'orden' => 1,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Nombre del detenido',
            'tipo_campo' => 'text',
            'opciones' => null,
            'requerido' => true,
            'orden' => 2,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'RUT/Identificación',
            'tipo_campo' => 'text',
            'opciones' => null,
            'requerido' => false,
            'orden' => 3,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Motivo de la detención',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Robo/Hurto',
                'Daño a la propiedad',
                'Alteración del orden público',
                'Ingreso no autorizado',
                'Conducta sospechosa',
                'Agresión',
                'Otro'
            ]),
            'requerido' => true,
            'orden' => 4,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Descripción del incidente',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 5,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Lugar de la detención',
            'tipo_campo' => 'text',
            'opciones' => null,
            'requerido' => true,
            'orden' => 6,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Evidencias encontradas',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => false,
            'orden' => 7,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Autoridades notificadas',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Carabineros',
                'Policía de Investigaciones (PDI)',
                'Supervisión',
                'Cliente',
                'Múltiples',
                'Pendiente de notificar'
            ]),
            'requerido' => true,
            'orden' => 8,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Estado del detenido',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Entregado a Carabineros',
                'Entregado a PDI',
                'Liberado con advertencia',
                'En custodia en sitio',
                'Otro'
            ]),
            'requerido' => true,
            'orden' => 9,
        ]);

        // 2. Reporte de Altercado
        $tarea2 = Tarea::create([
            'nombre' => 'Reporte de Altercado',
            'descripcion' => 'Registro de altercados, riñas o disputas ocurridas',
            'icono' => 'fas fa-user-injured',
            'color' => '#fd7e14',
            'categoria' => 'reporte_incidentes',
            'activa' => true,
        ]);

        // Detalles para Reporte de Altercado
        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Hora del altercado',
            'tipo_campo' => 'time',
            'opciones' => null,
            'requerido' => true,
            'orden' => 1,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Lugar del altercado',
            'tipo_campo' => 'text',
            'opciones' => null,
            'requerido' => true,
            'orden' => 2,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Tipo de altercado',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Riña verbal',
                'Pelea física',
                'Amenazas',
                'Disturbios',
                'Agresión',
                'Otro'
            ]),
            'requerido' => true,
            'orden' => 3,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Número de personas involucradas',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                '2 personas',
                '3-5 personas',
                '6-10 personas',
                'Más de 10 personas'
            ]),
            'requerido' => true,
            'orden' => 4,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Descripción del altercado',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 5,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Personas involucradas (nombres)',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => false,
            'orden' => 6,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Lesionados',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'No hay lesionados',
                'Lesiones leves',
                'Lesiones moderadas',
                'Lesiones graves',
                'Requiere atención médica'
            ]),
            'requerido' => true,
            'orden' => 7,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Acciones tomadas',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 8,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Autoridades notificadas',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Carabineros',
                'Ambulancia',
                'Supervisión',
                'Cliente',
                'Múltiples',
                'Ninguna'
            ]),
            'requerido' => true,
            'orden' => 9,
        ]);

        // 3. Reporte de Sospechosos
        $tarea3 = Tarea::create([
            'nombre' => 'Reporte de Sospechosos',
            'descripcion' => 'Registro de personas o actividades sospechosas detectadas',
            'icono' => 'fas fa-user-secret',
            'color' => '#6c757d',
            'categoria' => 'reporte_incidentes',
            'activa' => true,
        ]);

        // Detalles para Reporte de Sospechosos
        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Hora de detección',
            'tipo_campo' => 'time',
            'opciones' => null,
            'requerido' => true,
            'orden' => 1,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Lugar de detección',
            'tipo_campo' => 'text',
            'opciones' => null,
            'requerido' => true,
            'orden' => 2,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Tipo de actividad sospechosa',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Merodeo',
                'Ingreso no autorizado',
                'Comportamiento errático',
                'Observación prolongada',
                'Intentos de acceso',
                'Vehículo sospechoso',
                'Otro'
            ]),
            'requerido' => true,
            'orden' => 3,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Número de sospechosos',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                '1 persona',
                '2 personas',
                '3-5 personas',
                'Más de 5 personas'
            ]),
            'requerido' => true,
            'orden' => 4,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Descripción de los sospechosos',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 5,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Descripción de la actividad',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 6,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Vehículo involucrado',
            'tipo_campo' => 'text',
            'opciones' => null,
            'requerido' => false,
            'orden' => 7,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Acciones tomadas',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 8,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Resultado de la intervención',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Personas se retiraron',
                'Identificación realizada',
                'Entregado a autoridades',
                'Requiere seguimiento',
                'Situación controlada',
                'Otro'
            ]),
            'requerido' => true,
            'orden' => 9,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Notificaciones realizadas',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Carabineros',
                'Supervisión',
                'Cliente',
                'Múltiples',
                'Ninguna por el momento'
            ]),
            'requerido' => true,
            'orden' => 10,
        ]);
    }
}
