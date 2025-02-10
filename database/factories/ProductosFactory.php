<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Categorias;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Productos>
 */
class ProductosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imagen_principal' => $this->faker->imageUrl(),
            'imagen_sec_1' => $this->faker->imageUrl(),
            'imagen_sec_2' => $this->faker->imageUrl(),
            'imagen_sec_3' => $this->faker->imageUrl(),
            'descripcion' => $this->faker->text(),
            'cat_id' => Categorias::all()->random()->cat_id,
        ];
    }
}
