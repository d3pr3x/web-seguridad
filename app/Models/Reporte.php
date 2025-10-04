<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $fillable = [
        'user_id',
        'tarea_id',
        'datos',
        'imagenes',
        'estado',
        'comentarios_admin',
    ];

    protected function casts(): array
    {
        return [
            'datos' => 'array',
            'imagenes' => 'array',
        ];
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Scope para reportes por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }
}
