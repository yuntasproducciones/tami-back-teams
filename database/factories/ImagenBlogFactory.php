<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImagenBlog>
 */
class ImagenBlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url_imagen' => $this->faker->imageUrl(),
            'parrafo_imagen' => $this->faker->sentence(),
            'id_blog' => Blog::inRandomOrder()->first()->id
        ];
    }
}
