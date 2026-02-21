<?php

namespace App\Http\Controllers;

use App\Models\Accion;
use App\Models\DocumentoPersonal;
use App\Models\Informe;
use App\Models\Reporte;
use App\Models\ReporteEspecial;
use App\Models\User;
use App\Services\AuditoriaService;
use App\Services\AuthorizationContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Descarga de archivos sensibles desde disco private (o legacy public).
 * Requiere auth, autorización (dueño o admin/supervisor), throttle y auditoría.
 */
class ArchivoPrivadoController extends Controller
{
    /**
     * Descargar imagen de documento (frente o reverso). Audita download_file.
     */
    public function documentoArchivo(Request $request, DocumentoPersonal $documento, string $lado): StreamedResponse
    {
        if (!in_array($lado, ['frente', 'reverso'], true)) {
            abort(404);
        }
        $user = $request->user();
        $documento->loadMissing('user.sucursal');
        $resourceEmpresaId = $documento->user?->sucursal?->empresa_id;
        $this->ensureSameEmpresaOrIdor($user, $resourceEmpresaId, 'documentos', (string) $documento->id, $request);
        $puedeVer = $documento->id_usuario === $user->id_usuario
            || $user->esAdministrador()
            || $user->esSupervisor();
        if (!$puedeVer) {
            $this->recordIdorAndAbort('documentos', (string) $documento->id, $request);
        }
        $path = $lado === 'frente' ? $documento->imagen_frente : $documento->imagen_reverso;
        if (!$path) {
            abort(404);
        }
        $absolutePath = $this->resolvePath($path);
        if (!$absolutePath) {
            abort(404);
        }
        AuditoriaService::registrar('download_file', 'documentos', (string) $documento->id, null, null, [
            'tipo' => 'documento_imagen',
            'lado' => $lado,
            'id_usuario_documento' => $documento->id_usuario,
        ]);
        return response()->file($absolutePath);
    }

    /**
     * Imagen de acción (rondas/novedades). Audita download_file.
     */
    public function accionImagen(Request $request, Accion $accion, int $index): StreamedResponse
    {
        $user = $request->user();
        $accion->loadMissing('sucursal');
        $this->ensureSameEmpresaOrIdor($user, $accion->sucursal?->empresa_id, 'acciones', (string) $accion->id, $request);
        $puedeVer = $accion->id_usuario === $user->id_usuario
            || $user->esAdministrador()
            || ($user->esSupervisor() && (int) $accion->sucursal_id === (int) $user->sucursal_id);
        if (!$puedeVer) {
            $this->recordIdorAndAbort('acciones', (string) $accion->id, $request);
        }
        $imagenes = $accion->imagenes ?? [];
        $path = $imagenes[$index] ?? null;
        if ($path === null || $path === '') {
            abort(404);
        }
        $absolutePath = $this->resolvePath($path);
        if (!$absolutePath) {
            abort(404);
        }
        AuditoriaService::registrar('download_file', 'acciones', (string) $accion->id, null, null, [
            'tipo' => 'accion_imagen',
            'index' => $index,
        ]);
        return response()->file($absolutePath);
    }

    /**
     * Imagen de reporte (tarea). Audita download_file.
     */
    public function reporteImagen(Request $request, Reporte $reporte, int $index): StreamedResponse
    {
        $reporte->loadMissing('user.sucursal');
        $user = $request->user();
        $this->ensureSameEmpresaOrIdor($user, $reporte->user?->sucursal?->empresa_id, 'reportes', (string) $reporte->id, $request);
        $puedeVer = (int) $reporte->id_usuario === (int) $user->id_usuario
            || $user->esAdministrador()
            || ($user->esSupervisor() && $reporte->user && (int) $reporte->user->sucursal_id === (int) $user->sucursal_id);
        if (!$puedeVer) {
            $this->recordIdorAndAbort('reportes', (string) $reporte->id, $request);
        }
        $imagenes = $reporte->imagenes ?? [];
        $path = $imagenes[$index] ?? null;
        if ($path === null || $path === '') {
            abort(404);
        }
        $absolutePath = $this->resolvePath($path);
        if (!$absolutePath) {
            abort(404);
        }
        AuditoriaService::registrar('download_file', 'reportes', (string) $reporte->id, null, null, [
            'tipo' => 'reporte_imagen',
            'index' => $index,
        ]);
        return response()->file($absolutePath);
    }

