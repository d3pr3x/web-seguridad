<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarea;
use App\Models\TareaDetalle;

class TareaSeguridadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tarea: Auto sospechoso
        $tarea1 = Tarea::create([
            'nombre' => 'Auto sospechoso',
            'descripcion' => 'Reportar vehículos sospechosos en el área',
            'activa' => true,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Acciones',
            'tipo_campo' => 'text',
            'requerido' => true,
            'opciones' => null,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Resultado',
            'tipo_campo' => 'select',
            'requerido' => true,
            'opciones' => ['Sin novedad', 'Vehículo identificado', 'Vehículo disuadido', 'Intervención policial'],
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea1->id,
            'campo_nombre' => 'Observaciones',
            'tipo_campo' => 'textarea',
            'requerido' => false,
            'opciones' => null,
        ]);

        // Tarea: Acción disuasiva
        $tarea2 = Tarea::create([
            'nombre' => 'Acción disuasiva',
            'descripcion' => 'Realizar acciones preventivas de seguridad',
            'activa' => true,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Tipo de acción',
            'tipo_campo' => 'select',
            'requerido' => true,
            'opciones' => ['Guardia activa vigilancia preventiva', 'Patrullaje perimetral', 'Verificación de accesos', 'Control de identidad'],
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea2->id,
            'campo_nombre' => 'Resultado',
            'tipo_campo' => 'select',
            'requerido' => true,
            'opciones' => ['Sin novedad', 'Situación controlada', 'Requiere seguimiento', 'Escalado a supervisor'],
        ]);

        // Tarea: Delito en turno
        $tarea3 = Tarea::create([
            'nombre' => 'Delito en turno',
            'descripcion' => 'Reportar delitos o incidentes durante el turno',
            'activa' => true,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Tipo de delito',
            'tipo_campo' => 'select',
            'requerido' => true,
            'opciones' => ['Robo', 'Hurto', 'Vandalismo', 'Intrusión', 'Alteración del orden'],
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Acciones tomadas',
            'tipo_campo' => 'textarea',
            'requerido' => true,
            'opciones' => null,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea3->id,
            'campo_nombre' => 'Resultado',
            'tipo_campo' => 'select',
            'requerido' => true,
            'opciones' => ['Delito frustrado', 'Delito consumado', 'Sospechoso detenido', 'Requiere investigación'],
        ]);

        // Tarea: Vigilancia nocturna
        $tarea4 = Tarea::create([
            'nombre' => 'Vigilancia nocturna',
            'descripcion' => 'Rondas de vigilancia durante horario nocturno',
            'activa' => true,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea4->id,
            'campo_nombre' => 'Zona patrullada',
            'tipo_campo' => 'text',
            'requerido' => true,
            'opciones' => null,
        ]);

        TareaDetalle::create([
            'tarea_id' => $tarea4->id,
            'campo_nombre' => 'Observaciones',
            'tipo_campo' => 'textarea',
            'requerido' => false,
            'opciones' => null,
        ]);
    }
}