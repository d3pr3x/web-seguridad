<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Punto 2: Tipos de incidente/delito dentro de un grupo.
 */
class TipoIncidente extends Model
{
    protected $table = 'tipos_incidente';

    protected $fillable = ['grupo_id', 'nombre', 'slug', 'orden', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function grupo()
    {
        return $this->belongsTo(GrupoIncidente::class, 'grupo_id');
    }
}
