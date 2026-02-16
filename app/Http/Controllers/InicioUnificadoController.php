<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Vista de inicio unificada por perfil (pruebas).
 * Una sola vista que muestra u oculta bloques según administrador / supervisor / usuario.
 */
class InicioUnificadoController extends Controller
{
    /**
     * Mostrar la vista de inicio unificada (pruebas).
     */
    public function index()
    {
        return view('inicio-unificado.index');
    }
}
