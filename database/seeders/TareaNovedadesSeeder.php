<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarea;
use App\Models\TareaDetalle;

class TareaNovedadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Inicio del Servicio
        $tarea1 = Tarea::create([
            'nombre' => 'Inicio del Servicio',
            'descripcion' => 'Registro del inicio del turno de seguridad',
            'icono' => 'fas fa-play-circle',
            'color' => '#28a745',
            'categoria' => 'novedades_servicio',
            'activa' => true,
        ]);

        // Detalles para Inicio del Servicio
        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Hora de inicio',
            'tipo_campo' => 'time',
            'opciones' => null,
            'requerido' => true,
            'orden' => 1,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Estado de las instalaciones',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Normal - Todo en orden',
                'Con observaciones menores',
                'Requiere atención',
                'Situación anormal'
            ]),
            'requerido' => true,
            'orden' => 2,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Personal presente',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 3,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Novedades del turno anterior',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => false,
            'orden' => 4,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Observaciones iniciales',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => false,
            'orden' => 5,
        ]);

        // 2. Rondas
        $tarea2 = Tarea::create([
            'nombre' => 'Rondas',
            'descripcion' => 'Registro de rondas de vigilancia realizadas',
            'icono' => 'fas fa-walking',
            'color' => '#17a2b8',
            'categoria' => 'novedades_servicio',
            'activa' => true,
        ]);

        // Detalles para Rondas
        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Hora de la ronda',
            'tipo_campo' => 'time',
            'opciones' => null,
            'requerido' => true,
            'orden' => 1,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Área recorrida',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Perímetro exterior',
                'Instalaciones interiores',
                'Áreas comunes',
                'Estacionamientos',
                'Accesos',
                'Todo el complejo',
                'Otra'
            ]),
            'requerido' => true,
            'orden' => 2,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Hallazgos durante la ronda',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 3,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Estado general',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Todo normal',
                'Observaciones menores',
                'Situación que requiere atención',
                'Situación de riesgo'
            ]),
            'requerido' => true,
            'orden' => 4,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Acciones tomadas',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => false,
            'orden' => 5,
        ]);

        // 3. Constancias
        $tarea3 = Tarea::create([
            'nombre' => 'Constancias',
            'descripcion' => 'Registro de constancias y verificaciones del servicio',
            'icono' => 'fas fa-check-circle',
            'color' => '#6f42c1',
            'categoria' => 'novedades_servicio',
            'activa' => true,
        ]);

        // Detalles para Constancias
        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Tipo de constancia',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Apertura de instalaciones',
                'Cierre de instalaciones',
                'Entrega de llaves',
                'Recepción de llaves',
                'Inspección de equipos',
                'Verificación de sistemas',
                'Otra'
            ]),
            'requerido' => true,
            'orden' => 1,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Hora de la constancia',
            'tipo_campo' => 'time',
            'opciones' => null,
            'requerido' => true,
            'orden' => 2,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Descripción detallada',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 3,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Persona involucrada',
            'tipo_campo' => 'text',
            'opciones' => null,
            'requerido' => false,
            'orden' => 4,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Observaciones',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => false,
            'orden' => 5,
        ]);

        // 4. Entrega del Servicio
        $tarea4 = Tarea::create([
            'nombre' => 'Entrega del Servicio',
            'descripcion' => 'Registro del cierre del turno y entrega al siguiente guardia',
            'icono' => 'fas fa-handshake',
            'color' => '#fd7e14',
            'categoria' => 'novedades_servicio',
            'activa' => true,
        ]);

        // Detalles para Entrega del Servicio
        TareaDetalle::create([
            'tarea_id' => $tarea4->id,
            'campo_nombre' => 'Hora de entrega',
            'tipo_campo' => 'time',
            'opciones' => null,
            'requerido' => true,
            'orden' => 1,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea4->id,
            'campo_nombre' => 'Guardia que recibe',
            'tipo_campo' => 'text',
            'opciones' => null,
            'requerido' => true,
            'orden' => 2,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea4->id,
            'campo_nombre' => 'Estado de las instalaciones',
            'tipo_campo' => 'select',
            'opciones' => json_encode([
                'Normal - Todo en orden',
                'Con observaciones',
                'Requiere atención urgente',
                'Con situaciones pendientes'
            ]),
            'requerido' => true,
            'orden' => 3,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea4->id,
            'campo_nombre' => 'Resumen del turno',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => true,
            'orden' => 4,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea4->id,
            'campo_nombre' => 'Novedades a informar',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => false,
            'orden' => 5,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea4->id,
            'campo_nombre' => 'Pendientes para el siguiente turno',
            'tipo_campo' => 'textarea',
            'opciones' => null,
            'requerido' => false,
            'orden' => 6,
        ]);
    }
}
