<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Punto 2: Grupos de delitos/incidentes (ej. delitos contra la propiedad, contra las personas).
 */
class GrupoIncidente extends Model
{
    protected $table = 'grupos_incidentes';

    protected $fillable = ['nombre', 'slug', 'descripcion', 'orden', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function tiposIncidente()
    {
        return $this->hasMany(TipoIncidente::class, 'grupo_id')->where('activo', true)->orderBy('orden');
    }
}
