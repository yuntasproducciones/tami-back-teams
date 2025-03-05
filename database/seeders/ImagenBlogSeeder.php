<?php

namespace Database\Seeders;

use App\Models\ImagenBlog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImagenBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ImagenBlog::factory(10)->create();
    }
}
