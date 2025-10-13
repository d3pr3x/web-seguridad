<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdministradorController extends Controller
{
    /**
     * Mostrar la vista principal del administrador
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Verificar que el usuario sea administrador
        if (!$user->esAdministrador()) {
            abort(403, 'No tiene permisos para acceder a esta sección.');
        }
        
        return view('administrador.index', compact('user'));
    }
}




