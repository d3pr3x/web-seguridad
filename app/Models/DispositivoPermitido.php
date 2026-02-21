<?php

namespace App\Models;

use App\Models\Concerns\HasActivoScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DispositivoPermitido extends Model
{
    use HasFactory, HasActivoScope, SoftDeletes;

    protected $table = 'dispositivos_permitidos';

    protected $fillable = [
        'browser_fingerprint',
        'descripcion',
        'activo',
        'requiere_ubicacion',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'requiere_ubicacion' => 'boolean',
    ];

    /**
     * Verificar si un fingerprint está permitido
     */
    public static function isPermitido($fingerprint)
    {
        if (!$fingerprint) {
            return false;
        }

        return self::where('browser_fingerprint', $fingerprint)
            ->where('activo', true)
            ->exists();
    }

    /**
     * Obtener o crear un dispositivo permitido
     */
    public static function obtenerOCrear($fingerprint, $descripcion = null)
    {
        return self::firstOrCreate(
            ['browser_fingerprint' => $fingerprint],
            [
                'descripcion' => $descripcion ?? 'Dispositivo registrado el ' . now()->format('d/m/Y H:i'),
                'activo' => true,
                'requiere_ubicacion' => true,
            ]
        );
    }

    /**
     * Verificar si un dispositivo requiere validación de ubicación
     */
    public static function requiereUbicacion($fingerprint)
    {
        $dispositivo = self::where('browser_fingerprint', $fingerprint)
            ->where('activo', true)
            ->first();

        return $dispositivo ? $dispositivo->requiere_ubicacion : true;
    }
}
