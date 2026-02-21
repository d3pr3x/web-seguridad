<?php

namespace App\Http\Controllers;

use App\Models\DocumentoPersonal;
use App\Services\SecureUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioDocumentoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $tiposDocumentos = [
            'cedula_identidad' => 'Cédula de Identidad',
            'licencia_conductor' => 'Licencia de Conductor',
            'certificado_antecedentes' => 'Certificado de Antecedentes',
            'certificado_os10' => 'Certificado de O.S. 10',
        ];
        
        // Obtener documentos aprobados
        $documentosAprobados = $user->documentosPersonales()
            ->where('estado', 'aprobado')
            ->get()
            ->keyBy('tipo_documento');
        
        // Obtener documentos pendientes
        $documentosPendientes = $user->documentosPersonales()
            ->whereIn('estado', ['pendiente', 'rechazado'])
            ->orderBy('creado_en', 'desc')
            ->get();
        
        return view('usuario.documentos.index', compact('tiposDocumentos', 'documentosAprobados', 'documentosPendientes'));
    }
    
    public function create(Request $request)
    {
        $tipo = $request->get('tipo');
        
        $tiposDocumentos = [
            'cedula_identidad' => 'Cédula de Identidad',
            'licencia_conductor' => 'Licencia de Conductor',
            'certificado_antecedentes' => 'Certificado de Antecedentes',
            'certificado_os10' => 'Certificado de O.S. 10',
        ];
        
        if (!isset($tiposDocumentos[$tipo])) {
            return redirect()->route('usuario.documentos.index')
                ->with('error', 'Tipo de documento no válido.');
        }
        
        $user = Auth::user();
        
        // Verificar si ya tiene un documento aprobado de este tipo
        $documentoExistente = $user->documentosPersonales()
            ->where('tipo_documento', $tipo)
            ->where('estado', 'aprobado')
            ->first();
        
        // Verificar si ya tiene una solicitud pendiente
        $solicitudPendiente = $user->documentosPersonales()
            ->where('tipo_documento', $tipo)
            ->where('estado', 'pendiente')
            ->first();
        
        return view('usuario.documentos.create', compact('tipo', 'tiposDocumentos', 'documentoExistente', 'solicitudPendiente'));
    }
    
    public function store(Request $request)
    {
        $maxKb = config('uploads.max_image_kb', 5120);
        $validated = $request->validate([
            'tipo_documento' => 'required|in:cedula_identidad,licencia_conductor,certificado_antecedentes,certificado_os10',
            'imagen_frente' => 'required|image|mimes:jpeg,jpg,png,webp|max:' . $maxKb,
            'imagen_reverso' => 'required|image|mimes:jpeg,jpg,png,webp|max:' . $maxKb,
        ], [
            'imagen_frente.required' => 'La imagen del frente es obligatoria.',
            'imagen_frente.max' => 'La imagen del frente no debe superar ' . ($maxKb / 1024) . ' MB.',
            'imagen_reverso.required' => 'La imagen del reverso es obligatoria.',
            'imagen_reverso.max' => 'La imagen del reverso no debe superar ' . ($maxKb / 1024) . ' MB.',
        ]);

        $user = Auth::user();

        $solicitudPendiente = $user->documentosPersonales()
            ->where('tipo_documento', $validated['tipo_documento'])
            ->where('estado', 'pendiente')
            ->first();

        if ($solicitudPendiente) {
            return redirect()->route('usuario.documentos.index')
                ->with('error', 'Ya tienes una solicitud pendiente para este documento.');
        }

        $upload = app(SecureUploadService::class);
        $subdir = 'documentos/' . $user->id_usuario;
        $imagenFrente = $upload->storeImage($request->file('imagen_frente'), $subdir);
        $imagenReverso = $upload->storeImage($request->file('imagen_reverso'), $subdir);
        
        // Verificar si es un cambio de documento existente
        $documentoExistente = $user->documentosPersonales()
            ->where('tipo_documento', $validated['tipo_documento'])
            ->where('estado', 'aprobado')
            ->first();
        
        // Crear el documento
        $documento = DocumentoPersonal::create([
            'id_usuario' => $user->id_usuario,
            'tipo_documento' => $validated['tipo_documento'],
            'imagen_frente' => $imagenFrente,
            'imagen_reverso' => $imagenReverso,
            'estado' => 'pendiente',
            'es_cambio' => $documentoExistente ? true : false,
            'documento_anterior_id' => $documentoExistente ? $documentoExistente->id : null,
        ]);
        
        return redirect()->route('usuario.documentos.index')
            ->with('success', $documentoExistente 
                ? 'Tu solicitud de cambio ha sido enviada para aprobación.' 
                : 'Tu documento ha sido enviado para aprobación.');
    }
    
    public function show(DocumentoPersonal $documento)
    {
        $user = Auth::user();
        
        // Verificar que el documento pertenece al usuario
        if ($documento->id_usuario !== $user->id_usuario) {
            abort(403);
        }
        
        return view('usuario.documentos.show', compact('documento'));
    }
}


