<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionSueldo extends Model
{
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
     * Scope para configuraciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Obtener multiplicador por tipo de día
     */
    public static function getMultiplicador($tipoDia)
    {
        $config = self::activas()->where('tipo_dia', $tipoDia)->first();
        return $config ? $config->multiplicador : 1.00;
    }
}
