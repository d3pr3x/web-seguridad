<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reunion extends Model
{
    use SoftDeletes;

    protected $table = 'reuniones';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_reunion',
        'ubicacion',
        'id_usuario_creador',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_reunion' => 'datetime',
        ];
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'id_usuario_creador', 'id_usuario');
    }
}
