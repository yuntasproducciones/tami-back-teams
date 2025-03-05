<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetalleBlog>
 */
class DetalleBlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo_blog' => $this->faker->title(),
            'subtitulo_beneficio' => $this->faker->sentence(),
            'id_blog' => Blog::inRandomOrder()->first()->id,
        ];
    }
}
