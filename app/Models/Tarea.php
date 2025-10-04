<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    /**
     * Relación con detalles de tarea
     */
    public function detalles()
    {
        return $this->hasMany(TareaDetalle::class)->orderBy('orden');
    }

    /**
     * Relación con reportes
     */
    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    /**
     * Scope para tareas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }
}
