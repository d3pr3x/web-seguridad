<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImeiPermitido;
use Illuminate\Http\Request;

class ImeiPermitidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $imeis = ImeiPermitido::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.imeis.index', compact('imeis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.imeis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'imei' => 'required|string|size:15|unique:imeis_permitidos,imei',
            'descripcion' => 'nullable|string|max:255',
        ]);

        ImeiPermitido::create([
            'imei' => $request->imei,
            'descripcion' => $request->descripcion,
            'activo' => true,
        ]);

        return redirect()->route('admin.imeis.index')
                        ->with('success', 'IMEI agregado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ImeiPermitido $imei)
    {
        return view('admin.imeis.show', compact('imei'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImeiPermitido $imei)
    {
        return view('admin.imeis.edit', compact('imei'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImeiPermitido $imei)
    {
        $request->validate([
            'imei' => 'required|string|size:15|unique:imeis_permitidos,imei,' . $imei->id,
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ]);

        $imei->update([
            'imei' => $request->imei,
            'descripcion' => $request->descripcion,
            'activo' => $request->has('activo'),
        ]);

        return redirect()->route('admin.imeis.index')
                        ->with('success', 'IMEI actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImeiPermitido $imei)
    {
        $imei->delete();

        return redirect()->route('admin.imeis.index')
                        ->with('success', 'IMEI eliminado exitosamente.');
    }

    /**
     * Toggle the active status of an IMEI
     */
    public function toggle(ImeiPermitido $imei)
    {
        $antes = ['activo' => $imei->activo];
        $imei->update(['activo' => !$imei->activo]);
        \App\Services\AuditoriaService::registrar('toggle_activo', 'imeis_permitidos', $imei->id, $antes, $imei->only('activo'), []);

        $status = $imei->activo ? 'activado' : 'desactivado';
        
        return redirect()->route('admin.imeis.index')
                        ->with('success', "IMEI {$status} exitosamente.");
    }
}
