<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImeiPermitido extends Model
{
    protected $fillable = [
        'imei',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Verificar si un IMEI está permitido
     */
    public static function isPermitido($imei)
    {
        return self::where('imei', $imei)
                   ->where('activo', true)
                   ->exists();
    }
}
