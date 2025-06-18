<?php

namespace Database\Seeders;

use App\Models\VideoBlog;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $video = [
            [
                'url_video' => 'https://youtu.be/rmLrwbUiRrs?si=v9eZvgvF2KpGzeVi',
                'titulo_video' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'id_blog' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_video' => 'https://youtu.be/p6QQtwnVPOE?si=725EhgfUmkx-2fyU',
                'titulo_video' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'id_blog' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_video' => 'https://youtu.be/kVFGNUe12eM?si=lygYRD8R-OcrBYwb',
                'titulo_video' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'id_blog' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_video' => 'https://youtu.be/2_Nj6_8Bsao?si=WZKCQBSgvOU3UcTA',
                'titulo_video' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'id_blog' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('video_blogs')->insert($video);
    }
}
