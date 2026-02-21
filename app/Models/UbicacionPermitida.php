<?php

namespace App\Models;

use App\Models\Concerns\HasActivoScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UbicacionPermitida extends Model
{
    use HasFactory, HasActivoScope, SoftDeletes;

    protected $table = 'ubicaciones_permitidas';

    protected function activoColumn(): string
    {
        return 'activa';
    }

    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'radio',
        'activa',
        'descripcion',
        'sucursal_id',
    ];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'radio' => 'integer',
        'activa' => 'boolean',
    ];

    /**
     * Relación con sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Calcular distancia entre dos puntos GPS usando la fórmula de Haversine
     * 
     * @param float $lat1 Latitud del punto 1
     * @param float $lon1 Longitud del punto 1
     * @param float $lat2 Latitud del punto 2
     * @param float $lon2 Longitud del punto 2
     * @return float Distancia en metros
     */
    public static function calcularDistancia($lat1, $lon1, $lat2, $lon2)
    {
        $radioTierra = 6371000; // Radio de la Tierra en metros

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radioTierra * $c; // Distancia en metros
    }

    /**
     * Verificar si una ubicación está dentro del radio permitido
     * 
     * @param float $latitud
     * @param float $longitud
     * @return bool
     */
    public function dentroDelRadio($latitud, $longitud)
    {
        $distancia = self::calcularDistancia(
            $this->latitud,
            $this->longitud,
            $latitud,
            $longitud
        );

        return $distancia <= $this->radio;
    }

    /**
     * Verificar si una ubicación está dentro de alguna ubicación permitida activa
     * 
     * @param float $latitud
     * @param float $longitud
     * @return array ['permitido' => bool, 'ubicacion' => UbicacionPermitida|null, 'distancia' => float|null]
     */
    public static function verificarUbicacion($latitud, $longitud)
    {
        $ubicaciones = self::where('activa', true)->get();

        foreach ($ubicaciones as $ubicacion) {
            $distancia = self::calcularDistancia(
                $ubicacion->latitud,
                $ubicacion->longitud,
                $latitud,
                $longitud
            );

            if ($distancia <= $ubicacion->radio) {
                return [
                    'permitido' => true,
                    'ubicacion' => $ubicacion,
                    'distancia' => round($distancia, 2)
                ];
            }
        }

        // Si no está en ninguna ubicación, devolver la más cercana
        $ubicacionMasCercana = null;
        $distanciaMinima = PHP_FLOAT_MAX;

        foreach ($ubicaciones as $ubicacion) {
            $distancia = self::calcularDistancia(
                $ubicacion->latitud,
                $ubicacion->longitud,
                $latitud,
                $longitud
            );

            if ($distancia < $distanciaMinima) {
                $distanciaMinima = $distancia;
                $ubicacionMasCercana = $ubicacion;
            }
        }

        return [
            'permitido' => false,
            'ubicacion' => $ubicacionMasCercana,
            'distancia' => round($distanciaMinima, 2)
        ];
    }
}
