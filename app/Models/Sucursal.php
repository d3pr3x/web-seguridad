<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    
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
     * Scope para sucursales activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }
}
