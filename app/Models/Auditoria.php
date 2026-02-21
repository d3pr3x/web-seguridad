<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditorias';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'empresa_id',
        'sucursal_id',
        'accion',
        'tabla',
        'registro_id',
        'route',
        'ip',
        'user_agent',
        'cambios_antes',
        'cambios_despues',
        'ocurrido_en',
        'metadata',
    ];

    protected $casts = [
        'cambios_antes' => 'array',
        'cambios_despues' => 'array',
        'metadata' => 'array',
        'ocurrido_en' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_usuario');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
