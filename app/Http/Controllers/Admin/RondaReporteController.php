<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RondaEscaneo;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;

class RondaReporteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user || (!$user->esAdministrador() && !$user->esSupervisor())) {
            abort(403, 'No tiene permisos para ver el reporte de escaneos.');
        }
        $sucursales = Sucursal::activas()->orderBy('nombre')->get();

        $query = RondaEscaneo::with(['puntoRonda.sucursal', 'user']);

        if ($request->filled('sucursal_id')) {
            $query->whereHas('puntoRonda', fn ($q) => $q->where('sucursal_id', $request->sucursal_id));
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('escaneado_en', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('escaneado_en', '<=', $request->fecha_hasta);
        }

        $escaneos = $query->orderByDesc('escaneado_en')->paginate(25)->withQueryString();

        $usuarios = collect();
        if ($request->filled('sucursal_id')) {
            $usuarios = User::where('sucursal_id', $request->sucursal_id)->orderBy('name')->get(['id', 'name', 'apellido']);
        }

        return view('admin.rondas.reporte', compact('sucursales', 'escaneos', 'usuarios'));
    }
}
