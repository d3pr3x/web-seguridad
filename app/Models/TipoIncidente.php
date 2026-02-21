<?php

namespace App\Models;

use App\Models\Concerns\HasActivoScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Punto 2: Tipos de incidente/delito dentro de un grupo.
 */
class TipoIncidente extends Model
{
    use HasActivoScope, SoftDeletes;

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
