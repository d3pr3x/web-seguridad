<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    /**
     * Mostrar la vista principal del supervisor
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Verificar que el usuario sea supervisor o supervisor-usuario
        if (!$user->esSupervisor()) {
            abort(403, 'No tiene permisos para acceder a esta sección.');
        }
        
        return view('supervisor.index', compact('user'));
    }
}





