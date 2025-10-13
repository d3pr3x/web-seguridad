<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    /**
     * Mostrar listado de sucursales para gestionar sectores
     */
    public function index()
    {
        $sucursales = Sucursal::withCount('sectores')
            ->orderBy('nombre')
            ->paginate(15);
            
        return view('admin.sectores.index', compact('sucursales'));
    }

    /**
     * Mostrar sectores de una sucursal específica
     */
    public function show(Sucursal $sucursal)
    {
        $sectores = $sucursal->sectores()
            ->orderBy('nombre')
            ->paginate(15);
            
        return view('admin.sectores.show', compact('sucursal', 'sectores'));
    }

    /**
     * Mostrar formulario para crear sector en una sucursal
     */
    public function create(Request $request)
    {
        $sucursalId = $request->get('sucursal_id');
        
        if (!$sucursalId) {
            return redirect()->route('admin.sectores.index')
                ->with('error', 'Debe seleccionar una sucursal primero.');
        }
        
        $sucursal = Sucursal::findOrFail($sucursalId);
        
        return view('admin.sectores.create', compact('sucursal'));
    }

    /**
     * Guardar nuevo sector
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $validated['activo'] = $request->has('activo') ? true : false;

        Sector::create($validated);

        return redirect()->route('admin.sectores.show', $validated['sucursal_id'])
            ->with('success', 'Sector creado exitosamente.');
    }

    /**
     * Mostrar formulario para editar sector
     */
    public function edit(Sector $sector)
    {
        $sucursal = $sector->sucursal;
        
        return view('admin.sectores.edit', compact('sector', 'sucursal'));
    }

    /**
     * Actualizar sector
     */
    public function update(Request $request, Sector $sector)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $validated['activo'] = $request->has('activo') ? true : false;

        $sector->update($validated);

        return redirect()->route('admin.sectores.show', $sector->sucursal_id)
            ->with('success', 'Sector actualizado exitosamente.');
    }

    /**
     * Eliminar sector
     */
    public function destroy(Sector $sector)
    {
        $sucursalId = $sector->sucursal_id;
        $sector->delete();

        return redirect()->route('admin.sectores.show', $sucursalId)
            ->with('success', 'Sector eliminado exitosamente.');
    }

    /**
     * Activar/desactivar sector
     */
    public function toggle(Sector $sector)
    {
        $sector->update(['activo' => !$sector->activo]);

        $status = $sector->activo ? 'activado' : 'desactivado';
        
        return redirect()->route('admin.sectores.show', $sector->sucursal_id)
            ->with('success', "Sector {$status} exitosamente.");
    }
}



