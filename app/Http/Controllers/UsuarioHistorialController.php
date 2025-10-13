<?php

namespace App\Http\Controllers;

use App\Models\Accion;
use App\Models\ReporteEspecial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioHistorialController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Obtener acciones del usuario
        $acciones = Accion::where('user_id', $user->id)
            ->where('sucursal_id', $user->sucursal_id)
            ->select('id', 'tipo', 'dia', 'hora', 'novedad', 'created_at')
            ->get()
            ->map(function ($accion) {
                return [
                    'id' => $accion->id,
                    'tipo_registro' => 'novedad',
                    'tipo' => $accion->tipo,
                    'nombre_tipo' => $accion->nombre_tipo,
                    'dia' => $accion->dia,
                    'hora' => $accion->hora,
                    'descripcion' => $accion->novedad ?? 'Sin descripción',
                    'fecha_completa' => $accion->created_at,
                    'ruta_detalle' => route('usuario.acciones.show', $accion->id),
                    'color' => $this->getColorNovedad($accion->tipo),
                    'icono' => $this->getIconoNovedad($accion->tipo),
                ];
            });
        
        // Obtener reportes del usuario
        $reportes = ReporteEspecial::where('user_id', $user->id)
            ->where('sucursal_id', $user->sucursal_id)
            ->select('id', 'tipo', 'dia', 'hora', 'novedad', 'estado', 'created_at')
            ->get()
            ->map(function ($reporte) {
                return [
                    'id' => $reporte->id,
                    'tipo_registro' => 'reporte',
                    'tipo' => $reporte->tipo,
                    'nombre_tipo' => $reporte->nombre_tipo,
                    'dia' => $reporte->dia,
                    'hora' => $reporte->hora,
                    'descripcion' => $reporte->novedad ?? 'Sin descripción',
                    'estado' => $reporte->estado,
                    'fecha_completa' => $reporte->created_at,
                    'ruta_detalle' => route('usuario.reportes.show', $reporte->id),
                    'color' => $this->getColorReporte($reporte->tipo),
                    'icono' => $this->getIconoReporte($reporte->tipo),
                    'estado_badge' => $this->getEstadoBadge($reporte->estado),
                ];
            });
        
        // Combinar y ordenar por fecha
        $registros = $acciones->concat($reportes)
            ->sortByDesc('fecha_completa')
            ->values();
        
        // Aplicar filtros si existen
        if ($request->filled('tipo_registro')) {
            $registros = $registros->where('tipo_registro', $request->tipo_registro)->values();
        }
        
        if ($request->filled('fecha_desde')) {
            $fechaDesde = \Carbon\Carbon::parse($request->fecha_desde)->startOfDay();
            $registros = $registros->filter(function ($registro) use ($fechaDesde) {
                return \Carbon\Carbon::parse($registro['fecha_completa'])->gte($fechaDesde);
            })->values();
        }
        
        if ($request->filled('fecha_hasta')) {
            $fechaHasta = \Carbon\Carbon::parse($request->fecha_hasta)->endOfDay();
            $registros = $registros->filter(function ($registro) use ($fechaHasta) {
                return \Carbon\Carbon::parse($registro['fecha_completa'])->lte($fechaHasta);
            })->values();
        }
        
        return view('usuario.historial.index', compact('registros'));
    }
    
    private function getColorNovedad($tipo)
    {
        $colores = [
            'inicio_servicio' => 'green',
            'rondas' => 'blue',
            'constancias' => 'purple',
            'concurrencia_carabineros' => 'indigo',
            'concurrencia_servicios' => 'orange',
            'entrega_servicio' => 'red',
        ];
        
        return $colores[$tipo] ?? 'gray';
    }
    
    private function getColorReporte($tipo)
    {
        $colores = [
            'incidentes' => 'red',
            'denuncia' => 'purple',
            'detenido' => 'orange',
            'accion_sospechosa' => 'yellow',
        ];
        
        return $colores[$tipo] ?? 'gray';
    }
    
    private function getIconoNovedad($tipo)
    {
        $iconos = [
            'inicio_servicio' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'rondas' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'constancias' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'concurrencia_carabineros' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            'concurrencia_servicios' => 'M13 10V3L4 14h7v7l9-11h-7z',
            'entrega_servicio' => 'M5 13l4 4L19 7',
        ];
        
        return $iconos[$tipo] ?? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
    }
    
    private function getIconoReporte($tipo)
    {
        $iconos = [
            'incidentes' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
            'denuncia' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
            'detenido' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
            'accion_sospechosa' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
        ];
        
        return $iconos[$tipo] ?? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
    }
    
    private function getEstadoBadge($estado)
    {
        $badges = [
            'pendiente' => ['color' => 'yellow', 'texto' => 'Pendiente'],
            'en_revision' => ['color' => 'blue', 'texto' => 'En Revisión'],
            'completado' => ['color' => 'green', 'texto' => 'Completado'],
            'rechazado' => ['color' => 'red', 'texto' => 'Rechazado'],
        ];
        
        return $badges[$estado] ?? ['color' => 'gray', 'texto' => 'Desconocido'];
    }
}


