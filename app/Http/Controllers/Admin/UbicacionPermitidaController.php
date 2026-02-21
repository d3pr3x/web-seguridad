<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UbicacionPermitida;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class UbicacionPermitidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ubicaciones = UbicacionPermitida::with('sucursal')
            ->orderBy('activa', 'desc')
            ->orderBy('nombre', 'asc')
            ->paginate(15);
        
        return view('admin.ubicaciones.index', compact('ubicaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sucursales = Sucursal::orderBy('nombre')->get();
        return view('admin.ubicaciones.create', compact('sucursales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'radio' => 'required|integer|min:1|max:1000',
            'descripcion' => 'nullable|string',
            'sucursal_id' => 'nullable|exists:sucursales,id',
        ]);

        UbicacionPermitida::create([
            'nombre' => $request->nombre,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'radio' => $request->radio,
            'activa' => true,
            'descripcion' => $request->descripcion,
            'sucursal_id' => $request->sucursal_id,
        ]);

        return redirect()->route('admin.ubicaciones.index')
                        ->with('success', 'Ubicación agregada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UbicacionPermitida $ubicacion)
    {
        $ubicacion->load('sucursal');
        return view('admin.ubicaciones.show', compact('ubicacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UbicacionPermitida $ubicacion)
    {
        $sucursales = Sucursal::orderBy('nombre')->get();
        return view('admin.ubicaciones.edit', compact('ubicacion', 'sucursales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UbicacionPermitida $ubicacion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'radio' => 'required|integer|min:1|max:1000',
            'descripcion' => 'nullable|string',
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'activa' => 'boolean',
        ]);

        $ubicacion->update([
            'nombre' => $request->nombre,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'radio' => $request->radio,
            'activa' => $request->has('activa'),
            'descripcion' => $request->descripcion,
            'sucursal_id' => $request->sucursal_id,
        ]);

        return redirect()->route('admin.ubicaciones.index')
                        ->with('success', 'Ubicación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UbicacionPermitida $ubicacion)
    {
        $ubicacion->delete();

        return redirect()->route('admin.ubicaciones.index')
                        ->with('success', 'Ubicación eliminada exitosamente.');
    }

    /**
     * Toggle the active status of a ubicacion
     */
    public function toggle(UbicacionPermitida $ubicacion)
    {
        $antes = ['activa' => $ubicacion->activa];
        $ubicacion->update(['activa' => !$ubicacion->activa]);
        \App\Services\AuditoriaService::registrar('toggle_activo', 'ubicaciones_permitidas', $ubicacion->id, $antes, $ubicacion->only('activa'), ['nombre' => $ubicacion->nombre]);

        $status = $ubicacion->activa ? 'activada' : 'desactivada';
        
        return redirect()->route('admin.ubicaciones.index')
                        ->with('success', "Ubicación {$status} exitosamente.");
    }
}
