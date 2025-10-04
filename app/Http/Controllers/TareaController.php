<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\TareaDetalle;

class TareaController extends Controller
{
    /**
     * Mostrar formulario de tarea específica
     */
    public function show($id)
    {
        $tarea = Tarea::with('detalles')->findOrFail($id);
        
        if (!$tarea->activa) {
            return redirect()->route('dashboard')->with('error', 'Esta tarea no está disponible.');
        }
        
        return view('tareas.formulario', compact('tarea'));
    }
}
