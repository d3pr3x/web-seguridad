<?php

namespace App\Http\Controllers;

use App\Models\Blacklist;
use App\Rules\ChileRut;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    /**
     * Listado de entradas en blacklist (solo index, store, destroy según especificación).
     */
    public function index()
    {
        $blacklists = Blacklist::withTrashed()->with('creador')->latest()->paginate(50);

        return view('ingresos.blacklist', compact('blacklists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rut' => ['required', 'string', 'max:12', new ChileRut],
            'patente' => ['nullable', 'regex:/^[A-Z]{3,4}\d{2,3}$/i', 'max:10'],
            'motivo' => 'required|string|max:500',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ], [
            'patente.regex' => 'La patente debe ser formato chileno (ABCD12 o ABC123).',
        ]);

        $rut = preg_replace('/[^0-9kK]/', '', strtoupper($request->rut));
        $rut = strlen($rut) >= 2 ? substr($rut, 0, -1) . '-' . substr($rut, -1) : $request->rut;
        $patente = $request->filled('patente') ? strtoupper(preg_replace('/\s+/', '', $request->patente)) : null;

        Blacklist::create([
            'rut' => $rut,
            'patente' => $patente,
            'motivo' => $request->motivo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'activo' => true,
            'creado_por' => auth()->id(),
        ]);

        return redirect()->route('blacklist.index')->with('success', 'Entrada agregada a la blacklist.');
    }

    public function destroy(int $id)
    {
        $item = Blacklist::findOrFail($id);
        $item->delete();

        return redirect()->route('blacklist.index')->with('success', 'Entrada eliminada de la blacklist.');
    }

    /**
     * Activar/desactivar entrada (toggle activo).
     */
    public function toggle(int $id)
    {
        $item = Blacklist::withTrashed()->findOrFail($id);
        if ($item->trashed()) {
            $item->restore();
            $item->update(['activo' => true]);
        } else {
            $item->update(['activo' => !$item->activo]);
        }

        return redirect()->route('blacklist.index')->with('success', 'Estado actualizado.');
    }
}
