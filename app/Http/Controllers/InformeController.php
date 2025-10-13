<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Informe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class InformeController extends Controller
{
    /**
     * Mostrar lista de informes del usuario
     */
    public function index()
    {
        $userId = Auth::id();
        $informes = Informe::with(['reporte.tarea', 'reporte.user'])
            ->whereHas('reporte', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('informes.index', compact('informes'));
    }

    /**
     * Mostrar formulario para crear informe
     */
    public function create($reporteId)
    {
        $userId = Auth::id();
        $reporte = Reporte::with(['tarea', 'user'])
            ->where('id', $reporteId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return view('informes.create', compact('reporte'));
    }

    /**
     * Almacenar nuevo informe
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'reporte_id' => 'required|exists:reportes,id',
                'hora' => 'required|date_format:H:i',
                'descripcion' => 'required|string|max:1000',
                'lesionados' => 'required|string|max:500',
                'acciones_inmediatas' => 'required|array|min:1',
                'acciones_inmediatas.*' => 'string|max:255',
                'conclusiones' => 'required|array|min:1',
                'conclusiones.*' => 'string|max:255',
                'fotografias' => 'nullable|array|max:12',
                'fotografias.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            ], [
                'fotografias.max' => 'Solo puedes subir un máximo de 12 fotografías.',
                'fotografias.*.image' => 'Todos los archivos deben ser imágenes.',
                'fotografias.*.mimes' => 'Las fotografías deben ser de tipo: jpeg, png, jpg, gif o webp.',
                'fotografias.*.max' => 'Cada fotografía no debe superar los 15MB.',
                'acciones_inmediatas.required' => 'Debe seleccionar al menos una acción inmediata.',
                'conclusiones.required' => 'Debe seleccionar al menos una conclusión.',
            ]);

            // Verificar que el reporte pertenece al usuario
            $userId = Auth::id();
            $reporte = Reporte::where('id', $request->reporte_id)
                ->where('user_id', $userId)
                ->firstOrFail();

            // Validar total de fotografías (reporte base + nuevas)
            $fotosReporte = $reporte->imagenes ? count($reporte->imagenes) : 0;
            $fotosNuevas = $request->hasFile('fotografias') ? count($request->file('fotografias')) : 0;
            $totalFotos = $fotosReporte + $fotosNuevas;
            
            if ($totalFotos > 12) {
                return back()->withErrors([
                    'fotografias' => "El total de fotografías no puede exceder 12. Tienes {$fotosReporte} del reporte base y estás subiendo {$fotosNuevas} nuevas."
                ])->withInput();
            }

            // Combinar fotografías del reporte base con las nuevas
            $fotografias = [];
            
            // Primero agregar las fotografías del reporte base
            if ($reporte->imagenes && count($reporte->imagenes) > 0) {
                $fotografias = array_merge($fotografias, $reporte->imagenes);
            }
            
            // Luego agregar las nuevas fotografías del informe
            if ($request->hasFile('fotografias')) {
                foreach ($request->file('fotografias') as $index => $fotografia) {
                    if ($fotografia->isValid()) {
                        $nombreArchivo = Str::uuid() . '.' . $fotografia->getClientOriginalExtension();
                        $ruta = $fotografia->storeAs('informes/fotografias', $nombreArchivo, 'public');
                        $fotografias[] = $ruta;
                    }
                }
            }

            // Crear informe
            $informe = Informe::create([
                'reporte_id' => $request->reporte_id,
                'numero_informe' => $this->generarNumeroInforme(),
                'hora' => $request->hora,
                'descripcion' => $request->descripcion,
                'lesionados' => $request->lesionados,
                'acciones_inmediatas' => $request->acciones_inmediatas,
                'conclusiones' => $request->conclusiones,
                'fotografias' => $fotografias,
                'estado' => 'pendiente_revision',
            ]);

            return redirect()->route('informes.show', $informe->id)
                ->with('success', 'Informe generado exitosamente. Está pendiente de revisión.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error al generar informe: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al generar el informe. Por favor, intenta nuevamente.')->withInput();
        }
    }

    /**
     * Mostrar detalle del informe
     */
    public function show($id)
    {
        $userId = Auth::id();
        $informe = Informe::with(['reporte.tarea', 'reporte.user'])
            ->where('id', $id)
            ->whereHas('reporte', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

        return view('informes.show', compact('informe'));
    }

    /**
     * Generar PDF del informe
     */
    public function pdf($id)
    {
        $userId = Auth::id();
        $informe = Informe::with(['reporte.tarea', 'reporte.user'])
            ->where('id', $id)
            ->whereHas('reporte', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

        // Dividir fotografías en hojas de 4
        $fotografiasPorHoja = array_chunk($informe->fotografias, 4);
        
        $pdf = Pdf::loadView('informes.pdf', compact('informe', 'fotografiasPorHoja'));
        
        return $pdf->download('informe_incidente_' . $informe->numero_informe . '.pdf');
    }

    /**
     * Generar número de informe único
     */
    private function generarNumeroInforme()
    {
        $ultimoInforme = Informe::orderBy('numero_informe', 'desc')->first();
        $numero = $ultimoInforme ? $ultimoInforme->numero_informe + 1 : 1;
        
        return $numero;
    }

    /**
     * Obtener opciones predeterminadas para acciones inmediatas
     */
    public function getOpcionesAcciones()
    {
        return [
            'Se notificó al supervisor inmediatamente',
            'Se activó el protocolo de emergencia',
            'Se evacuó el área afectada',
            'Se contactó con servicios de emergencia',
            'Se tomó evidencia fotográfica del incidente',
            'Se aisló la zona de riesgo',
            'Se proporcionó primeros auxilios a los lesionados',
            'Se coordinó con el personal de seguridad',
            'Se documentó el incidente en el libro de novedades',
            'Se notificó a la gerencia de operaciones'
        ];
    }

    /**
     * Aprobar informe
     */
    public function aprobar(Request $request, $id)
    {
        $request->validate([
            'comentarios_aprobacion' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $informe = Informe::with(['reporte'])
            ->where('id', $id)
            ->whereHas('reporte', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

        if (!$informe->isPendienteRevision()) {
            return back()->with('error', 'Solo se pueden aprobar informes pendientes de revisión.');
        }

        $informe->update([
            'estado' => 'aprobado',
            'fecha_aprobacion' => now(),
            'aprobado_por' => auth()->user()->nombre_completo,
            'comentarios_aprobacion' => $request->comentarios_aprobacion,
        ]);

        return redirect()->route('informes.show', $informe->id)
            ->with('success', 'Informe aprobado exitosamente. Ya puede generar el PDF.');
    }

    /**
     * Rechazar informe
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'comentarios_aprobacion' => 'required|string|max:1000',
        ]);

        $userId = Auth::id();
        $informe = Informe::with(['reporte'])
            ->where('id', $id)
            ->whereHas('reporte', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

        if (!$informe->isPendienteRevision()) {
            return back()->with('error', 'Solo se pueden rechazar informes pendientes de revisión.');
        }

        $informe->update([
            'estado' => 'rechazado',
            'fecha_aprobacion' => now(),
            'aprobado_por' => auth()->user()->nombre_completo,
            'comentarios_aprobacion' => $request->comentarios_aprobacion,
        ]);

        return redirect()->route('informes.show', $informe->id)
            ->with('error', 'Informe rechazado. Puede editarlo y volver a enviarlo para revisión.');
    }

    /**
     * Volver a enviar informe para revisión
     */
    public function reenviar($id)
    {
        $userId = Auth::id();
        $informe = Informe::with(['reporte'])
            ->where('id', $id)
            ->whereHas('reporte', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

        if (!$informe->isRechazado()) {
            return back()->with('error', 'Solo se pueden reenviar informes rechazados.');
        }

        $informe->update([
            'estado' => 'pendiente_revision',
            'fecha_aprobacion' => null,
            'aprobado_por' => null,
            'comentarios_aprobacion' => null,
        ]);

        return redirect()->route('informes.show', $informe->id)
            ->with('success', 'Informe reenviado para revisión.');
    }

    /**
     * Obtener opciones predeterminadas para conclusiones
     */
    public function getOpcionesConclusiones()
    {
        return [
            'El incidente fue causado por factores humanos',
            'El incidente fue causado por condiciones ambientales',
            'El incidente fue causado por fallas en el equipo',
            'Se requieren medidas preventivas adicionales',
            'Se debe capacitar al personal involucrado',
            'Se deben revisar los procedimientos de seguridad',
            'El incidente no tuvo consecuencias graves',
            'Se requiere investigación adicional',
            'Se implementarán medidas correctivas inmediatas',
            'El personal actuó según los protocolos establecidos'
        ];
    }
}
