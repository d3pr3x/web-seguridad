<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReporteEspecial;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;

class ReporteEspecialController extends Controller
{
    /**
     * Mostrar listado completo de reportes especiales (Admin)
     */
    public function index(Request $request)
    {
        $query = ReporteEspecial::with(['user', 'sucursal', 'sector']);

        // Filtros
        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('tipo')) {
            $query->porTipo($request->tipo);
        }

        if ($request->filled('estado')) {
            $query->porEstado($request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('dia', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('dia', '<=', $request->fecha_hasta);
        }

        $reportes = $query->orderBy('dia', 'desc')
                          ->orderBy('hora', 'desc')
                          ->paginate(20);

        // Datos para filtros
        $sucursales = Sucursal::activas()->orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();
        $tipos = ReporteEspecial::tipos();
        $estados = ['pendiente', 'en_revision', 'completado', 'rechazado'];

        // Estadísticas
        $totalReportes = $query->count();
        $reportesPorEstado = ReporteEspecial::selectRaw('estado, count(*) as total')
            ->when($request->filled('sucursal_id'), function($q) use ($request) {
                return $q->where('sucursal_id', $request->sucursal_id);
            })
            ->groupBy('estado')
            ->get()
            ->pluck('total', 'estado');

        $reportesPorTipo = ReporteEspecial::selectRaw('tipo, count(*) as total')
            ->when($request->filled('sucursal_id'), function($q) use ($request) {
                return $q->where('sucursal_id', $request->sucursal_id);
            })
            ->groupBy('tipo')
            ->get()
            ->pluck('total', 'tipo');

        return view('admin.reportes-especiales.index', compact(
            'reportes', 
            'sucursales', 
            'usuarios', 
            'tipos',
            'estados',
            'totalReportes',
            'reportesPorEstado',
            'reportesPorTipo'
        ));
    }

    /**
     * Mostrar detalle de reporte especial
     */
    public function show(ReporteEspecial $reporteEspecial)
    {
        $reporteEspecial->load(['user', 'sucursal', 'sector']);
        return view('admin.reportes-especiales.show', compact('reporteEspecial'));
    }

    /**
     * Actualizar estado del reporte
     */
    public function updateEstado(Request $request, ReporteEspecial $reporteEspecial)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_revision,completado,rechazado',
            'comentarios_admin' => 'nullable|string',
        ]);

        $reporteEspecial->update($validated);

        return redirect()->route('admin.reportes-especiales.show', $reporteEspecial)
                        ->with('success', 'Estado actualizado exitosamente.');
    }
}

