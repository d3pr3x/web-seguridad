<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accion;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;

class NovedadController extends Controller
{
    /**
     * Mostrar listado completo de novedades (Admin)
     */
    public function index(Request $request)
    {
        $query = Accion::with(['user', 'sucursal', 'sector']);

        // Filtros
        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        if ($request->filled('id_usuario')) {
            $query->where('id_usuario', $request->id_usuario);
        }

        if ($request->filled('tipo')) {
            $query->porTipo($request->tipo);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('dia', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('dia', '<=', $request->fecha_hasta);
        }

        $acciones = $query->orderBy('dia', 'desc')
                          ->orderBy('hora', 'desc')
                          ->paginate(20);

        // Datos para filtros
        $sucursales = Sucursal::activas()->orderBy('nombre')->get();
        $usuarios = User::orderBy('nombre_completo')->get();
        $tipos = Accion::tipos();

        // Estadísticas
        $totalAcciones = $query->count();
        $accionesPorTipo = Accion::selectRaw('tipo, count(*) as total')
            ->when($request->filled('sucursal_id'), function($q) use ($request) {
                return $q->where('sucursal_id', $request->sucursal_id);
            })
            ->groupBy('tipo')
            ->get()
            ->pluck('total', 'tipo');

        return view('admin.novedades.index', compact(
            'acciones', 
            'sucursales', 
            'usuarios', 
            'tipos',
            'totalAcciones',
            'accionesPorTipo'
        ));
    }

    /**
     * Mostrar detalle de novedad
     */
    public function show(Accion $accion)
    {
        $accion->load(['user', 'sucursal', 'sector']);
        return view('admin.novedades.show', compact('accion'));
    }
}

