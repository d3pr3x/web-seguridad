<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RondaEscaneo extends Model
{
    use SoftDeletes;

    protected $table = 'escaneos_ronda';

    protected $fillable = [
        'punto_ronda_id',
        'id_usuario',
        'escaneado_en',
        'lat',
        'lng',
    ];

    protected function casts(): array
    {
        return [
            'escaneado_en' => 'datetime',
        ];
    }

    public function puntoRonda()
    {
        return $this->belongsTo(PuntoRonda::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
