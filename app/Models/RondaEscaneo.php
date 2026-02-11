<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RondaEscaneo extends Model
{
    protected $table = 'ronda_escaneos';

    protected $fillable = [
        'punto_ronda_id',
        'user_id',
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
        return $this->belongsTo(User::class);
    }
}
