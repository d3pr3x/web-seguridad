<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiaTrabajado;
use Carbon\Carbon;

class DiaTrabajadoController extends Controller
{
    /**
     * Mostrar días trabajados del usuario
     */
    public function index()
    {
        if (!config('app.show_calculo_sueldos')) {
            abort(403, 'Función no habilitada.');
        }
        $usuario = auth()->user();
        $mesActual = Carbon::now()->format('Y-m');
        
        $diasTrabajados = DiaTrabajado::where('id_usuario', $usuario->id_usuario)
            ->whereRaw("TO_CHAR(fecha, 'YYYY-MM') = ?", [$mesActual])
            ->orderBy('fecha', 'desc')
            ->get();

        $totalDias = $diasTrabajados->sum('ponderacion');
        
        return view('dias-trabajados.index', compact('diasTrabajados', 'totalDias', 'mesActual'));
    }

    /**
     * Mostrar formulario para agregar día trabajado
     */
    public function create()
    {
        if (!config('app.show_calculo_sueldos')) {
            abort(403, 'Función no habilitada.');
        }
        return view('dias-trabajados.create');
    }

    /**
     * Guardar día trabajado
     */
    public function store(Request $request)
    {
        if (!config('app.show_calculo_sueldos')) {
            abort(403, 'Función no habilitada.');
        }
        $request->validate([
            'fecha' => 'required|date',
            'ponderacion' => 'required|numeric|min:0.1|max:3.0',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $usuario = auth()->user();
        
        // Verificar que no exista ya un registro para esa fecha
        $existe = DiaTrabajado::where('id_usuario', $usuario->id_usuario)
            ->where('fecha', $request->fecha)
            ->exists();

        if ($existe) {
            return back()->withErrors(['fecha' => 'Ya existe un registro para esta fecha.'])->withInput();
        }

        DiaTrabajado::create([
            'id_usuario' => $usuario->id_usuario,
            'fecha' => $request->fecha,
            'ponderacion' => $request->ponderacion,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('dias-trabajados.index')->with('success', 'Día trabajado registrado exitosamente.');
    }

    /**
     * Editar día trabajado
     */
    public function edit($id)
    {
        if (!config('app.show_calculo_sueldos')) {
            abort(403, 'Función no habilitada.');
        }
        $diaTrabajado = DiaTrabajado::where('id', $id)
            ->where('id_usuario', auth()->id())
            ->firstOrFail();

        return view('dias-trabajados.edit', compact('diaTrabajado'));
    }

    /**
     * Actualizar día trabajado
     */
    public function update(Request $request, $id)
    {
        if (!config('app.show_calculo_sueldos')) {
            abort(403, 'Función no habilitada.');
        }
        $request->validate([
            'fecha' => 'required|date',
            'ponderacion' => 'required|numeric|min:0.1|max:3.0',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $diaTrabajado = DiaTrabajado::where('id', $id)
            ->where('id_usuario', auth()->id())
            ->firstOrFail();

        // Verificar que no exista otro registro para esa fecha (excluyendo el actual)
        $existe = DiaTrabajado::where('id_usuario', auth()->id())
            ->where('fecha', $request->fecha)
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return back()->withErrors(['fecha' => 'Ya existe un registro para esta fecha.'])->withInput();
        }

        $diaTrabajado->update([
            'fecha' => $request->fecha,
            'ponderacion' => $request->ponderacion,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('dias-trabajados.index')->with('success', 'Día trabajado actualizado exitosamente.');
    }

    /**
     * Eliminar día trabajado
     */
    public function destroy($id)
    {
        if (!config('app.show_calculo_sueldos')) {
            abort(403, 'Función no habilitada.');
        }
        $diaTrabajado = DiaTrabajado::where('id', $id)
            ->where('id_usuario', auth()->id())
            ->firstOrFail();

        $diaTrabajado->delete();

        return redirect()->route('dias-trabajados.index')->with('success', 'Día trabajado eliminado exitosamente.');
    }
}
