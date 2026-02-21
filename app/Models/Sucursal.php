<?php

namespace App\Models;

use App\Models\Concerns\HasActivoScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sucursal extends Model
{
    use HasActivoScope, SoftDeletes;

    protected $table = 'sucursales';

    protected function activoColumn(): string
    {
        return 'activa';
    }

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
    
    protected $fillable = [
        'empresa_id',
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
     * Empresa (cliente) a la que pertenece esta instalación
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
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

    public function scopeActivas($query)
    {
        return $this->scopeActivos($query);
    }
}
