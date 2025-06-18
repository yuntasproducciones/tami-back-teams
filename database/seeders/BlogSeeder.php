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
                'producto_id' => 1,
                'link' => 'panel-fibra-bambu',
                'parrafo' => 'Panel de Fibra de Bambú: Sostenibilidad y Estética para la construcción moderna',
                'descripcion' => 'Futuro verde en la construcción Beneficios del bambú',
                'imagen_principal' => 'https://i.imgur.com/bKisDUE.png',
                'created_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Soldadora lingba',
                'producto_id' => 2,
                'link' => 'soldadora-lingba',
                'parrafo' => 'Diseño Sostenible: Interiores Eco-Friendly para Oficinas Modernas',
                'descripcion' => 'Descubre cómo incorporar materiales reciclados y energías renovables',
                'imagen_principal' => 'https://i.imgur.com/vgxpLns.png',
                'created_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Soldadora spark',
                'producto_id' => 3,
                'link' => 'soldadora-spark',
                'parrafo' => 'Iluminación Inteligente: Optimizando Espacios de Trabajo',
                'descripcion' => 'Explora las últimas tendencias en iluminación LED y sistemas de control',
                'imagen_principal' => 'https://i.imgur.com/ZfXUcxC.png',
                'created_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Ventilador holográfico',
                'producto_id' => 4,
                'link' => 'ventilador-holografico',
                'parrafo' => 'Acústica en Restaurantes: Diseño para una Experiencia Culinaria Óptima',
                'descripcion' => 'Aprende sobre materiales y técnicas de diseño para crear ambientes acústicamente agradables',
                'imagen_principal' => 'https://i.imgur.com/ZgElRO5.png',
                'created_at' => Carbon::now(),
            ]
        ];
        DB::table('blogs')->insert($blog);
    }
}
