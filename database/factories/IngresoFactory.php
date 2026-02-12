<?php

namespace Database\Factories;

use App\Models\Ingreso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingreso>
 */
class IngresoFactory extends Factory
{
    protected $model = Ingreso::class;

    public function definition(): array
    {
        $tipo = fake()->randomElement(['peatonal', 'vehicular']);
        $rut = fake()->numerify('########') . '-' . fake()->randomElement(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'K']);
        $nombre = fake()->name();
        $patente = $tipo === 'vehicular' ? strtoupper(fake()->bothify('????##')) : null;

        return [
            'tipo' => $tipo,
            'rut' => $rut,
            'nombre' => $nombre,
            'patente' => $patente,
            'guardia_id' => User::query()->inRandomOrder()->first()?->id ?? 1,
            'fecha_ingreso' => fake()->dateTimeBetween('-7 days', 'now'),
            'fecha_salida' => fake()->optional(0.5)->dateTimeBetween('-6 days', 'now'),
            'estado' => fake()->randomElement(['ingresado', 'salida', 'bloqueado']),
            'alerta_blacklist' => false,
            'ip_ingreso' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}
