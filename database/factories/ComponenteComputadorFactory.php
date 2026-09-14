<?php

namespace Database\Factories;

use App\Models\ComponenteComputador;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComponenteComputador>
 */
class ComponenteComputadorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->randomElement(['Placa de Vídeo', 'Processador', 'Placa-mãe', 'Memória RAM', 'SSD', 'Fonte']),
            'descricao' => fake()->sentence(),
        ];
    }
}
