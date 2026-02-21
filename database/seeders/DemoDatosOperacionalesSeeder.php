<?php

namespace Database\Seeders;

use App\Models\Accion;
use App\Models\DocumentoPersonal;
use App\Models\RondaEscaneo;
use App\Models\Informe;
use App\Models\PuntoRonda;
use App\Models\Reporte;
use App\Models\ReporteEspecial;
use App\Models\Sector;
use App\Models\Sucursal;
use App\Models\Tarea;
use App\Models\TipoIncidente;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Datos operacionales demo: puntos ronda, escaneos, acciones, reportes, informes, documentos pendientes.
 * Idempotente donde sea posible (codigo, numero_informe, etc.).
 */
class DemoDatosOperacionalesSeeder extends Seeder
{
    public function run(): void
    {
        $this->puntosRonda();
        $this->escaneos();
        $this->acciones();
        $this->reportesEspeciales();
        $this->reportesEInforme();
        $this->documentosPendientes();
    }

    private function puntosRonda(): void
    {
        $sucursales = Sucursal::all();
        $nombres = ['Acceso principal', 'Bodega', 'Oficinas', 'Perímetro exterior', 'Estacionamiento'];
        foreach ($sucursales as $suc) {
            $sectores = Sector::where('sucursal_id', $suc->id)->get();
            foreach ($nombres as $i => $nombre) {
                $sector = $sectores->get($i % $sectores->count());
                PuntoRonda::updateOrCreate(
                    [
                        'sucursal_id' => $suc->id,
                        'codigo' => $suc->codigo . '-P' . ($i + 1),
                    ],
                    [
                        'sector_id' => $sector?->id,
                        'nombre' => "Punto {$nombre}",
                        'descripcion' => "QR demo {$suc->codigo}",
                        'orden' => $i + 1,
                        'activo' => true,
                    ]
                );
            }
        }
    }

    private function escaneos(): void
    {
        $usuario = User::whereHas('rol', fn ($q) => $q->where('slug', 'usuario'))->first();
        $puntos = PuntoRonda::take(10)->get();
        if (!$usuario || $puntos->isEmpty()) {
            return;
        }
        $hora = now()->startOfDay()->addHours(9);
        foreach ($puntos->take(10) as $idx => $punto) {
            RondaEscaneo::firstOrCreate(
                [
                    'punto_ronda_id' => $punto->id,
                    'id_usuario' => $usuario->id_usuario,
                    'escaneado_en' => $hora->copy()->addMinutes($idx * 15),
                ],
                []
            );
        }
    }

    private function acciones(): void
    {
        $user = User::whereHas('rol', fn ($q) => $q->whereIn('slug', ['usuario', 'supervisor']))->first();
        $sucursal = Sucursal::with('sectores')->first();
        if (!$user || !$sucursal) {
            return;
        }
        $sector = $sucursal->sectores->first();
        $tipos = ['inicio_servicio', 'rondas', 'constancias'];
        $dia = now()->toDateString();
        foreach ($tipos as $i => $tipo) {
            Accion::updateOrCreate(
                [
                    'id_usuario' => $user->id_usuario,
                    'sucursal_id' => $sucursal->id,
                    'dia' => $dia,
                    'hora' => sprintf('%02d:%02d', 8 + $i, 0),
                ],
                [
                    'sector_id' => $sector?->id,
                    'tipo' => $tipo,
                    'tipo_hecho' => 'observacion',
                    'importancia' => 'cotidiana',
                    'novedad' => "Novedad demo {$tipo}",
                    'accion' => 'Acción realizada.',
                    'resultado' => 'OK',
                ]
            );
        }
    }

    private function reportesEspeciales(): void
    {
        $tipoIncidente = TipoIncidente::first();
        $usuario = User::whereHas('rol', fn ($q) => $q->where('slug', 'usuario'))->first();
        $sucursales = Sucursal::with('sectores')->take(2)->get();
        if (!$usuario || $sucursales->isEmpty()) {
            return;
        }
        $dia = now()->toDateString();
        foreach ($sucursales as $suc) {
            $sector = $suc->sectores->first();
            ReporteEspecial::updateOrCreate(
                [
                    'id_usuario' => $usuario->id_usuario,
                    'sucursal_id' => $suc->id,
                    'tipo' => 'incidentes',
                    'dia' => $dia,
                    'hora' => '10:00',
                ],
                [
                    'sector_id' => $sector?->id,
                    'tipo_incidente_id' => $tipoIncidente?->id,
                    'novedad' => 'Reporte especial demo.',
                    'estado' => 'pendiente',
                ]
            );
        }
    }

    private function reportesEInforme(): void
    {
        $tarea = Tarea::first();
        $usuarios = User::whereHas('rol', fn ($q) => $q->where('slug', 'usuario'))->get();
        $sectores = Sector::take(2)->get();
        if (!$tarea || $usuarios->isEmpty() || $sectores->isEmpty()) {
            return;
        }
        $user = $usuarios->first();
        $sector = $sectores->first();
        $reporte = Reporte::updateOrCreate(
            [
                'id_usuario' => $user->id_usuario,
                'tarea_id' => $tarea->id,
                'sector_id' => $sector->id,
                'estado' => 'completado',
            ],
            [
                'datos' => ['acciones' => 'Guardia activa protocolos.', 'resultado' => 'Requiere investigación', 'observaciones' => 'Demo.'],
                'imagenes' => [],
            ]
        );
        $num = (int) Informe::max('numero_informe') + 1 ?: 1;
        Informe::updateOrCreate(
            ['reporte_id' => $reporte->id],
            [
                'numero_informe' => $num,
                'hora' => '15:24',
                'descripcion' => 'Informe de demostración creado por seed.',
                'lesionados' => 'Ninguno.',
                'acciones_inmediatas' => ['Protocolo activado.'],
                'conclusiones' => ['Conclusión demo.'],
                'estado' => 'aprobado',
                'fecha_aprobacion' => now(),
                'aprobado_por' => 'Admin Demo',
            ]
        );
    }

    private function documentosPendientes(): void
    {
        $usuarios = User::whereHas('rol', fn ($q) => $q->whereIn('slug', ['usuario', 'usuario_supervisor']))->take(2)->get();
        $tipos = ['cedula_identidad', 'licencia_conductor'];
        foreach ($usuarios as $i => $user) {
            $tipo = $tipos[$i % count($tipos)];
            DocumentoPersonal::updateOrCreate(
                [
                    'id_usuario' => $user->id_usuario,
                    'tipo_documento' => $tipo,
                    'estado' => 'pendiente',
                ],
                [
                    'es_cambio' => false,
                ]
            );
        }
    }
}
