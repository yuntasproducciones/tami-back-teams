<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Productos;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Detalles_Productos>
 */
class Detalles_ProductosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prod_id' => Productos::all()->random()->prod_id,
            'titulo' => $this->faker->title(),
            'subtitulo' => $this->faker->sentence(),
            'descripcion' => $this->faker->text(), 
            'longitud' => $this->faker->randomFloat(2, 0, 99999999.99), 
            'ancho' => $this->faker->randomFloat(2, 0, 99999999.99), 
            'altura' => $this->faker->randomFloat(2, 0, 99999999.99), 
        ];
    }
}
