<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentoPersonal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentoPersonalController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentoPersonal::with(['user', 'aprobador']);
        
        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('tipo_documento')) {
            $query->where('tipo_documento', $request->tipo_documento);
        }
        
        if ($request->filled('id_usuario')) {
            $query->where('id_usuario', $request->id_usuario);
        }
        
        $documentos = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $estadisticas = [
            'pendientes' => DocumentoPersonal::where('estado', 'pendiente')->count(),
            'aprobados' => DocumentoPersonal::where('estado', 'aprobado')->count(),
            'rechazados' => DocumentoPersonal::where('estado', 'rechazado')->count(),
            'total' => DocumentoPersonal::count(),
        ];
        
        $usuarios = User::orderBy('nombre_completo')->get();
        
        return view('admin.documentos.index', compact('documentos', 'estadisticas', 'usuarios'));
    }
    
    public function show(DocumentoPersonal $documento)
    {
        $documento->load(['user', 'aprobador', 'documentoAnterior']);
        
        return view('admin.documentos.show', compact('documento'));
    }
    
    public function aprobar(DocumentoPersonal $documento)
    {
        if ($documento->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Este documento ya fue procesado.');
        }
        
        $user = Auth::user();
        
        if ($documento->es_cambio && $documento->documentoAnterior) {
            $documento->documentoAnterior->update([
                'estado' => 'rechazado',
                'motivo_rechazo' => 'Reemplazado por nuevo documento',
            ]);
        }
        
        $documento->update([
            'estado' => 'aprobado',
            'aprobado_por' => $user->id_usuario,
            'aprobado_en' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Documento aprobado exitosamente.');
    }
    
    public function rechazar(Request $request, DocumentoPersonal $documento)
    {
        $validated = $request->validate([
            'motivo_rechazo' => 'required|string|max:500',
        ], [
            'motivo_rechazo.required' => 'Debes proporcionar un motivo para el rechazo.',
        ]);
        
        if ($documento->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Este documento ya fue procesado.');
        }
        
        $user = Auth::user();
        
        $documento->update([
            'estado' => 'rechazado',
            'motivo_rechazo' => $validated['motivo_rechazo'],
            'aprobado_por' => $user->id_usuario,
            'aprobado_en' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Documento rechazado.');
    }
    
    public function usuarios()
    {
        $usuarios = User::with(['documentosPersonales' => function($query) {
            $query->where('estado', 'aprobado');
        }])->orderBy('nombre_completo')->get();
        
        return view('admin.documentos.usuarios', compact('usuarios'));
    }
    
    public function usuarioDocumentos(User $user)
    {
        $documentos = $user->documentosPersonales()
            ->orderBy('tipo_documento')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $tiposDocumentos = [
            'cedula_identidad' => 'Cédula de Identidad',
            'licencia_conductor' => 'Licencia de Conductor',
            'certificado_antecedentes' => 'Certificado de Antecedentes',
            'certificado_os10' => 'Certificado de O.S. 10',
        ];
        
        return view('admin.documentos.usuario', compact('user', 'documentos', 'tiposDocumentos'));
    }
}

