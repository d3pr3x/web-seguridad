<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DispositivoPermitido;
use Illuminate\Http\Request;

class DispositivoPermitidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dispositivos = DispositivoPermitido::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.dispositivos.index', compact('dispositivos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dispositivos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'browser_fingerprint' => 'required|string|max:255|unique:dispositivos_permitidos,browser_fingerprint',
            'descripcion' => 'required|string|max:255',
            'requiere_ubicacion' => 'nullable|boolean',
        ]);

        DispositivoPermitido::create([
            'browser_fingerprint' => $request->browser_fingerprint,
            'descripcion' => $request->descripcion,
            'activo' => true,
            'requiere_ubicacion' => $request->has('requiere_ubicacion'),
        ]);

        return redirect()->route('admin.dispositivos.index')
                        ->with('success', 'Dispositivo agregado exitosamente. ' . 
                               ($request->has('requiere_ubicacion') ? 'Requerirá validación GPS.' : 'Puede acceder desde cualquier ubicación.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(DispositivoPermitido $dispositivo)
    {
        return view('admin.dispositivos.show', compact('dispositivo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DispositivoPermitido $dispositivo)
    {
        return view('admin.dispositivos.edit', compact('dispositivo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DispositivoPermitido $dispositivo)
    {
        $request->validate([
            'browser_fingerprint' => 'required|string|max:255|unique:dispositivos_permitidos,browser_fingerprint,' . $dispositivo->id,
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ]);

        $dispositivo->update([
            'browser_fingerprint' => $request->browser_fingerprint,
            'descripcion' => $request->descripcion,
            'activo' => $request->has('activo'),
        ]);

        return redirect()->route('admin.dispositivos.index')
                        ->with('success', 'Dispositivo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DispositivoPermitido $dispositivo)
    {
        $dispositivo->delete();

        return redirect()->route('admin.dispositivos.index')
                        ->with('success', 'Dispositivo eliminado exitosamente.');
    }

    /**
     * Toggle the active status of a dispositivo
     */
    public function toggle(DispositivoPermitido $dispositivo)
    {
        $antes = ['activo' => $dispositivo->activo];
        $dispositivo->update(['activo' => !$dispositivo->activo]);
        \App\Services\AuditoriaService::registrar('toggle_activo', 'dispositivos_permitidos', $dispositivo->id, $antes, $dispositivo->only('activo'), ['descripcion' => $dispositivo->descripcion]);

        $status = $dispositivo->activo ? 'activado' : 'desactivado';
        
        return redirect()->route('admin.dispositivos.index')
                        ->with('success', "Dispositivo {$status} exitosamente.");
    }

    /**
     * Toggle the requiere_ubicacion status of a dispositivo
     */
    public function toggleUbicacion(DispositivoPermitido $dispositivo)
    {
        $dispositivo->update(['requiere_ubicacion' => !$dispositivo->requiere_ubicacion]);

        $status = $dispositivo->requiere_ubicacion ? 'requiere validación de ubicación' : 'puede acceder desde cualquier ubicación';
        
        return redirect()->route('admin.dispositivos.index')
                        ->with('success', "Dispositivo actualizado: ahora {$status}.");
    }
}
