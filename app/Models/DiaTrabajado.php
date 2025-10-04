<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaTrabajado extends Model
{
    protected $table = 'dias_trabajados';
    
    protected $fillable = [
        'user_id',
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
        return $this->belongsTo(User::class);
    }
}
