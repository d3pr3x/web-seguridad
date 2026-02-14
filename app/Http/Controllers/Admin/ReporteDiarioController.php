<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\DiaTrabajado;
use App\Models\User;
use App\Models\Sucursal;
use App\Models\Tarea;
use Carbon\Carbon;

class ReporteDiarioController extends Controller
{
    /**
     * Mostrar reporte diario
     */
    public function index(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::now()->format('Y-m-d'));
        $sucursalId = $request->get('sucursal_id');
        
        $sucursales = Sucursal::activas()->get();
        
        // Obtener reportes del día
        $reportes = Reporte::with(['user.sucursal', 'tarea'])
            ->whereDate('created_at', $fecha)
            ->when($sucursalId, function($query) use ($sucursalId) {
                return $query->whereHas('user', function($q) use ($sucursalId) {
                    $q->where('sucursal_id', $sucursalId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Obtener días trabajados del día
        $diasTrabajados = DiaTrabajado::with(['user.sucursal'])
            ->where('fecha', $fecha)
            ->when($sucursalId, function($query) use ($sucursalId) {
                return $query->whereHas('user', function($q) use ($sucursalId) {
                    $q->where('sucursal_id', $sucursalId);
                });
            })
            ->orderBy('creado_en', 'desc')
            ->get();
        
        // Estadísticas
        $totalReportes = $reportes->count();
        $totalDiasTrabajados = $diasTrabajados->count();
        $usuariosActivos = $diasTrabajados->pluck('id_usuario')->unique()->count();
        
        // Agrupar por sucursal
        $reportesPorSucursal = $reportes->groupBy('user.sucursal.nombre');
        $diasPorSucursal = $diasTrabajados->groupBy('user.sucursal.nombre');
        
        return view('admin.reportes-diarios', compact(
            'fecha', 'sucursales', 'sucursalId', 'reportes', 'diasTrabajados',
            'totalReportes', 'totalDiasTrabajados', 'usuariosActivos',
            'reportesPorSucursal', 'diasPorSucursal'
        ));
    }
    
    /**
     * Exportar reporte diario a PDF
     */
    public function exportar(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::now()->format('Y-m-d'));
        $sucursalId = $request->get('sucursal_id');
        
        // Lógica para exportar a PDF
        // Por ahora retornamos una vista
        return $this->index($request);
    }
}
