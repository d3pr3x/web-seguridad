<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        $usuario = $this->route('usuario');
        $id = $usuario ? $usuario->id_usuario : null;

        return [
            'nombre_completo' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('usuarios', 'email')->ignore($id, 'id_usuario')],
            'telefono' => 'nullable|string|max:30',
            'run' => ['required', 'string', 'max:20', Rule::unique('usuarios', 'run')->ignore($id, 'id_usuario')],
            'password' => [
                'nullable',
                'string',
                'confirmed',
                Password::min(12)->uncompromised(),
            ],
            'rol_id' => 'required|exists:roles_usuario,id',
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'fecha_nacimiento' => 'nullable|date',
            'domicilio' => 'nullable|string|max:500',
            'rango' => 'nullable|string|max:80',
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 12 caracteres.',
        ];
    }
}
