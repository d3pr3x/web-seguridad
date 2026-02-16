<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrupoIncidente;
use App\Models\TipoIncidente;
use Illuminate\Http\Request;

/**
 * Punto 2: Vista de grupos de delitos/incidentes y tipos asociados.
 */
class GruposIncidentesController extends Controller
{
    public function index()
    {
        $grupos = GrupoIncidente::with('tiposIncidente')->orderBy('orden')->get();
        return view('admin.grupos-incidentes.index', compact('grupos'));
    }
}
