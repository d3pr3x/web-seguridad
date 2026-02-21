<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminStoreUserRequest;
use App\Http\Requests\AdminUpdateUserRequest;
use App\Models\Persona;
use App\Models\RolUsuario;
use App\Models\User;
use App\Models\Sucursal;
use App\Services\AuditoriaService;
use App\Services\SessionRevocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
     * Guardar nuevo usuario (política de contraseña: min 12, confirmación, no comprometida).
     */
    public function store(AdminStoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['clave'] = Hash::make($validated['password']);
        unset($validated['password']);
        $validated['sucursal_id'] = $validated['sucursal_id'] ?: null;
        $validated['email'] = !empty($validated['email']) ? $validated['email'] : null;

        $user = User::create($validated);
        AuditoriaService::registrar('password_changed', 'usuarios', (string) $user->id_usuario, null, null, ['contexto' => 'admin_crear_usuario']);

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
     * Actualizar usuario (solo ADMIN). Si se envía password, política: min 12, confirmación, no comprometida.
     */
    public function update(AdminUpdateUserRequest $request, User $usuario)
    {
        $validated = $request->validated();
        $validated['sucursal_id'] = $validated['sucursal_id'] ?: null;
        $validated['email'] = !empty($validated['email']) ? $validated['email'] : null;

        $oldRolId = $usuario->rol_id;
        $oldSucursalId = $usuario->sucursal_id;
        $passwordChanged = !empty($validated['password'] ?? null);
        if ($passwordChanged) {
            $validated['clave'] = Hash::make($validated['password']);
            AuditoriaService::registrar('password_changed', 'usuarios', (string) $usuario->id_usuario, null, null, ['contexto' => 'admin_editar_usuario']);
        }
        unset($validated['password'], $validated['password_confirmation']);

        $usuario->update($validated);

        if ($passwordChanged) {
            SessionRevocationService::revokeOtherSessionsForUser($usuario->id_usuario, 'password_changed_by_admin');
        }
        if ((int) $oldRolId !== (int) ($usuario->rol_id ?? 0) || (int) $oldSucursalId !== (int) ($usuario->sucursal_id ?? 0)) {
            SessionRevocationService::revokeOtherSessionsForUser($usuario->id_usuario, 'rol_or_sucursal_changed');
        }

        Persona::registrarOActualizar($validated['run'], $validated['nombre_completo'], [
            'sucursal_id' => $validated['sucursal_id'],
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
