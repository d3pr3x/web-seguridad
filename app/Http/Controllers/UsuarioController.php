<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reporte;
use App\Models\Informe;

class UsuarioController extends Controller
{
    /**
     * Mostrar la vista principal del usuario
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        return view('usuario.index', compact('user'));
    }

    /**
     * Mostrar sección de novedades
     */
    public function novedades()
    {
        return view('usuario.novedades.index');
    }

    /**
     * Mostrar formulario para crear novedad (según tipo)
     */
    public function novedadesCreate($tipo)
    {
        return view('usuario.novedades.create', compact('tipo'));
    }

    /**
     * Guardar novedad
     */
    public function novedadesStore(Request $request)
    {
        // TODO: Implementar lógica de guardado
        return redirect()->route('usuario.index')
            ->with('success', 'Novedad registrada exitosamente');
    }

    /**
     * Mostrar sección de reportes
     */
    public function reportes()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $reportes = Reporte::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('usuario.reportes.index', compact('reportes'));
    }

    /**
     * Mostrar formulario para crear reporte (según tipo)
     */
    public function reportesCreate($tipo)
    {
        return view('usuario.reportes.create', compact('tipo'));
    }

    /**
     * Guardar reporte
     */
    public function reportesStore(Request $request)
    {
        // TODO: Implementar lógica de guardado
        return redirect()->route('usuario.index')
            ->with('success', 'Reporte registrado exitosamente');
    }
}
