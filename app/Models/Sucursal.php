<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
    
    protected $fillable = [
        'nombre',
        'empresa',
        'codigo',
        'direccion',
        'comuna',
        'ciudad',
        'region',
        'telefono',
        'email',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    /**
     * Relación con usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relación con sectores
     */
    public function sectores()
    {
        return $this->hasMany(Sector::class);
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
     * Scope para sucursales activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }
}
