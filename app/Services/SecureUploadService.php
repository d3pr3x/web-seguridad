<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Subida segura de archivos sensibles: disco private, UUID, validación estricta.
 * Retorna path interno (relativo al disco private); no se expone URL pública.
 */
class SecureUploadService
{
    private string $disk = 'private';

    public function storeDocument(UploadedFile $file, string $subdir = 'documentos'): string
    {
        $maxKb = config('uploads.max_document_kb', 10240);
        $allowedMimes = config('uploads.document_mimes', ['pdf', 'jpg', 'jpeg', 'png']);
        $allowedMimetypes = config('uploads.document_mimetypes', [
            'application/pdf', 'image/jpeg', 'image/png',
        ]);
        return $this->store($file, $subdir, $allowedMimes, $allowedMimetypes, $maxKb);
    }

    public function storeImage(UploadedFile $file, string $subdir = 'imagenes'): string
    {
        $maxKb = config('uploads.max_image_kb', 5120);
        $allowedMimes = config('uploads.image_mimes', ['jpg', 'jpeg', 'png', 'webp']);
        $allowedMimetypes = config('uploads.image_mimetypes', [
            'image/jpeg', 'image/png', 'image/webp',
        ]);
        return $this->store($file, $subdir, $allowedMimes, $allowedMimetypes, $maxKb);
    }

    /**
     * Guarda el archivo en disco private con nombre UUID. Valida extensión, mimetype y tamaño.
     * @return string Path relativo al disco (ej: "documentos/abc-123.pdf")
     */
    private function store(
        UploadedFile $file,
        string $subdir,
        array $allowedExtensions,
        array $allowedMimetypes,
        int $maxKb
    ): string {
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?? '');
        if (!in_array($ext, $allowedExtensions, true)) {
            throw new InvalidArgumentException(
                'Tipo de archivo no permitido. Permitidos: ' . implode(', ', $allowedExtensions)
            );
        }
        $mime = $file->getMimeType();
        if (!in_array($mime, $allowedMimetypes, true)) {
            throw new InvalidArgumentException('Tipo MIME no permitido.');
        }
        if ($file->getSize() > $maxKb * 1024) {
            throw new InvalidArgumentException('El archivo supera el tamaño máximo permitido (' . ($maxKb / 1024) . ' MB).');
        }
        if ($this->isImageSubdir($subdir)) {
            $this->validateImageDimensions($file);
        }
        $filename = Str::uuid() . '.' . $ext;
        $path = $subdir . '/' . $filename;
        $file->storeAs($subdir, $filename, $this->disk);
        return $path;
    }

    /**
     * Ruta absoluta en disco para respuesta file()/download().
     */
    public function path(string $relativePath): string
    {
        return Storage::disk($this->disk)->path($relativePath);
    }

    public function exists(string $relativePath): bool
    {
        return Storage::disk($this->disk)->exists($relativePath);
    }

    private function isImageSubdir(string $subdir): bool
    {
        return in_array($subdir, ['acciones', 'reportes', 'reportes-especiales', 'informes', 'documentos', 'imagenes'], true);
    }

    /**
     * Protección contra image bombs: limita dimensiones máximas vía getimagesize (no decodifica todo el pixel data).
     * EXIF no se elimina (requeriría intervention/image u otra lib); declarar en reporte si aplica.
     */
    private function validateImageDimensions(UploadedFile $file): void
    {
        $path = $file->getRealPath();
        if ($path === false) {
            throw new InvalidArgumentException('No se pudo leer el archivo.');
        }
        $info = @getimagesize($path);
        if ($info === false) {
            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            if (in_array($ext, ['heic', 'heif'], true)) {
                return;
            }
            throw new InvalidArgumentException('No se pudo leer las dimensiones de la imagen.');
        }
        $maxW = config('uploads.max_image_width', 3000);
        $maxH = config('uploads.max_image_height', 3000);
        $w = (int) ($info[0] ?? 0);
        $h = (int) ($info[1] ?? 0);
        if ($w > $maxW || $h > $maxH) {
            throw new InvalidArgumentException("Dimensiones máximas permitidas: {$maxW}x{$maxH} píxeles.");
        }
    }
}
