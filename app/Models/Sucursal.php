<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    
    protected $fillable = [
        'nombre',
        'codigo',
        'direccion',
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
     * Scope para sucursales activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }
}
