<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accion;
use App\Models\Sector;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;

class NovedadController extends Controller
{
    /**
     * Mostrar listado completo de novedades (Admin / Supervisor). Punto 9: jefe de turno solo ve su instalación.
     */
    public function index(Request $request)
    {
        $query = Accion::with(['user', 'sucursal', 'sector']);

        // Punto 9: jefe de turno (SUPERVISOR_USUARIO) solo ve su sucursal/instalación
        $user = auth()->user();
        if ($user->esSupervisorUsuario() && $user->sucursal_id) {
            $query->where('sucursal_id', $user->sucursal_id);
        }

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

        // Punto 1: filtrar por tipo de hecho
        if ($request->filled('tipo_hecho')) {
            $query->porTipoHecho($request->tipo_hecho);
        }

        // Punto 15: filtrar por importancia (importante / cotidiana)
        if ($request->filled('importancia')) {
            $query->porImportancia($request->importancia);
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
        $hechos = Accion::hechos();
        $nivelesImportancia = Accion::nivelesImportancia();

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
            'hechos',
            'nivelesImportancia',
            'totalAcciones',
            'accionesPorTipo'
        ));
    }

    /**
     * Formulario para que el administrador registre una novedad/incidente (ve todo, puede crear todo).
     */
    public function create()
    {
        $sucursales = Sucursal::activas()->orderBy('nombre')->get();
        $sectores = Sector::with('sucursal')->where('activo', true)->orderBy('sucursal_id')->orderBy('nombre')->get()->groupBy('sucursal_id');
        $usuarios = User::orderBy('nombre_completo')->get();
        $tipos = Accion::tipos();
        $hechos = Accion::hechos();
        $nivelesImportancia = Accion::nivelesImportancia();

        return view('admin.novedades.create', compact(
            'sucursales', 'sectores', 'usuarios', 'tipos', 'hechos', 'nivelesImportancia'
        ));
    }

    /**
     * Guardar novedad/incidente creada por el administrador.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:inicio_servicio,rondas,constancias,concurrencia_autoridades,concurrencia_servicios,entrega_servicio',
            'tipo_hecho' => 'nullable|in:incidente,observacion,informacion,delito,accidente',
            'importancia' => 'nullable|in:cotidiana,importante,critica',
            'sucursal_id' => 'required|exists:sucursales,id',
            'sector_id' => 'nullable|exists:sectores,id',
            'id_usuario' => 'nullable|exists:usuarios,id_usuario',
            'dia' => 'required|date',
            'hora' => 'required',
            'novedad' => 'nullable|string',
            'accion' => 'nullable|string',
            'resultado' => 'nullable|string',
            'imagenes' => 'nullable|array|max:4',
            'imagenes.*' => 'image|mimes:jpeg,jpg,png,heic,heif|max:15360',
        ], [
            'imagenes.max' => 'Máximo 4 fotografías.',
            'imagenes.*.max' => 'Cada imagen máximo 15MB.',
        ]);

        $imagenes = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $imagenes[] = $imagen->store('acciones', 'public');
            }
        }

        // Si no se asigna usuario, se usa el admin que registra
        $idUsuario = !empty($validated['id_usuario']) ? $validated['id_usuario'] : auth()->id();

        Accion::create([
            'id_usuario' => $idUsuario,
            'sucursal_id' => $validated['sucursal_id'],
            'sector_id' => $validated['sector_id'] ?? null,
            'tipo' => $validated['tipo'],
            'tipo_hecho' => $validated['tipo_hecho'] ?? null,
            'importancia' => $validated['importancia'] ?? null,
            'dia' => $validated['dia'],
            'hora' => $validated['hora'],
            'novedad' => $validated['novedad'] ?? null,
            'accion' => $validated['accion'] ?? null,
            'resultado' => $validated['resultado'] ?? null,
            'imagenes' => $imagenes,
        ]);

        return redirect()->route('admin.novedades.index')
            ->with('success', 'Novedad registrada correctamente. Puede filtrar por importancia para ver solo las relevantes.');
    }

    /**
     * Mostrar detalle de novedad
     */
    public function show(Accion $accion)
    {
        $accion->load(['user', 'sucursal', 'sector', 'reporteEspecial']);
        return view('admin.novedades.show', compact('accion'));
    }

    /**
     * Punto 6: Elevar novedad (acción) a reporte formal. Solo jefe de turno o superior.
     */
    public function elevarAReporte(Request $request, Accion $accion)
    {
        $user = auth()->user();
        if (!$user->esSupervisorUsuario() && !$user->esSupervisor() && !$user->esAdministrador()) {
            abort(403, 'Solo el jefe de turno o supervisión pueden elevar una novedad a reporte.');
        }
        if ($accion->reporteEspecial) {
            return redirect()->route('admin.reportes-especiales.show', $accion->reporteEspecial)
                ->with('info', 'Esta novedad ya fue elevada a reporte.');
        }

        $reporte = \App\Models\ReporteEspecial::create([
            'id_usuario' => $accion->id_usuario,
            'accion_id' => $accion->id,
            'sucursal_id' => $accion->sucursal_id,
            'sector_id' => $accion->sector_id,
            'tipo' => 'incidentes',
            'dia' => $accion->dia,
            'hora' => $accion->hora,
            'novedad' => $accion->novedad,
            'accion' => $accion->accion,
            'resultado' => $accion->resultado,
            'imagenes' => $accion->imagenes,
            'latitud' => $accion->latitud,
            'longitud' => $accion->longitud,
            'precision' => $accion->precision,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('admin.reportes-especiales.show', $reporte)
            ->with('success', 'Novedad elevada a reporte correctamente.');
    }
}

