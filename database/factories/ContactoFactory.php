<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contacto>
 */
class ContactoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'apellido' =>$this->faker->lastName(),
            'telefono' => $this->faker->regexify('9\d{8}'), 
            'email' => $this->faker->email(),
            'seccion' => $this->faker->name(),
            'fecha_creacion' => now()->format('Y-m-d'),
        ];
    }
}
