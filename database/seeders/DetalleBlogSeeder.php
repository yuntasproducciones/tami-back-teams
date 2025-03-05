<?php

namespace Database\Seeders;

use App\Models\DetalleBlog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetalleBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetalleBlog::factory(10)->create();
    }
}
