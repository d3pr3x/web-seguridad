<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Valida RUT chileno (formato y dígito verificador).
 * Acepta 12.345.678-9 o 12345678-9.
 */
class ChileRut implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) && !is_numeric($value)) {
            $fail('El RUT debe ser un valor válido.');
            return;
        }

        $rut = preg_replace('/[^0-9kK]/', '', strtoupper((string) $value));

        if (strlen($rut) < 8 || strlen($rut) > 9) {
            $fail('El RUT debe tener 8 o 9 dígitos más el dígito verificador.');
            return;
        }

        if (!preg_match('/^[0-9]{7,8}[0-9kK]$/', $rut)) {
            $fail('El formato del RUT no es válido (ej: 12.345.678-9).');
            return;
        }

        $dv = substr($rut, -1);
        $cuerpo = substr($rut, 0, -1);
        $suma = 0;
        $multiplier = 2;

        for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
            $suma += (int) $cuerpo[$i] * $multiplier;
            $multiplier = $multiplier === 7 ? 2 : $multiplier + 1;
        }

        $dvEsperado = 11 - ($suma % 11);
        $dvEsperado = $dvEsperado === 11 ? '0' : ($dvEsperado === 10 ? 'K' : (string) $dvEsperado);

        if (strtoupper($dv) !== $dvEsperado) {
            $fail('El dígito verificador del RUT no es válido.');
        }
    }
}
