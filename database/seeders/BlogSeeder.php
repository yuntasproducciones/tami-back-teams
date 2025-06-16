<?php

namespace Database\Seeders;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blog = [
            [
                'titulo' => 'Panel de fibra de bamboo',
                'subtitulo1' => 'Panel de Fibra de Bambú: Sostenibilidad y Estética para la construcción moderna',
                'subtitulo2' => 'Futuro verde en la construcción Beneficios del bambú',
                'imagen_principal' => 'https://i.imgur.com/bKisDUE.png',
                'created_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Soldadora lingba',
                'subtitulo1' => 'Diseño Sostenible: Interiores Eco-Friendly para Oficinas Modernas',
                'subtitulo2' => 'Descubre cómo incorporar materiales reciclados y energías renovables',
                'imagen_principal' => 'https://i.imgur.com/vgxpLns.png',
                'created_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Soldadora spark',
                'subtitulo1' => 'Iluminación Inteligente: Optimizando Espacios de Trabajo',
                'subtitulo2' => 'Explora las últimas tendencias en iluminación LED y sistemas de control',
                'imagen_principal' => 'https://i.imgur.com/ZfXUcxC.png',
                'created_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Ventilador holográfico',
                'subtitulo1' => 'Acústica en Restaurantes: Diseño para una Experiencia Culinaria Óptima',
                'subtitulo2' => 'Aprende sobre materiales y técnicas de diseño para crear ambientes acústicamente agradables',
                'imagen_principal' => 'https://i.imgur.com/ZgElRO5.png',
                'created_at' => Carbon::now(),
            ]
        ];
        DB::table('blogs')->insert($blog);
    }
}
