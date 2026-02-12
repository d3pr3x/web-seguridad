<?php

namespace Database\Factories;

use App\Models\Blacklist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blacklist>
 */
class BlacklistFactory extends Factory
{
    protected $model = Blacklist::class;

    public function definition(): array
    {
        return [
            'rut' => fake()->numerify('########') . '-' . fake()->randomElement(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'K']),
            'patente' => fake()->optional(0.5)->passthrough(strtoupper(fake()->bothify('????##'))),
            'motivo' => fake()->sentence(),
            'fecha_inicio' => fake()->dateTimeBetween('-1 month', 'now'),
            'fecha_fin' => fake()->optional(0.3)->dateTimeBetween('now', '+1 month'),
            'activo' => true,
            'created_by' => User::query()->inRandomOrder()->first()?->id,
        ];
    }
}
