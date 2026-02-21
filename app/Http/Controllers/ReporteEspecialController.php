<?php

namespace App\Http\Controllers;

use App\Models\ReporteEspecial;
use App\Models\Sector;
use App\Services\SecureUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteEspecialController extends Controller
{
    /**
     * Mostrar listado de reportes especiales
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ReporteEspecial::with(['user', 'sucursal', 'sector']);

        // Si es guardia, solo ve sus propios reportes
        if ($user->esGuardiaControlAcceso()) {
            $query->where('id_usuario', $user->id_usuario);
        } elseif ($user->esSupervisor() && $user->sucursal_id) {
            // Si es supervisor, solo ve reportes de su sucursal
            $query->where('sucursal_id', $user->sucursal_id);
        }

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
                          ->paginate(20);

        return view('reportes-especiales.index', compact('reportes'));
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

        return view('reportes-especiales.create', compact('tipo', 'sectores', 'tipos'));
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

        $upload = app(SecureUploadService::class);
        $imagenes = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $imagenes[] = $upload->storeImage($imagen, 'reportes-especiales');
            }
        }

        $reporte = ReporteEspecial::create([
            'id_usuario' => $user->id_usuario,
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

        return redirect()->route('reportes-especiales.show', $reporte)
                        ->with('success', 'Reporte registrado exitosamente.');
    }

    /**
     * Mostrar detalle de reporte especial
     */
    public function show(ReporteEspecial $reporteEspecial)
    {
        $user = Auth::user();

        // Verificar permisos
        if ($user->esGuardiaControlAcceso() && $reporteEspecial->id_usuario !== $user->id_usuario) {
            abort(403, 'No tienes permiso para ver este reporte.');
        }

        if ($user->esSupervisor() && $reporteEspecial->sucursal_id !== $user->sucursal_id) {
            abort(403, 'No tienes permiso para ver este reporte.');
        }

        $reporteEspecial->load(['user', 'sucursal', 'sector']);

        return view('reportes-especiales.show', compact('reporteEspecial'));
    }

    /**
     * Actualizar estado del reporte (solo admin)
     */
    public function updateEstado(Request $request, ReporteEspecial $reporteEspecial)
    {
        $user = Auth::user();

        // Punto 3: ADMIN siempre puede modificar; supervisores también
        if (!$user->esAdministrador() && !$user->esSupervisor()) {
            abort(403, 'No tienes permiso para actualizar el estado.');
        }

        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_revision,completado,rechazado',
            'comentarios_admin' => 'nullable|string',
        ]);

        $reporteEspecial->update($validated);

        return redirect()->route('reportes-especiales.show', $reporteEspecial)
                        ->with('success', 'Estado actualizado exitosamente.');
    }
}




