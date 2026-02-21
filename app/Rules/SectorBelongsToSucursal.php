<?php

namespace App\Rules;

use App\Models\Sector;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SectorBelongsToSucursal implements ValidationRule
{
    public function __construct(
        protected ?int $sucursalId = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }
        $sucursalId = $this->sucursalId ?? request()->input('sucursal_id');
        if (! $sucursalId) {
            return;
        }
        $sector = Sector::find($value);
        if (! $sector || (int) $sector->sucursal_id !== (int) $sucursalId) {
            $fail('El sector seleccionado no pertenece a la sucursal indicada.');
        }
    }
}
