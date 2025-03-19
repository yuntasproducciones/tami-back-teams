<?php

namespace Database\Seeders;

use App\Models\Blog;
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
                'parrafo' => 'Panel de Fibra de Bambú: Sostenibilidad y Estética para la construcción moderna',
                'descripcion' => 'Futuro verde en la construcción Beneficios del bambú',
                'imagen_principal' => 'panel-fibra-bambu.jpg'
            ],
            [
                'titulo' => 'Diseño Sostenible: Interiores Eco-Friendly para Oficinas Modernas',
                'parrafo' => 'Soldadora lingba',
                'descripcion' => 'Descubre cómo incorporar materiales reciclados y energías renovables',
                'imagen_principal' => 'soldadora-lingba.jpg'
            ],
            [
                'titulo' => 'Iluminación Inteligente: Optimizando Espacios de Trabajo',
                'parrafo' => 'Soldadora spark',
                'descripcion' => 'Explora las últimas tendencias en iluminación LED y sistemas de control',
                'imagen_principal' => 'soldadora-spark.jpg'
            ],
            [
                'titulo' => 'Acústica en Restaurantes: Diseño para una Experiencia Culinaria Óptima',
                'parrafo' => 'ventilador holográfico',
                'descripcion' => 'Aprende sobre materiales y técnicas de diseño para crear ambientes acústicamente agradables',
                'imagen_principal' => 'ventilador-holografico.jpg'
            ]
        ];
        DB::table('blogs')->insert($blog);
    }
}
