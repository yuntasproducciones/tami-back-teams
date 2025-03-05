<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VideoBlog>
 */
class VideoBlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url_video' => $this->faker->url(),
            'titulo_video' => $this->faker->title(),
            'id_blog' => Blog::inRandomOrder()->first()->id,
        ];
    }
}
