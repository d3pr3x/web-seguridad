<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolUsuario extends Model
{
    protected $table = 'roles_usuario';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['nombre', 'slug', 'descripcion'];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'rol_id');
    }
}
