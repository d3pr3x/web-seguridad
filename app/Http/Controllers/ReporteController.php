<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Tarea;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReporteController extends Controller
{
    /**
     * Procesar envío de reporte
     */
    public function store(Request $request)
    {
        $request->validate([
            'tarea_id' => 'required|exists:tareas,id',
            'datos' => 'required|array',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tarea = Tarea::findOrFail($request->tarea_id);
        $usuario = auth()->user();
        
        // Procesar imágenes si existen
        $imagenes = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $nombreArchivo = Str::uuid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = $imagen->storeAs('reportes', $nombreArchivo, 'public');
                $imagenes[] = $ruta;
            }
        }

        // Crear reporte
        $reporte = Reporte::create([
            'user_id' => $usuario->id,
            'tarea_id' => $tarea->id,
            'datos' => $request->datos,
            'imagenes' => $imagenes,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('dashboard')->with('success', 'Reporte enviado exitosamente.');
    }

    /**
     * Mostrar reportes del usuario
     */
    public function index()
    {
        $usuario = auth()->user();
        $reportes = Reporte::with('tarea')
            ->where('user_id', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reportes.index', compact('reportes'));
    }

    /**
     * Mostrar detalle de reporte
     */
    public function show($id)
    {
        $reporte = Reporte::with(['tarea', 'user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('reportes.show', compact('reporte'));
    }
}
