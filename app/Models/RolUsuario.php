<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Rol de usuario (tabla roles_usuario). Los perfiles y permisos se basan solo en esta tabla
 * y en rol_permiso/permisos; no se usa Spatie ni paquetes externos de roles.
 */
class RolUsuario extends Model
{
    use SoftDeletes;

    protected $table = 'roles_usuario';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['nombre', 'slug', 'descripcion'];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'rol_id');
    }

    /**
     * Permisos asignados al rol (tabla pivot rol_permiso).
     * Excluye asignaciones soft-deleted en el pivot.
     */
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'rol_permiso', 'rol_id', 'permiso_id')
            ->wherePivotNull('deleted_at');
    }
}
