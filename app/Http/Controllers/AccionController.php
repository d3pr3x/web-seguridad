<?php

namespace App\Http\Controllers;

use App\Models\Accion;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccionController extends Controller
{
    /**
     * Mostrar listado de acciones
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Accion::with(['user', 'sucursal', 'sector']);

        // Si es guardia, solo ve sus propias acciones
        if ($user->esGuardiaControlAcceso()) {
            $query->where('id_usuario', $user->id_usuario);
        } elseif ($user->esSupervisor() && $user->sucursal_id) {
            // Si es supervisor, solo ve acciones de su sucursal
            $query->where('sucursal_id', $user->sucursal_id);
        }

        // Filtros
        if ($request->filled('tipo')) {
            $query->porTipo($request->tipo);
        }
        
        if ($request->filled('fecha')) {
            $query->porFecha($request->fecha);
        }

        $acciones = $query->orderBy('dia', 'desc')
                          ->orderBy('hora', 'desc')
                          ->paginate(20);

        return view('acciones.index', compact('acciones'));
    }

    /**
     * Mostrar formulario para crear acción
     */
    public function create(Request $request)
    {
        $tipo = $request->tipo ?? 'rondas';
        $user = Auth::user();
        
        // Obtener sectores de la sucursal del usuario
        $sectores = [];
        if ($user->sucursal_id) {
            $sectores = Sector::where('sucursal_id', $user->sucursal_id)
                             ->activos()
                             ->orderBy('nombre')
                             ->get();
        }

        $tipos = Accion::tipos();

        return view('acciones.create', compact('tipo', 'sectores', 'tipos'));
    }

    /**
     * Guardar nueva acción
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'tipo' => 'required|in:inicio_servicio,rondas,constancias,concurrencia_autoridades,concurrencia_servicios,entrega_servicio',
            'dia' => 'required|date',
            'hora' => 'required',
            'sector_id' => 'nullable|exists:sectores,id',
            'novedad' => 'nullable|string',
            'accion' => 'nullable|string',
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
                $path = $imagen->store('acciones', 'public');
                $imagenes[] = $path;
            }
        }

        $accion = Accion::create([
            'id_usuario' => $user->id_usuario,
            'sucursal_id' => $user->sucursal_id,
            'tipo' => $validated['tipo'],
            'dia' => $validated['dia'],
            'hora' => $validated['hora'],
            'sector_id' => $validated['sector_id'] ?? null,
            'novedad' => $validated['novedad'] ?? null,
            'accion' => $validated['accion'] ?? null,
            'resultado' => $validated['resultado'] ?? null,
            'imagenes' => $imagenes,
            'latitud' => $validated['latitud'] ?? null,
            'longitud' => $validated['longitud'] ?? null,
            'precision' => $validated['precision'] ?? null,
        ]);

        return redirect()->route('acciones.show', $accion)
                        ->with('success', 'Acción registrada exitosamente.');
    }

    /**
     * Mostrar detalle de acción
     */
    public function show(Accion $accion)
    {
        $user = Auth::user();

        // Verificar permisos
        if ($user->esGuardiaControlAcceso() && $accion->id_usuario !== $user->id_usuario) {
            abort(403, 'No tienes permiso para ver esta acción.');
        }

        if ($user->esSupervisor() && $accion->sucursal_id !== $user->sucursal_id) {
            abort(403, 'No tienes permiso para ver esta acción.');
        }

        $accion->load(['user', 'sucursal', 'sector']);

        return view('acciones.show', compact('accion'));
    }

    /**
     * Obtener sectores por sucursal (API)
     */
    public function sectoresPorSucursal($sucursalId)
    {
        $sectores = Sector::where('sucursal_id', $sucursalId)
                         ->activos()
                         ->orderBy('nombre')
                         ->get(['id', 'nombre']);

        return response()->json($sectores);
    }
}




