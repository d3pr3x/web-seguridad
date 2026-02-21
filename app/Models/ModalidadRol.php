<?php

namespace App\Models;

use App\Models\Concerns\HasActivoScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModalidadRol extends Model
{
    use HasActivoScope, SoftDeletes;

    protected $table = 'modalidad_roles';

    protected $fillable = ['modalidad_id', 'rol_id', 'orden', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function modalidad()
    {
        return $this->belongsTo(ModalidadJerarquia::class, 'modalidad_id');
    }

    public function rol()
    {
        return $this->belongsTo(RolUsuario::class, 'rol_id');
    }
}
