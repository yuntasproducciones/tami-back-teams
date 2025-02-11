<?php

namespace Database\Factories;

use App\Models\Comentarios;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seccion>
 */
class SeccionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seccion' => $this->faker->sentence(6),
            'id_coment' => Comentarios::all()->random()->id_coment
        ];
    }
}
