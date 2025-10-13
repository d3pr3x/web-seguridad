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
        try {
            $request->validate([
                'tarea_id' => 'required|exists:tareas,id',
                'datos' => 'required|array',
                'imagenes' => 'nullable|array|max:5',
                'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
                'latitud' => 'nullable|numeric|between:-90,90',
                'longitud' => 'nullable|numeric|between:-180,180',
                'precision' => 'nullable|numeric|min:0',
            ], [
                'imagenes.max' => 'Solo puedes subir un máximo de 5 imágenes.',
                'imagenes.*.image' => 'Todos los archivos deben ser imágenes.',
                'imagenes.*.mimes' => 'Las imágenes deben ser de tipo: jpeg, png, jpg, gif o webp.',
                'imagenes.*.max' => 'Cada imagen no debe superar los 15MB.',
            ]);

            $tarea = Tarea::findOrFail($request->tarea_id);
            $usuario = auth()->user();
            
            // Procesar imágenes si existen
            $imagenes = [];
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $index => $imagen) {
                    if ($imagen->isValid()) {
                        $nombreArchivo = Str::uuid() . '.' . $imagen->getClientOriginalExtension();
                        $ruta = $imagen->storeAs('reportes', $nombreArchivo, 'public');
                        $imagenes[] = $ruta;
                    }
                }
            }

            // Crear reporte
            $reporte = Reporte::create([
                'user_id' => $usuario->id,
                'tarea_id' => $tarea->id,
                'datos' => $request->datos,
                'imagenes' => $imagenes,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'precision' => $request->precision,
                'estado' => 'pendiente',
            ]);

            return redirect()->to('/')->with('success', 'Reporte enviado exitosamente.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al subir reporte: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al procesar el reporte. Por favor, intenta nuevamente.')->withInput();
        }
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