    /**
     * Imagen de reporte especial. Audita download_file.
     */
    public function reporteEspecialImagen(Request $request, ReporteEspecial $reporte_especial, int $index): StreamedResponse
    {
        $user = $request->user();
        $reporte_especial->loadMissing('sucursal');
        $this->ensureSameEmpresaOrIdor($user, $reporte_especial->sucursal?->empresa_id, 'reportes_especiales', (string) $reporte_especial->id, $request);
        $puedeVer = $reporte_especial->id_usuario === $user->id_usuario
            || $user->esAdministrador()
            || ($user->esSupervisor() && (int) $reporte_especial->sucursal_id === (int) $user->sucursal_id);
        if (!$puedeVer) {
            $this->recordIdorAndAbort('reportes_especiales', (string) $reporte_especial->id, $request);
        }
        $imagenes = $reporte_especial->imagenes ?? [];
        $path = $imagenes[$index] ?? null;
        if ($path === null || $path === '') {
            abort(404);
        }
        $absolutePath = $this->resolvePath($path);
        if (!$absolutePath) {
            abort(404);
        }
        AuditoriaService::registrar('download_file', 'reportes_especiales', (string) $reporte_especial->id, null, null, [
            'tipo' => 'reporte_especial_imagen',
            'index' => $index,
        ]);
        return response()->file($absolutePath);
    }

    /**
     * Fotografía de informe. Audita download_file.
     */
    public function informeFotografia(Request $request, Informe $informe, int $index): StreamedResponse
    {
        $informe->loadMissing('reporte.user.sucursal');
        $user = $request->user();
        $reporte = $informe->reporte;
        $this->ensureSameEmpresaOrIdor($user, $reporte?->user?->sucursal?->empresa_id, 'informes', (string) $informe->id, $request);
        $puedeVer = $reporte && (int) $reporte->id_usuario === (int) $user->id_usuario
            || $user->esAdministrador()
            || ($user->esSupervisor() && $reporte && $reporte->user && (int) $reporte->user->sucursal_id === (int) $user->sucursal_id);
        if (!$puedeVer) {
            $this->recordIdorAndAbort('informes', (string) $informe->id, $request);
        }
        $fotografias = $informe->fotografias ?? [];
        $path = $fotografias[$index] ?? null;
        if ($path === null || $path === '') {
            abort(404);
        }
        $absolutePath = $this->resolvePath($path);
        if (!$absolutePath) {
            abort(404);
        }
        AuditoriaService::registrar('download_file', 'informes', (string) $informe->id, null, null, [
            'tipo' => 'informe_fotografia',
            'index' => $index,
        ]);
        return response()->file($absolutePath);
    }

    /**
     * Supervisor debe estar en la misma empresa que el recurso; si no, 404 + idor_attempt.
     */
    private function ensureSameEmpresaOrIdor(User $user, ?int $resourceEmpresaId, string $tabla, string $registroId, Request $request): void
    {
        if (!$user->esSupervisor() || $resourceEmpresaId === null) {
            return;
        }
        $userEmpresaId = AuthorizationContext::getUserEmpresaId($user);
        if ($userEmpresaId !== null && (int) $userEmpresaId !== (int) $resourceEmpresaId) {
            $this->recordIdorAndAbort($tabla, $registroId, $request);
        }
    }

    /**
     * Registra intento IDOR y responde 404 para no revelar existencia del recurso.
     */
    private function recordIdorAndAbort(string $tabla, string $registroId, Request $request): void
    {
        AuditoriaService::registrar('idor_attempt', $tabla, $registroId, null, null, [
            'ruta' => $request->path(),
            'metodo' => $request->method(),
        ]);
        abort(404);
    }

    /**
     * Resuelve path: primero private, luego public (legacy). Retorna ruta absoluta o null.
     */
    private function resolvePath(string $relativePath): ?string
    {
        if (Storage::disk('private')->exists($relativePath)) {
            return Storage::disk('private')->path($relativePath);
        }
        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->path($relativePath);
        }
        return null;
    }
}
