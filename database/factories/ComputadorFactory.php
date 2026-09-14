<?php

namespace Database\Factories;

use App\Models\Computador;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Computador>
 */
class ComputadorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->words(3, true),
            'descricao' => fake()->sentence(),
            'status' => fake()->randomElement(['Ativo', 'Inativo', 'Comprado', 'Esperando Milagre']),
            'user_id' => User::factory(),
        ];
    }
}
