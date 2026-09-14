<?php

namespace Database\Factories;

use App\Models\ComponenteComputador;
use App\Models\Computador;
use App\Models\PecaDesejada;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PecaDesejada>
 */
class PecaDesejadaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'componente_computadore_id' => ComponenteComputador::factory(),
            'computador_id' => Computador::factory(),
            'descricao' => fake()->words(3, true),
            'quantidade' => fake()->numberBetween(1, 4),
            'link_inicial' => fake()->url(),
            'valor_inicial' => fake()->randomFloat(2, 100, 5000),
        ];
    }
}
