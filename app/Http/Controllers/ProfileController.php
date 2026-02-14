<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
            'sucursal_id' => 'nullable|exists:sucursales,id',
        ]);

        $user->update([
            'nombre_completo' => $request->nombre_completo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'domicilio' => $request->domicilio,
            'sucursal_id' => $request->sucursal_id,
        ]);

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
     * Actualizar la contraseña del usuario
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'clave' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Contraseña actualizada exitosamente.');
    }
}


