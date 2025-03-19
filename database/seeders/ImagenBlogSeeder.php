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
        // $image = [
        //     [
        //         'url_imagen',
        //         'parrafo_imagen',
        //         'id_blog'
        //     ]
        // ];
        
        ImagenBlog::factory(10)->create();
    }
}
