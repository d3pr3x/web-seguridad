<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsuarioPerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        return view('usuario.perfil.index', compact('user'));
    }
    
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Debes ingresar tu contraseña actual.',
            'new_password.required' => 'Debes ingresar una nueva contraseña.',
            'new_password.confirmed' => 'Las contraseñas no coinciden.',
            'new_password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);
        
        $user = Auth::user();
        
        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->with('error', 'La contraseña actual es incorrecta.');
        }
        
        // Actualizar la contraseña
        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);
        
        return redirect()->back()->with('success', 'Contraseña actualizada exitosamente.');
    }
}

