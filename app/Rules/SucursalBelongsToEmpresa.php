<?php

namespace App\Rules;

use App\Models\Sucursal;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SucursalBelongsToEmpresa implements ValidationRule
{
    public function __construct(
        protected ?int $empresaId = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }
        $empresaId = $this->empresaId ?? request()->input('empresa_id');
        if (! $empresaId) {
            return;
        }
        $sucursal = Sucursal::find($value);
        if (! $sucursal || (int) $sucursal->empresa_id !== (int) $empresaId) {
            $fail('La sucursal seleccionada no pertenece a la empresa indicada.');
        }
    }
}
