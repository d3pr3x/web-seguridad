<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\ModalidadJerarquia;
use App\Models\Sucursal;
use Illuminate\Http\Request;

/**
 * Vista Clientes: crear/editar empresas e instalaciones (sucursales).
 * Jerarquía: Empresa → Instalaciones (sucursales).
 */
class ClienteController extends Controller
{
    // ─── Empresas ───

    public function index(Request $request)
    {
        $query = Empresa::withCount('sucursales')->with('modalidad');
        if (! $request->boolean('incluir_inactivos')) {
            $query->activos();
        }
        if (! $request->boolean('incluir_borrados')) {
            // Por defecto no incluir borrados (soft deleted)
        } else {
            $query->withTrashed();
        }
        $empresas = $query->orderBy('nombre')->paginate(15)->withQueryString();

        return view('admin.clientes.index', compact('empresas'));
    }

    public function create()
    {
        $modalidades = ModalidadJerarquia::activos()->orderBy('nombre')->get();

        return view('admin.clientes.empresas.create', compact('modalidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modalidad_id' => 'required|exists:modalidades_jerarquia,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50|unique:empresas,codigo',
            'razon_social' => 'nullable|string|max:200',
            'rut' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'comuna' => 'nullable|string|max:100',
            'ciudad' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'activa' => 'boolean',
        ]);
        $validated['activa'] = $request->boolean('activa', true);

        Empresa::create($validated);

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    public function edit(Empresa $cliente)
    {
        $modalidades = ModalidadJerarquia::activos()->orderBy('nombre')->get();

        return view('admin.clientes.empresas.edit', compact('cliente', 'modalidades'));
    }

    public function update(Request $request, Empresa $cliente)
    {
        $validated = $request->validate([
            'modalidad_id' => 'required|exists:modalidades_jerarquia,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50|unique:empresas,codigo,' . $cliente->id,
            'razon_social' => 'nullable|string|max:200',
            'rut' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'comuna' => 'nullable|string|max:100',
            'ciudad' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'activa' => 'boolean',
        ]);
        $validated['activa'] = $request->boolean('activa', true);
        if ($cliente->trashed()) {
            return redirect()->route('admin.clientes.index')->with('error', 'No se puede editar un registro histórico (borrado).');
        }
        $cliente->update($validated);

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Empresa $cliente)
    {
        if ($cliente->sucursales()->exists()) {
            return redirect()->route('admin.clientes.index')
                ->with('error', 'No se puede eliminar la empresa porque tiene instalaciones asociadas. Elimine o reasigne las instalaciones primero.');
        }
        $cliente->delete();
        return redirect()->route('admin.clientes.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }

    // ─── Instalaciones (sucursales) de una empresa ───

    public function instalaciones(Empresa $cliente)
    {
        $instalaciones = $cliente->sucursales()->withCount('sectores')->orderBy('nombre')->paginate(15);

        return view('admin.clientes.instalaciones.index', compact('cliente', 'instalaciones'));
    }

    public function createInstalacion(Empresa $cliente)
    {
        return view('admin.clientes.instalaciones.create', compact('cliente'));
    }

    public function storeInstalacion(Request $request, Empresa $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:sucursales,codigo',
            'direccion' => 'required|string',
            'comuna' => 'nullable|string|max:100',
            'ciudad' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'activa' => 'boolean',
        ]);
        $validated['empresa_id'] = $cliente->id;
        $validated['activa'] = $request->boolean('activa', true);

        Sucursal::create($validated);

        return redirect()->route('admin.clientes.instalaciones', $cliente)
            ->with('success', 'Instalación creada correctamente.');
    }

    public function editInstalacion(Empresa $cliente, Sucursal $sucursal)
    {
        if ($sucursal->empresa_id != $cliente->id) {
            abort(404);
        }
        return view('admin.clientes.instalaciones.edit', compact('cliente', 'sucursal'));
    }

    public function updateInstalacion(Request $request, Empresa $cliente, Sucursal $sucursal)
    {
        if ($sucursal->empresa_id != $cliente->id) {
            abort(404);
        }
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:sucursales,codigo,' . $sucursal->id,
            'direccion' => 'required|string',
            'comuna' => 'nullable|string|max:100',
            'ciudad' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'activa' => 'boolean',
        ]);
        $validated['activa'] = $request->boolean('activa', true);

        $sucursal->update($validated);

        return redirect()->route('admin.clientes.instalaciones', $cliente)
            ->with('success', 'Instalación actualizada correctamente.');
    }

    public function destroyInstalacion(Empresa $cliente, Sucursal $sucursal)
    {
        if ($sucursal->empresa_id != $cliente->id) {
            abort(404);
        }
        if ($sucursal->users()->exists() || $sucursal->sectores()->exists()) {
            return redirect()->route('admin.clientes.instalaciones', $cliente)
                ->with('error', 'No se puede eliminar la instalación porque tiene usuarios o sectores asociados.');
        }
        $sucursal->delete();
        return redirect()->route('admin.clientes.instalaciones', $cliente)
            ->with('success', 'Instalación eliminada correctamente.');
    }
}
