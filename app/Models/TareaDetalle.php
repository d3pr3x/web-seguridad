<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TareaDetalle extends Model
{
    use SoftDeletes;

    protected $table = 'detalles_tarea';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
    
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
