<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntoRonda extends Model
{
    protected $table = 'puntos_ronda';

    protected $fillable = [
        'sucursal_id',
        'sector_id',
        'nombre',
        'codigo',
        'descripcion',
        'orden',
        'lat',
        'lng',
        'distancia_maxima_metros',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function escaneos()
    {
        return $this->hasMany(RondaEscaneo::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }

    /**
     * Indica si el punto tiene ubicación configurada (necesaria para validar escaneo in situ).
     */
    public function tieneUbicacion(): bool
    {
        return $this->lat !== null && $this->lng !== null;
    }

    /**
     * Distancia máxima en metros para aceptar un escaneo (configurable por punto por el administrador).
     */
    public function getDistanciaMaximaMetros(): int
    {
        $valor = $this->distancia_maxima_metros;
        return $valor !== null && $valor > 0 ? (int) $valor : 10;
    }
}
