<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Reporte extends Model
{
    protected static function booted(): void
    {
        static::saving(function (Reporte $reporte) {
            if ($reporte->id_usuario !== null && Schema::hasColumn($reporte->getTable(), 'user_id')) {
                $reporte->user_id = $reporte->id_usuario;
            }
            // No intentar guardar user_id si la columna no existe (p. ej. en algunas BD)
            if (! Schema::hasColumn($reporte->getTable(), 'user_id') && array_key_exists('user_id', $reporte->getAttributes())) {
                $reporte->offsetUnset('user_id');
            }
        });
    }

    protected $fillable = [
        'id_usuario',
        'user_id',
        'tarea_id',
        'datos',
        'imagenes',
        'latitud',
        'longitud',
        'precision',
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
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
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
