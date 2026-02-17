<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Blacklist;
use App\Models\Persona;
use App\Rules\ChileRut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class IngresosController extends Controller
{
    /**
     * Listado de ingresos (paginado).
     */
    public function index(Request $request)
    {
        $query = Ingreso::with('guardia')->latest('fecha_ingreso');

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_ingreso', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_ingreso', '<=', $request->fecha_hasta);
        }
        if ($request->filled('id_guardia')) {
            $query->where('id_guardia', $request->id_guardia);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        // Punto 12: filtro por RUT
        if ($request->filled('rut')) {
            $rut = trim($request->rut);
            $query->where(function ($q) use ($rut) {
                $q->where('rut', 'like', '%' . $rut . '%');
                if (Schema::hasColumn('ingresos', 'pasaporte')) {
                    $q->orWhere('pasaporte', 'like', '%' . $rut . '%');
                }
            });
        }

        $ingresos = $query->paginate(50)->withQueryString();

        return view('ingresos.listado', compact('ingresos'));
    }

    /**
     * Vista solo para leer QR del carnet (cédula). Misma vista que ingresos/escaner (Capturar y leer) con registro automático.
     * URL: /qr-automatico
     */
    public function qrAutomatico()
    {
        return view('ingresos.escaner', ['modo_qr_automatico' => true]);
    }

    /**
     * Vista del escáner (peatonal/vehicular). Si config('app.ingresos_entrada_manual_solo') es true, redirige a entrada manual.
     */
    public function escaner()
    {
        if (config('app.ingresos_entrada_manual_solo', true)) {
            return redirect()->route('ingresos.entrada-manual');
        }
        return view('ingresos.escaner');
    }

    /**
     * Vista de entrada manual (sin escáner QR ni lector de patente). Registrar ingreso con RUT/nombre o patente.
     */
    public function entradaManual()
    {
        return view('ingresos.entrada-manual');
    }

    /**
     * API: buscar persona por RUT para completar nombre en el escáner (base de personas).
     */
    public function buscarPersona(Request $request)
    {
        $rut = $request->query('rut');
        if (!$rut || strlen(trim($rut)) < 8) {
            return response()->json(['found' => false]);
        }
        $persona = Persona::buscarPorRut(trim($rut));
        if (!$persona) {
            return response()->json(['found' => false]);
        }
        return response()->json([
            'found' => true,
            'nombre' => $persona->nombre,
            'telefono' => $persona->telefono,
            'email' => $persona->email,
            'empresa' => $persona->empresa,
        ]);
    }

    /**
     * Detalle de un ingreso (solo lectura; muestra QR de salida para reimprimir).
     */
    public function show(int $id)
    {
        $ingreso = Ingreso::with('guardia')->findOrFail($id);
        $qrSalidaUrl = route('ingresos.qr-salida', ['id' => $ingreso->id]);

        return view('ingresos.show', compact('ingreso', 'qrSalidaUrl'));
    }

    /**
     * Registrar ingreso (peatonal o vehicular). Valida blacklist y devuelve JSON.
     */
    public function store(Request $request)
    {
        $rut = $request->rut ? $this->normalizarRut($request->rut) : null;
        $nombre = $request->nombre ?? '';
        $patente = $request->filled('patente') ? strtoupper(preg_replace('/\s+/', '', $request->patente)) : null;

        if ($request->filled('qr_data')) {
            $parsed = $this->parseQrCedula($request->qr_data);
            if ($parsed) {
                $rut = $parsed['rut'] ?? $rut;
                $nombre = $parsed['nombre'] ?? $nombre;
            }
        }

        $rules = [
            'tipo' => 'required|in:peatonal,vehicular',
            'rut' => ['nullable', 'required_if:tipo,peatonal', 'string', 'max:12', new ChileRut],
            'nombre' => 'nullable|string|max:100',
            'patente' => ['nullable', 'required_if:tipo,vehicular', 'regex:/^[A-Z]{3,4}\d{2,3}$/i', 'max:10'],
        ];
        $validated = $request->validate($rules);

        $rut = $rut ?? $this->normalizarRut($validated['rut'] ?? '');
        $nombre = $nombre ?: ($validated['nombre'] ?? '');
        $patente = $patente ?? (isset($validated['patente']) ? strtoupper($validated['patente']) : null);

        $blacklistHit = Blacklist::activos()
            ->where(function ($q) use ($rut, $patente) {
                if ($rut) {
                    $q->where('rut', $rut);
                }
                if ($patente) {
                    $q->orWhere('patente', $patente);
                }
            })
            ->first();

        if ($rut && trim($nombre) !== '') {
            Persona::registrarOActualizar($rut, trim($nombre));
        }

        if ($blacklistHit) {
            $ingreso = Ingreso::create([
                'tipo' => $request->tipo,
                'rut' => $rut,
                'nombre' => $nombre,
                'patente' => $patente,
                'id_guardia' => auth()->id(),
                'estado' => 'bloqueado',
                'alerta_blacklist' => true,
                'ip_ingreso' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Log::channel('single')->warning('Ingreso bloqueado por blacklist', [
                'ingreso_id' => $ingreso->id,
                'rut' => $rut,
                'patente' => $patente,
                'motivo' => $blacklistHit->motivo,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Acceso bloqueado: persona o vehículo en lista restringida.',
                'motivo' => $blacklistHit->motivo,
                'ingreso_id' => $ingreso->id,
            ], 422);
        }

        $ingreso = Ingreso::create([
            'tipo' => $request->tipo,
            'rut' => $rut,
            'nombre' => $nombre,
            'patente' => $patente,
            'id_guardia' => auth()->id(),
            'estado' => 'ingresado',
            'alerta_blacklist' => false,
            'ip_ingreso' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $qrSalidaUrl = route('ingresos.qr-salida', ['id' => $ingreso->id]);

        return response()->json([
            'success' => true,
            'message' => 'Ingreso registrado correctamente.',
            'ingreso_id' => $ingreso->id,
            'qr_salida_url' => $qrSalidaUrl,
        ]);
    }

    /**
     * Registrar salida de un ingreso.
     */
    public function salida(int $id)
    {
        $ingreso = Ingreso::findOrFail($id);
        $ingreso->update([
            'fecha_salida' => now(),
            'estado' => 'salida',
        ]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Salida registrada.']);
        }

        return redirect()->route('ingresos.index')->with('success', 'Salida registrada.');
    }

    /**
     * Página o recurso QR para registrar salida (escaneo del QR de salida).
     */
    public function qrSalida(int $id)
    {
        $ingreso = Ingreso::findOrFail($id);

        if ($ingreso->estado !== 'ingresado') {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Este ingreso ya tiene salida registrada.'], 422);
            }
            return redirect()->route('ingresos.index')->with('error', 'Este ingreso ya tiene salida registrada.');
        }

        $ingreso->update([
            'fecha_salida' => now(),
            'estado' => 'salida',
        ]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Salida registrada.']);
        }

        return redirect()->route('ingresos.index')->with('success', 'Salida registrada correctamente.');
    }

    /**
     * Exportar ingresos a CSV.
     */
    public function exportarCsv(Request $request)
    {
        $query = Ingreso::with('guardia')->latest('fecha_ingreso');

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_ingreso', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_ingreso', '<=', $request->fecha_hasta);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $ingresos = $query->limit(10000)->get();

        $filename = 'ingresos_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($ingresos) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Fecha ingreso', 'Fecha salida', 'Tipo', 'RUT', 'Nombre', 'Patente', 'Guardia', 'Estado', 'Alerta blacklist'], ';');
            foreach ($ingresos as $i) {
                fputcsv($out, [
                    $i->id,
                    $i->fecha_ingreso?->format('Y-m-d H:i:s'),
                    $i->fecha_salida?->format('Y-m-d H:i:s'),
                    $i->tipo,
                    $i->rut,
                    $i->nombre,
                    $i->patente ?? '',
                    $i->guardia?->nombre_completo ?? '',
                    $i->estado,
                    $i->alerta_blacklist ? 'Sí' : 'No',
                ], ';');
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function normalizarRut(string $rut): string
    {
        $rut = preg_replace('/[^0-9kK]/', '', strtoupper($rut));
        if (strlen($rut) >= 2) {
            $rut = substr($rut, 0, -1) . '-' . substr($rut, -1);
        }
        return $rut;
    }

    /**
     * Parsear datos QR cédula Chile (formato típico RUT|Nombre|DV|FechaNacimiento o similar).
     */
    private function parseQrCedula(string $qrData): ?array
    {
        $parts = array_map('trim', explode('|', $qrData));
        if (count($parts) < 2) {
            return null;
        }
        $rut = $this->normalizarRut($parts[0]);
        $nombre = $parts[1] ?? '';
        return ['rut' => $rut, 'nombre' => $nombre];
    }

}
