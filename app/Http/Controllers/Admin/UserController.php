<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\RolUsuario;
use App\Models\User;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Listado de usuarios del sistema con filtros
     */
    public function index(Request $request)
    {
        $query = User::with(['sucursal', 'rol'])->orderBy('nombre_completo');

        if ($request->filled('buscar')) {
            $term = $request->buscar;
            $query->where(function ($q) use ($term) {
                $q->where('nombre_completo', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('run', 'like', "%{$term}%");
            });
        }

        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        if ($request->filled('rol_id')) {
            $query->where('rol_id', $request->rol_id);
        }

        $usuarios = $query->paginate(15)->withQueryString();
        $sucursales = Sucursal::orderBy('nombre')->get();
        $roles = RolUsuario::orderBy('nombre')->get();

        return view('admin.usuarios.index', compact('usuarios', 'sucursales', 'roles'));
    }

    /**
     * Formulario para crear usuario
     */
    public function create()
    {
        $sucursales = Sucursal::activas()->orderBy('nombre')->get();
        $roles = RolUsuario::orderBy('nombre')->get();
        return view('admin.usuarios.create', compact('sucursales', 'roles'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'nullable|email|unique:usuarios,email',
            'telefono' => 'nullable|string|max:30',
            'run' => 'required|string|max:20|unique:usuarios,run',
            'password' => 'required|string|min:8|confirmed',
            'rol_id' => 'required|exists:roles_usuario,id',
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'fecha_nacimiento' => 'nullable|date',
            'domicilio' => 'nullable|string|max:500',
            'rango' => 'nullable|string|max:80',
        ]);

        $validated['clave'] = Hash::make($validated['password']);
        unset($validated['password']);
        $validated['sucursal_id'] = $validated['sucursal_id'] ?: null;
        $validated['email'] = !empty($validated['email']) ? $validated['email'] : null;

        User::create($validated);

        Persona::registrarOActualizar($validated['run'], $validated['nombre_completo'], [
            'sucursal_id' => $validated['sucursal_id'],
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Formulario para editar usuario. Punto 13: solo ADMIN puede editar; supervisor solo crea.
     */
    public function edit(User $usuario)
    {
        if (!auth()->user()->esAdministrador()) {
            abort(403, 'Solo el administrador puede editar usuarios.');
        }
        $sucursales = Sucursal::activas()->orderBy('nombre')->get();
        $roles = RolUsuario::orderBy('nombre')->get();
        return view('admin.usuarios.edit', compact('usuario', 'sucursales', 'roles'));
    }

    /**
     * Actualizar usuario. Punto 13: solo ADMIN.
     */
    public function update(Request $request, User $usuario)
    {
        if (!auth()->user()->esAdministrador()) {
            abort(403, 'Solo el administrador puede editar usuarios.');
        }
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('usuarios', 'email')->ignore($usuario->id_usuario, 'id_usuario')],
            'telefono' => 'nullable|string|max:30',
            'run' => ['required', 'string', 'max:20', Rule::unique('usuarios', 'run')->ignore($usuario->id_usuario, 'id_usuario')],
            'password' => 'nullable|string|min:8|confirmed',
            'rol_id' => 'required|exists:roles_usuario,id',
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'fecha_nacimiento' => 'nullable|date',
            'domicilio' => 'nullable|string|max:500',
            'rango' => 'nullable|string|max:80',
        ]);

        $validated['sucursal_id'] = $validated['sucursal_id'] ?: null;
        $validated['email'] = !empty($validated['email']) ? $validated['email'] : null;

        if (!empty($validated['password'] ?? null)) {
            $validated['clave'] = Hash::make($validated['password']);
        }
        unset($validated['password']);
        if (isset($validated['password_confirmation'])) {
            unset($validated['password_confirmation']);
        }

        $usuario->update($validated);

        Persona::registrarOActualizar($validated['run'], $validated['nombre_completo'], [
            'sucursal_id' => $validated['sucursal_id'],
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
