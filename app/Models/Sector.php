<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = 'sectores';
    
    protected $fillable = [
        'sucursal_id',
        'nombre',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    /**
     * Relación con sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relación con acciones
     */
    public function acciones()
    {
        return $this->hasMany(Accion::class);
    }

    /**
     * Relación con reportes especiales
     */
    public function reportesEspeciales()
    {
        return $this->hasMany(ReporteEspecial::class);
    }

    /**
     * Relación con puntos de ronda (QR)
     */
    public function puntosRonda()
    {
        return $this->hasMany(PuntoRonda::class);
    }

    /**
     * Scope para sectores activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para sectores por sucursal
     */
    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }
}




