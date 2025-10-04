<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TareaDetalle extends Model
{
    protected $table = 'tarea_detalles';
    
    protected $fillable = [
        'tarea_id',
        'campo_nombre',
        'tipo_campo',
        'opciones',
        'requerido',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'requerido' => 'boolean',
            'opciones' => 'array',
        ];
    }

    /**
     * Relación con tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
}
