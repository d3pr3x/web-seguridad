<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Tarea;
use App\Services\SecureUploadService;
use Illuminate\Http\Request;

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
                'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:' . (config('uploads.max_image_kb', 5120)),
                'latitud' => 'nullable|numeric|between:-90,90',
                'longitud' => 'nullable|numeric|between:-180,180',
                'precision' => 'nullable|numeric|min:0',
            ], [
                'imagenes.max' => 'Solo puedes subir un máximo de 5 imágenes.',
                'imagenes.*.image' => 'Todos los archivos deben ser imágenes.',
                'imagenes.*.mimes' => 'Las imágenes deben ser de tipo: jpeg, png, jpg, gif o webp.',
                'imagenes.*.max' => 'Cada imagen no debe superar ' . (config('uploads.max_image_kb', 5120) / 1024) . ' MB.',
            ]);

            $tarea = Tarea::findOrFail($request->tarea_id);
            $usuario = auth()->user();
            
            $upload = app(SecureUploadService::class);
            $imagenes = [];
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $imagen) {
                    if ($imagen->isValid()) {
                        $imagenes[] = $upload->storeImage($imagen, 'reportes');
                    }
                }
            }

            // Crear reporte
            $reporte = Reporte::create([
                'id_usuario' => $usuario->id_usuario,
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
            ->where('id_usuario', $usuario->id_usuario)
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
            ->where('id_usuario', auth()->id())
            ->firstOrFail();

        return view('reportes.show', compact('reporte'));
    }
}
