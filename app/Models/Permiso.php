<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Permiso (tabla permisos). Se asigna a roles vía rol_permiso. La autorización
 * del proyecto se basa en roles_usuario/rol_id; los permisos son opcionales para grano fino.
 */
class Permiso extends Model
{
    use SoftDeletes;

    protected $table = 'permisos';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['nombre', 'slug', 'descripcion'];

    /**
     * Roles que tienen este permiso. Excluye asignaciones soft-deleted en el pivot.
     */
    public function roles()
    {
        return $this->belongsToMany(RolUsuario::class, 'rol_permiso', 'permiso_id', 'rol_id')
            ->wherePivotNull('deleted_at');
    }
}
