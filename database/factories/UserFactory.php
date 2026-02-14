<?php

namespace Database\Factories;

use App\Models\RolUsuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $run = fake()->numerify('########') . fake()->randomElement(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'K']);
        $rolId = RolUsuario::query()->where('slug', 'USUARIO')->value('id') ?? 4;
        return [
            'run' => $run,
            'nombre_completo' => fake()->firstName() . ' ' . fake()->lastName(),
            'rango' => null,
            'email' => fake()->unique()->safeEmail(),
            'clave' => static::$password ??= Hash::make('password'),
            'fecha_nacimiento' => fake()->date(),
            'domicilio' => fake()->address(),
            'rol_id' => $rolId,
            'sucursal_id' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function guardiaControlAcceso(): static
    {
        $rolId = RolUsuario::query()->where('slug', 'GUARDIA')->value('id') ?? 5;
        return $this->state(fn (array $attributes) => ['rol_id' => $rolId]);
    }

    public function administrador(): static
    {
        $rolId = RolUsuario::query()->where('slug', 'ADMIN')->value('id') ?? 1;
        return $this->state(fn (array $attributes) => ['rol_id' => $rolId]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verificado_en' => null,
        ]);
    }
}
