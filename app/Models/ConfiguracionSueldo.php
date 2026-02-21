<?php

namespace App\Models;

use App\Models\Concerns\HasActivoScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfiguracionSueldo extends Model
{
    use HasActivoScope, SoftDeletes;

    protected $table = 'configuraciones_sueldo';
    
    protected $fillable = [
        'tipo_dia',
        'multiplicador',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'multiplicador' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    /**
     * Obtener multiplicador por tipo de día
     */
    public static function getMultiplicador($tipoDia)
    {
        $config = self::activos()->where('tipo_dia', $tipoDia)->first();
        return $config ? $config->multiplicador : 1.00;
    }
}
