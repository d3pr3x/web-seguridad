<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rut = fake()->numerify('########') . '-' . fake()->randomElement(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'K']);
        return [
            'name' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'rut' => $rut,
            'perfil' => 4,
            'apellido' => fake()->lastName(),
            'fecha_nacimiento' => fake()->date(),
            'domicilio' => fake()->address(),
            'sucursal_id' => null,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function guardiaControlAcceso(): static
    {
        return $this->state(fn (array $attributes) => ['perfil' => 5]);
    }

    public function administrador(): static
    {
        return $this->state(fn (array $attributes) => ['perfil' => 1]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
