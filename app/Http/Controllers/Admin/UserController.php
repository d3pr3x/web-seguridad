<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $query = User::with('sucursal')->orderBy('name')->orderBy('apellido');

        if ($request->filled('buscar')) {
            $term = $request->buscar;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('apellido', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('rut', 'like', "%{$term}%");
            });
        }

        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        if ($request->filled('perfil')) {
            $query->where('perfil', $request->perfil);
        }

        $usuarios = $query->paginate(15)->withQueryString();
        $sucursales = Sucursal::orderBy('nombre')->get();

        return view('admin.usuarios.index', compact('usuarios', 'sucursales'));
    }

    /**
     * Formulario para crear usuario
     */
    public function create()
    {
        $sucursales = Sucursal::activas()->orderBy('nombre')->get();
        return view('admin.usuarios.create', compact('sucursales'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'rut' => 'required|string|max:20|unique:users,rut',
            'password' => 'required|string|min:8|confirmed',
            'perfil' => 'required|in:1,2,3,4',
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'fecha_nacimiento' => 'nullable|date',
            'domicilio' => 'nullable|string|max:500',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['sucursal_id'] = $validated['sucursal_id'] ?: null;

        User::create($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Formulario para editar usuario
     */
    public function edit(User $usuario)
    {
        $sucursales = Sucursal::activas()->orderBy('nombre')->get();
        return view('admin.usuarios.edit', compact('usuario', 'sucursales'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($usuario->id)],
            'rut' => ['required', 'string', 'max:20', Rule::unique('users', 'rut')->ignore($usuario->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'perfil' => 'required|in:1,2,3,4',
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'fecha_nacimiento' => 'nullable|date',
            'domicilio' => 'nullable|string|max:500',
        ]);

        $validated['sucursal_id'] = $validated['sucursal_id'] ?: null;

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $usuario->update($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
