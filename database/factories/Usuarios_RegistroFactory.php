<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Seccion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuarios_Registro>
 */
class Usuarios_RegistroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'celular'=> $this->faker->numerify('9########'),
            'fecha' => now()->format('Y-m-d'),
            'sec_id' => Seccion::all()->random()->sec_id,
        ];
    }
}
