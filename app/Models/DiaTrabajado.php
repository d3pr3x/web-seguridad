<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaTrabajado extends Model
{
    protected $table = 'dias_trabajados';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
    
    protected $fillable = [
        'id_usuario',
        'fecha',
        'ponderacion',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'ponderacion' => 'decimal:2',
        ];
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
