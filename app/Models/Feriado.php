<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Feriado extends Model
{
    protected $fillable = [
        'nombre',
        'fecha',
        'irrenunciable',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'irrenunciable' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    /**
     * Scope para feriados activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Verificar si una fecha es feriado
     */
    public static function esFeriado($fecha)
    {
        return self::activos()->where('fecha', $fecha)->exists();
    }

    /**
     * Obtener feriado por fecha
     */
    public static function getFeriado($fecha)
    {
        return self::activos()->where('fecha', $fecha)->first();
    }

    /**
     * Verificar si es día hábil (lunes a viernes, no feriado)
     */
    public static function esDiaHabil($fecha)
    {
        $carbon = Carbon::parse($fecha);
        $esFinDeSemana = $carbon->isWeekend();
        $esFeriado = self::esFeriado($fecha);
        
        return !$esFinDeSemana && !$esFeriado;
    }

    /**
     * Obtener tipo de día
     */
    public static function getTipoDia($fecha)
    {
        $carbon = Carbon::parse($fecha);
        
        if (self::esFeriado($fecha)) {
            return 'feriado';
        }
        
        if ($carbon->isSunday()) {
            return 'domingo';
        }
        
        if ($carbon->isSaturday()) {
            return 'sabado';
        }
        
        return 'habil';
    }
}
