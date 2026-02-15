<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Sucursal;
use App\Rules\ChileRut;
use Illuminate\Http\Request;

class PersonasController extends Controller
{
    public function index(Request $request)
    {
        $query = Persona::with('sucursal')->orderBy('nombre');

        if ($request->filled('buscar')) {
            $term = '%' . $request->buscar . '%';
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'like', $term)
                    ->orWhere('rut', 'like', $term)
                    ->orWhere('empresa', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        $personas = $query->paginate(30)->withQueryString();

        return view('ingresos.personas.index', compact('personas'));
    }

    public function create()
    {
        $sucursales = Sucursal::where('activa', true)->orderBy('nombre')->get();

        return view('ingresos.personas.create', compact('sucursales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rut' => ['required', 'string', 'max:12', new ChileRut],
            'nombre' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'empresa' => 'nullable|string|max:100',
            'notas' => 'nullable|string|max:1000',
            'sucursal_id' => 'nullable|exists:sucursales,id',
        ]);

        $rut = Persona::normalizarRut($request->rut);

        if (Persona::where('rut', $rut)->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['rut' => 'Ya existe una persona con este RUT.']);
        }

        Persona::create([
            'rut' => $rut,
            'nombre' => $request->nombre,
            'telefono' => $request->filled('telefono') ? $request->telefono : null,
            'email' => $request->filled('email') ? $request->email : null,
            'empresa' => $request->filled('empresa') ? $request->empresa : null,
            'notas' => $request->filled('notas') ? $request->notas : null,
            'sucursal_id' => $request->sucursal_id ?: null,
        ]);

        return redirect()->route('personas.index')->with('success', 'Persona registrada correctamente.');
    }

    public function edit(int $id)
    {
        $persona = Persona::findOrFail($id);
        $sucursales = Sucursal::where('activa', true)->orderBy('nombre')->get();

        return view('ingresos.personas.edit', compact('persona', 'sucursales'));
    }

    public function update(Request $request, int $id)
    {
        $persona = Persona::findOrFail($id);

        $request->validate([
            'rut' => ['required', 'string', 'max:12', new ChileRut],
            'nombre' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'empresa' => 'nullable|string|max:100',
            'notas' => 'nullable|string|max:1000',
            'sucursal_id' => 'nullable|exists:sucursales,id',
        ]);

        $rut = Persona::normalizarRut($request->rut);

        if (Persona::where('rut', $rut)->where('id', '!=', $id)->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['rut' => 'Ya existe otra persona con este RUT.']);
        }

        $persona->update([
            'rut' => $rut,
            'nombre' => $request->nombre,
            'telefono' => $request->filled('telefono') ? $request->telefono : null,
            'email' => $request->filled('email') ? $request->email : null,
            'empresa' => $request->filled('empresa') ? $request->empresa : null,
            'notas' => $request->filled('notas') ? $request->notas : null,
            'sucursal_id' => $request->sucursal_id ?: null,
        ]);

        return redirect()->route('personas.index')->with('success', 'Persona actualizada.');
    }

    public function destroy(int $id)
    {
        $persona = Persona::findOrFail($id);
        $persona->delete();

        return redirect()->route('personas.index')->with('success', 'Persona eliminada.');
    }
}
