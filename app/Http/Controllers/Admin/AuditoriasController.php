<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use Illuminate\Http\Request;

/**
 * C3: Vista admin de auditorías (solo lectura). Sin edición ni borrado.
 */
class AuditoriasController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = Auditoria::with(['usuario', 'empresa', 'sucursal'])
            ->orderByDesc('ocurrido_en');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('tabla')) {
            $query->where('tabla', $request->tabla);
        }
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }
        if ($request->filled('desde')) {
            $query->whereDate('ocurrido_en', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('ocurrido_en', '<=', $request->hasta);
        }

        $auditorias = $query->paginate(25)->withQueryString();

        $tablas = Auditoria::distinct()->pluck('tabla')->sort()->values();

        return view('admin.auditorias.index', compact('auditorias', 'tablas'));
    }

    public function show(Auditoria $auditoria)
    {
        $this->authorizeAdmin();
        $auditoria->load(['usuario', 'empresa', 'sucursal']);
        return view('admin.auditorias.show', compact('auditoria'));
    }

    private function authorizeAdmin(): void
    {
        if (!auth()->user()?->esAdministrador()) {
            abort(403, 'Solo administradores pueden ver auditorías.');
        }
    }
}
