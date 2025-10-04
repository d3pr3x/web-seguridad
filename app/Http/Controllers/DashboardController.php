<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\DiaTrabajado;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal
     */
    public function index()
    {
        $tareas = Tarea::activas()->get();
        $usuario = auth()->user();
        
        // Obtener días trabajados del mes actual
        $mesActual = Carbon::now()->format('Y-m');
        $diasTrabajados = DiaTrabajado::where('user_id', $usuario->id)
            ->whereRaw("TO_CHAR(fecha, 'YYYY-MM') = ?", [$mesActual])
            ->get();
        
        $totalDias = $diasTrabajados->sum('ponderacion');
        
        return view('dashboard', compact('tareas', 'diasTrabajados', 'totalDias'));
    }
}
