<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PuntoRonda;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PuntoRondaController extends Controller
{
    private function authorizeAdmin(): void
    {
        if (!auth()->user()?->esAdministrador()) {
            abort(403, 'Solo administradores pueden gestionar puntos de ronda.');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();
        $sucursales = Sucursal::withCount('puntosRonda')
            ->orderBy('nombre')
            ->paginate(15);

        return view('admin.rondas.index', compact('sucursales'));
    }

    public function show(Sucursal $sucursal)
    {
        $this->authorizeAdmin();
        $puntos = $sucursal->puntosRonda()
            ->with('sector')
            ->orderBy('orden')
            ->orderBy('nombre')
            ->paginate(20);

        return view('admin.rondas.show', compact('sucursal', 'puntos'));
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin();
        $sucursalId = $request->get('sucursal_id');
        if (!$sucursalId) {
            return redirect()->route('admin.rondas.index')
                ->with('error', 'Debe seleccionar una sucursal.');
        }
        $sucursal = Sucursal::findOrFail($sucursalId);
        $sectores = $sucursal->sectores()->activos()->orderBy('nombre')->get();

        return view('admin.rondas.create', compact('sucursal', 'sectores'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'sector_id' => 'nullable|exists:sectores,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer|min:0',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'distancia_maxima_metros' => 'nullable|integer|min:1|max:500',
        ]);

        $validated['codigo'] = strtoupper(Str::random(12));
        $validated['activo'] = $request->boolean('activo', true);
        $validated['orden'] = $validated['orden'] ?? 0;
        $validated['lat'] = isset($validated['lat']) ? (float) $validated['lat'] : null;
        $validated['lng'] = isset($validated['lng']) ? (float) $validated['lng'] : null;
        $validated['distancia_maxima_metros'] = isset($validated['distancia_maxima_metros']) && $validated['distancia_maxima_metros'] > 0
            ? (int) $validated['distancia_maxima_metros']
            : 10;

        PuntoRonda::create($validated);

        return redirect()->route('admin.rondas.show', $validated['sucursal_id'])
            ->with('success', 'Punto de ronda creado. Código QR: ' . $validated['codigo']);
    }

    public function edit(PuntoRonda $punto)
    {
        $this->authorizeAdmin();
        $sucursal = $punto->sucursal;
        $sectores = $sucursal->sectores()->activos()->orderBy('nombre')->get();

        return view('admin.rondas.edit', compact('punto', 'sucursal', 'sectores'));
    }

    public function update(Request $request, PuntoRonda $punto)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'sector_id' => 'nullable|exists:sectores,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer|min:0',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'distancia_maxima_metros' => 'nullable|integer|min:1|max:500',
        ]);

        $validated['activo'] = $request->boolean('activo', true);
        $validated['orden'] = $validated['orden'] ?? 0;
        $validated['lat'] = isset($validated['lat']) && $validated['lat'] !== '' ? (float) $validated['lat'] : null;
        $validated['lng'] = isset($validated['lng']) && $validated['lng'] !== '' ? (float) $validated['lng'] : null;
        $validated['distancia_maxima_metros'] = isset($validated['distancia_maxima_metros']) && $validated['distancia_maxima_metros'] > 0
            ? (int) $validated['distancia_maxima_metros']
            : 10;

        $punto->update($validated);

        return redirect()->route('admin.rondas.show', $punto->sucursal_id)
            ->with('success', 'Punto de ronda actualizado.');
    }

    public function destroy(PuntoRonda $punto)
    {
        $this->authorizeAdmin();
        $sucursalId = $punto->sucursal_id;
        $punto->delete();

        return redirect()->route('admin.rondas.show', $sucursalId)
            ->with('success', 'Punto de ronda eliminado.');
    }
}
