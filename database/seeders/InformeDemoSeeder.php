<?php

namespace Database\Seeders;

use App\Models\Informe;
use App\Models\Reporte;
use App\Models\User;
use App\Models\Tarea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Crea un reporte e informe de demostración para poder ver el PDF.
 */
class InformeDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::orderBy('id_usuario')->first();
        $tarea = Tarea::orderBy('id')->first();

        if (!$user || !$tarea) {
            $this->command->warn('Ejecuta primero: php artisan db:seed (para crear usuarios y tareas).');
            return;
        }

        $datosReporte = [
            'id_usuario' => $user->id_usuario,
            'tarea_id' => $tarea->id,
            'datos' => [
                'acciones' => 'Guardia activa protocolos de seguridad y llamado a Carabineros.',
                'resultado' => 'Requiere investigación',
                'observaciones' => 'File 324 San Bernardo oriente, cuadrícula 1.',
            ],
            'imagenes' => $this->primeraImagenReporte(),
            'estado' => 'completado',
        ];
        if (Schema::hasColumn('reportes', 'user_id')) {
            $datosReporte['user_id'] = $user->id_usuario;
        }
        $reporte = Reporte::create($datosReporte);

        $fotografias = $reporte->imagenes ?? [];

        Informe::create([
            'reporte_id' => $reporte->id,
            'numero_informe' => Informe::max('numero_informe') + 1 ?: 43,
            'hora' => '15:24',
            'descripcion' => "Siendo las 15.24 horas de hoy viernes 06 de febrero de 2026, File 324 San Bernardo oriente, cuadrícula 1, individuos desconocidos fracturan ventana de SUV, sustrayendo dos mochilas con un notebook y ropa.\n\nGuardia activa protocolos de seguridad y llamado a Carabineros.",
            'lesionados' => 'Ninguno.',
            'acciones_inmediatas' => [
                'Personal de seguridad activa protocolos de seguridad.',
                'Se aplica protocolo de contención de víctimas.',
                'Carabineros no se constituye.',
                'Se realiza encargo a las demás Estaciones para prevenir otros incidentes.',
                'Se solicita respaldo de imágenes.',
            ],
            'conclusiones' => [
                'Se establece modus operandi robo mediante fractura de ventana.',
                'Existe señalética que aconseja no dejar bolsos al interior de los vehículos.',
                'Carabineros no se constituye.',
                'Se refuerzan protocolos de seguridad a nuestros Guardias.',
            ],
            'fotografias' => $fotografias,
            'estado' => 'aprobado',
            'fecha_aprobacion' => now(),
            'aprobado_por' => 'Administrador de Contrato',
        ]);

        $this->command->info('Informe de demostración creado. Abre: ' . url('/informes-preview-pdf'));
    }

    private function primeraImagenReporte(): array
    {
        $dir = storage_path('app/public/reportes');
        if (!is_dir($dir)) {
            return [];
        }
        $files = array_values(array_diff(scandir($dir), ['.', '..']));
        foreach ($files as $file) {
            if (preg_match('/\.(jpe?g|png|gif|webp)$/i', $file)) {
                return ['reportes/' . $file];
            }
        }
        return [];
    }
}
