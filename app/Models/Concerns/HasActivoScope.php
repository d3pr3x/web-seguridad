<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Scope y método isActive() unificados para modelos con columna activo o activa.
 * Override activoColumn() en el modelo si usa 'activa' en lugar de 'activo'.
 */
trait HasActivoScope
{
    protected function activoColumn(): string
    {
        return 'activo';
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where($this->activoColumn(), true);
    }

    public function isActive(): bool
    {
        $col = $this->activoColumn();

        return (bool) ($this->attributes[$col] ?? false);
    }
}
