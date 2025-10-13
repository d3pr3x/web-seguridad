<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Sucursal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteSucursalController extends Controller
{
    /**
     * Mostrar reporte por sucursal
     */
    public function index(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::now()->format('Y-m-d'));
        $sucursalId = $request->get('sucursal_id');
        
        $sucursales = Sucursal::orderBy('nombre')->get();
        
        $query = Reporte::with(['user', 'tarea'])
            ->whereDate('created_at', $fecha);
            
        if ($sucursalId) {
            $query->whereHas('user', function($q) use ($sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            });
        }
        
        $reportes = $query->orderBy('created_at', 'desc')->get();
        
        // Agrupar por sucursal
        $reportesPorSucursal = $reportes->groupBy(function($reporte) {
            return $reporte->user->sucursal->nombre ?? 'Sin sucursal';
        });
        
        // Estadísticas
        $totalReportes = $reportes->count();
        $totalSucursales = $reportesPorSucursal->count();
        $accionesDisuasivas = $reportes->where('tarea.nombre', 'like', '%disuasiv%')->count();
        $delitosEnTurnos = $reportes->where('tarea.nombre', 'like', '%delito%')->count();
        
        return view('admin.reporte-sucursal', compact(
            'reportesPorSucursal',
            'sucursales',
            'fecha',
            'sucursalId',
            'totalReportes',
            'totalSucursales',
            'accionesDisuasivas',
            'delitosEnTurnos'
        ));
    }
    
    /**
     * Exportar reporte a PDF
     */
    public function exportar(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::now()->format('Y-m-d'));
        $sucursalId = $request->get('sucursal_id');
        
        $query = Reporte::with(['user', 'tarea'])
            ->whereDate('created_at', $fecha);
            
        if ($sucursalId) {
            $query->whereHas('user', function($q) use ($sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            });
        }
        
        $reportes = $query->orderBy('created_at', 'desc')->get();
        
        // Agrupar por sucursal
        $reportesPorSucursal = $reportes->groupBy(function($reporte) {
            return $reporte->user->sucursal->nombre ?? 'Sin sucursal';
        });
        
        // Estadísticas
        $totalReportes = $reportes->count();
        $accionesDisuasivas = $reportes->where('tarea.nombre', 'like', '%disuasiv%')->count();
        $delitosEnTurnos = $reportes->where('tarea.nombre', 'like', '%delito%')->count();
        
        $sucursal = null;
        if ($sucursalId) {
            $sucursal = Sucursal::find($sucursalId);
        }
        
        return view('admin.reporte-sucursal-pdf', compact(
            'reportesPorSucursal',
            'fecha',
            'sucursal',
            'totalReportes',
            'accionesDisuasivas',
            'delitosEnTurnos'
        ));
    }
}