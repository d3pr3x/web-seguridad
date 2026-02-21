<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Services\AuditoriaService;
use App\Services\SessionRevocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Actualizar el perfil del usuario
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date|before:today',
            'domicilio' => 'required|string|max:500',
        ]);

        $user->update($request->only(['nombre_completo', 'fecha_nacimiento', 'domicilio']));

        return redirect()->route('profile.index')
            ->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Mostrar formulario para cambiar contraseña
     */
    public function password()
    {
        return view('profile.password');
    }

    /**
     * Actualizar la contraseña del usuario (política: min 12, confirmación, no comprometida).
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        $user->update([
            'clave' => Hash::make($request->validated('password')),
        ]);
        Auth::logoutOtherDevices($request->validated('current_password'));
        SessionRevocationService::revokeOtherSessionsForUser($user->id_usuario, 'password_changed');
        AuditoriaService::registrar('password_changed', 'usuarios', (string) $user->id_usuario, null, null, ['contexto' => 'perfil']);
        return redirect()->route('profile.index')
            ->with('success', 'Contraseña actualizada exitosamente. El resto de sesiones han sido cerradas.');
    }
}


