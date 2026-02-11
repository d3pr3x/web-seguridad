<?php

namespace App\Http\Controllers;

use App\Models\PuntoRonda;
use App\Models\RondaEscaneo;
use Illuminate\Http\Request;

class RondaEscaneoController extends Controller
{
    /**
     * Distancia en metros entre dos coordenadas (fórmula de Haversine).
     */
    private static function distanciaMetros(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R = 6371000; // Radio de la Tierra en metros
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }

    /**
     * Registrar escaneo de un punto (guardia escanea QR desde la app con ubicación).
     * Solo es válido si el dispositivo está a ≤10 m del punto.
     * Ruta: /ronda/escanear/{codigo}
     */
    public function escanear(Request $request, string $codigo)
    {
        $user = $request->user();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Debe iniciar sesión.'], 401);
            }
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para registrar el escaneo.');
        }

        $punto = PuntoRonda::where('codigo', $codigo)->activos()->first();
        if (!$punto) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Código de punto no válido o inactivo.'], 404);
            }
            return redirect()->back()->with('error', 'Código de punto no válido o inactivo.');
        }

        if ($user->sucursal_id && $punto->sucursal_id !== $user->sucursal_id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Este punto no corresponde a su sucursal.'], 403);
            }
            return redirect()->back()->with('error', 'Este punto no corresponde a su sucursal.');
        }

        // Validación por ubicación: el punto debe tener coordenadas configuradas
        if (!$punto->tieneUbicacion()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este punto aún no tiene ubicación configurada. Contacte al administrador.',
                ], 422);
            }
            return redirect()->back()->with('error', 'Este punto no tiene ubicación configurada. Contacte al administrador.');
        }

        $lat = $request->input('lat');
        $lng = $request->input('lng');

        // La ubicación del dispositivo es obligatoria para validar que está en el lugar
        if ($lat === null || $lat === '' || $lng === null || $lng === '') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe permitir el acceso a la ubicación y escanear desde la aplicación para registrar el punto.',
                ], 422);
            }
            return redirect()->back()->with('error', 'Debe permitir la ubicación y escanear desde la aplicación.');
        }

        $lat = (float) $lat;
        $lng = (float) $lng;
        $distanciaMaxima = $punto->getDistanciaMaximaMetros();
        $distancia = self::distanciaMetros(
            (float) $punto->lat,
            (float) $punto->lng,
            $lat,
            $lng
        );

        if ($distancia > $distanciaMaxima) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe estar a menos de ' . $distanciaMaxima . ' metros del punto para registrar el escaneo. Distancia actual: ' . round($distancia, 1) . ' m.',
                ], 422);
            }
            return redirect()->back()->with('error', 'Debe estar a menos de ' . $distanciaMaxima . ' m del punto. Distancia actual: ' . round($distancia, 1) . ' m.');
        }

        RondaEscaneo::create([
            'punto_ronda_id' => $punto->id,
            'user_id' => $user->id,
            'escaneado_en' => now(),
            'lat' => $lat,
            'lng' => $lng,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Escaneo registrado correctamente.',
                'punto' => $punto->nombre,
                'escaneado_en' => now()->toIso8601String(),
            ]);
        }

        $target = $user->esUsuario() || $user->esSupervisorUsuario()
            ? route('usuario.ronda.index')
            : ($user->esAdministrador() ? route('administrador.index') : route('supervisor.index'));

        return redirect($target)->with('success', 'Escaneo registrado: ' . $punto->nombre . ' a las ' . now()->format('H:i'));
    }
}
