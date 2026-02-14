<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectorController extends Controller
{
    /**
     * Mostrar listado de sectores
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->esAdministrador()) {
            abort(403, 'No tienes permiso para administrar sectores.');
        }

        $sectores = Sector::with('sucursal')
                          ->orderBy('sucursal_id')
                          ->orderBy('nombre')
                          ->paginate(20);

        return view('sectores.index', compact('sectores'));
    }

    /**
     * Mostrar formulario para crear sector
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->esAdministrador()) {
            abort(403, 'No tienes permiso para crear sectores.');
        }

        $sucursales = Sucursal::activas()->orderBy('nombre')->get();

        return view('sectores.create', compact('sucursales'));
    }

    /**
     * Guardar nuevo sector
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->esAdministrador()) {
            abort(403, 'No tienes permiso para crear sectores.');
        }

        $validated = $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        Sector::create($validated);

        return redirect()->route('sectores.index')
                        ->with('success', 'Sector creado exitosamente.');
    }

    /**
     * Mostrar formulario para editar sector
     */
    public function edit(Sector $sector)
    {
        $user = Auth::user();

        if (!$user->esAdministrador()) {
            abort(403, 'No tienes permiso para editar sectores.');
        }

        $sucursales = Sucursal::activas()->orderBy('nombre')->get();

        return view('sectores.edit', compact('sector', 'sucursales'));
    }

    /**
     * Actualizar sector
     */
    public function update(Request $request, Sector $sector)
    {
        $user = Auth::user();

        if (!$user->esAdministrador()) {
            abort(403, 'No tienes permiso para editar sectores.');
        }

        $validated = $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $sector->update($validated);

        return redirect()->route('sectores.index')
                        ->with('success', 'Sector actualizado exitosamente.');
    }

    /**
     * Eliminar sector
     */
    public function destroy(Sector $sector)
    {
        $user = Auth::user();

        if (!$user->esAdministrador()) {
            abort(403, 'No tienes permiso para eliminar sectores.');
        }

        $sector->delete();

        return redirect()->route('sectores.index')
                        ->with('success', 'Sector eliminado exitosamente.');
    }
}




