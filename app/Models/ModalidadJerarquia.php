<?php

namespace App\Models;

use App\Models\Concerns\HasActivoScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModalidadJerarquia extends Model
{
    use HasActivoScope, SoftDeletes;

    protected $table = 'modalidades_jerarquia';

    protected $fillable = ['nombre', 'descripcion', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Roles de esta modalidad ordenados por pivot.orden. Excluye filas soft-deleted en el pivot.
     */
    public function rolesOrdenados()
    {
        return $this->belongsToMany(RolUsuario::class, 'modalidad_roles', 'modalidad_id', 'rol_id')
            ->withPivot('orden', 'activo')
            ->withTimestamps()
            ->wherePivotNull('deleted_at')
            ->orderByPivot('orden');
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'modalidad_id');
    }
}
