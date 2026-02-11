<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PuntoRonda;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RondaQrController extends Controller
{
    /**
     * Mostrar QR en pantalla (para imprimir o descargar)
     */
    public function show(PuntoRonda $punto)
    {
        if (!auth()->user()?->esAdministrador()) {
            abort(403, 'Solo administradores pueden ver o descargar códigos QR.');
        }
        $url = route('ronda.escanear', ['codigo' => $punto->codigo]);

        $qrCode = new QrCode($url, size: 280, margin: 10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return response($result->getString(), Response::HTTP_OK, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => 'inline; filename="qr-punto-' . $punto->id . '.png"',
        ]);
    }

    /**
     * Descargar PNG del QR
     */
    public function download(PuntoRonda $punto)
    {
        if (!auth()->user()?->esAdministrador()) {
            abort(403, 'Solo administradores pueden ver o descargar códigos QR.');
        }
        $url = route('ronda.escanear', ['codigo' => $punto->codigo]);

        $qrCode = new QrCode($url, size: 400, margin: 15);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $filename = 'qr-' . \Illuminate\Support\Str::slug($punto->nombre) . '-' . $punto->codigo . '.png';

        return response($result->getString(), Response::HTTP_OK, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
