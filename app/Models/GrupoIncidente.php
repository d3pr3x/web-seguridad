<?php

namespace App\Models;

use App\Models\Concerns\HasActivoScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Punto 2: Grupos de delitos/incidentes (ej. delitos contra la propiedad, contra las personas).
 */
class GrupoIncidente extends Model
{
    use HasActivoScope, SoftDeletes;

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
