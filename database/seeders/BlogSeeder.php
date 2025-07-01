<?php

namespace Database\Seeders;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{

    public function run(): void
    {
        $blog = [
            [
                'producto_id'=>1,
                'titulo' => 'Panel de fibra de bamboo',
                'link' => '1',
                'subtitulo1' => 'Panel de Fibra de Bambú: Sostenibilidad y Estética para la construcción moderna',
                'subtitulo2' => 'Futuro verde en la construcción Beneficios del bambú',
                
                'imagen_principal' => 'https://i.imgur.com/bKisDUE.png',
                'video_url' => 'http://www.rosenbaum.info/est-enim-perspiciatis-voluptatem-dolore-beatae-eligendi',
                'video_titulo' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'created_at' => Carbon::now(),
            ],
            [
                'producto_id'=>2,
                'titulo' => 'Soldadora lingba',
                'link' => '2',
                'subtitulo1' => 'Diseño Sostenible: Interiores Eco-Friendly para Oficinas Modernas',
                'subtitulo2' => 'Descubre cómo incorporar materiales reciclados y energías renovables',
              
                'video_url' => 'http://www.rosenbaum.info/est-enim-perspiciatis-voluptatem-dolore-beatae-eligendi',
                'video_titulo' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'imagen_principal' => 'https://i.imgur.com/vgxpLns.png',
                'created_at' => Carbon::now(),
            ],
            [
                'producto_id'=>3,

                'titulo' => 'Soldadora spark',
                'link' => '3',
                'subtitulo1' => 'Iluminación Inteligente: Optimizando Espacios de Trabajo',
                'subtitulo2' => 'Explora las últimas tendencias en iluminación LED y sistemas de control',
              
                'imagen_principal' => 'https://i.imgur.com/ZfXUcxC.png',
                'video_url' => 'http://www.rosenbaum.info/est-enim-perspiciatis-voluptatem-dolore-beatae-eligendi',
                'video_titulo' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'created_at' => Carbon::now(),
            ],
            [
                'producto_id'=>4,
                'titulo' => 'Ventilador holográfico',
                'link' => '4',
                'subtitulo1' => 'Acústica en Restaurantes: Diseño para una Experiencia Culinaria Óptima',
                'subtitulo2' => 'Aprende sobre materiales y técnicas de diseño para crear ambientes acústicamente agradables',
                'imagen_principal' => 'https://i.imgur.com/ZgElRO5.png',
                'url_video' => 'http://www.rosenbaum.info/est-enim-perspiciatis-voluptatem-dolore-beatae-eligendi',
                'titulo_video' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'created_at' => Carbon::now(),
            ]
        ];
        DB::table('blogs')->insert($blog);
    }
}
