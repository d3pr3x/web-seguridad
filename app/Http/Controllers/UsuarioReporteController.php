<?php

namespace App\Http\Controllers;

use App\Models\ReporteEspecial;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioReporteController extends Controller
{
    /**
     * Mostrar listado de reportes especiales del usuario
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = ReporteEspecial::with(['sucursal', 'sector'])
                                ->where('user_id', $user->id);

        // Filtros
        if ($request->filled('tipo')) {
            $query->porTipo($request->tipo);
        }
        
        if ($request->filled('estado')) {
            $query->porEstado($request->estado);
        }
        
        if ($request->filled('fecha')) {
            $query->porFecha($request->fecha);
        }

        $reportes = $query->orderBy('dia', 'desc')
                          ->orderBy('hora', 'desc')
                          ->paginate(15);

        return view('usuario.reportes.index', compact('reportes'));
    }

    /**
     * Mostrar formulario para crear reporte especial
     */
    public function create(Request $request)
    {
        $tipo = $request->tipo ?? 'incidentes';
        $user = Auth::user();
        
        // Obtener sectores de la sucursal del usuario
        $sectores = [];
        if ($user->sucursal_id) {
            $sectores = Sector::where('sucursal_id', $user->sucursal_id)
                             ->activos()
                             ->orderBy('nombre')
                             ->get();
        }

        $tipos = ReporteEspecial::tipos();

        return view('usuario.reportes.create', compact('tipo', 'sectores', 'tipos'));
    }

    /**
     * Guardar nuevo reporte especial
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'tipo' => 'required|in:incidentes,denuncia,detenido,accion_sospechosa',
            'dia' => 'required|date',
            'hora' => 'required',
            'sector_id' => 'nullable|exists:sectores,id',
            'novedad' => 'required|string',
            'accion' => 'required|string',
            'resultado' => 'nullable|string',
            'imagenes' => 'nullable|array|max:4',
            'imagenes.*' => 'image|mimes:jpeg,jpg,png,heic,heif|max:15360',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'precision' => 'nullable|numeric',
        ], [
            'imagenes.max' => 'Puede subir un máximo de 4 fotografías.',
            'imagenes.*.max' => 'Cada imagen no debe superar los 15MB.',
            'imagenes.*.mimes' => 'Solo se aceptan imágenes en formato JPG, PNG o HEIC.',
        ]);

        // Manejo de imágenes
        $imagenes = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('reportes-especiales', 'public');
                $imagenes[] = $path;
            }
        }

        $reporte = ReporteEspecial::create([
            'user_id' => $user->id,
            'sucursal_id' => $user->sucursal_id,
            'tipo' => $validated['tipo'],
            'dia' => $validated['dia'],
            'hora' => $validated['hora'],
            'sector_id' => $validated['sector_id'] ?? null,
            'novedad' => $validated['novedad'],
            'accion' => $validated['accion'],
            'resultado' => $validated['resultado'] ?? null,
            'imagenes' => $imagenes,
            'latitud' => $validated['latitud'] ?? null,
            'longitud' => $validated['longitud'] ?? null,
            'precision' => $validated['precision'] ?? null,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('usuario.reportes.show', $reporte)
                        ->with('success', 'Reporte registrado exitosamente.');
    }

    /**
     * Mostrar detalle de reporte especial
     */
    public function show(ReporteEspecial $reporteEspecial)
    {
        $user = Auth::user();

        // Verificar que el reporte pertenece al usuario
        if ($reporteEspecial->user_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este reporte.');
        }

        $reporteEspecial->load(['sucursal', 'sector']);

        return view('usuario.reportes.show', compact('reporteEspecial'));
    }
}


