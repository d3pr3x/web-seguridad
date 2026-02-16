<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioRondaController extends Controller
{
    public function index()
    {
        return view('usuario.ronda.index');
    }

    /**
     * Vista aparte solo para escanear QR de puntos de ronda (sin listado ni sidebar).
     */
    public function escaner()
    {
        return view('usuario.ronda.escaner');
    }
}
