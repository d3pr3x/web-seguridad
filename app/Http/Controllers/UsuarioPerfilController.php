<?php

namespace App\Http\Controllers;

use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioPerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('usuario.perfil.index', compact('user'));
    }

    /**
     * Actualizar contraseña (misma política: min 12, confirmación, no comprometida).
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::min(12)->uncompromised()],
        ], [
            'current_password.required' => 'Debe ingresar su contraseña actual.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'new_password.required' => 'Debe ingresar la nueva contraseña.',
            'new_password.confirmed' => 'La confirmación de contraseña no coincide.',
            'new_password.min' => 'La contraseña debe tener al menos 12 caracteres.',
        ]);

        $user = Auth::user();
        $user->update(['clave' => Hash::make($validated['new_password'])]);
        AuditoriaService::registrar('password_changed', 'usuarios', (string) $user->id_usuario, null, null, ['contexto' => 'usuario_perfil']);
        return redirect()->back()->with('success', 'Contraseña actualizada exitosamente.');
    }
}

