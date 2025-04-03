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
                'parrafo' => 'Panel de Fibra de Bambú: Sostenibilidad y Estética para la construcción moderna',
                'descripcion' => 'Futuro verde en la construcción Beneficios del bambú',
                'imagen_principal' => 'https://i.imgur.com/bKisDUE.png'
            ],
            [
                'titulo' => 'Soldadora lingba',
                'parrafo' => 'Diseño Sostenible: Interiores Eco-Friendly para Oficinas Modernas',
                'descripcion' => 'Descubre cómo incorporar materiales reciclados y energías renovables',
                'imagen_principal' => 'https://i.imgur.com/vgxpLns.png'
            ],
            [
                'titulo' => 'Soldadora spark',
                'parrafo' => 'Iluminación Inteligente: Optimizando Espacios de Trabajo',
                'descripcion' => 'Explora las últimas tendencias en iluminación LED y sistemas de control',
                'imagen_principal' => 'https://i.imgur.com/ZfXUcxC.png'
            ],
            [
                'titulo' => 'Ventilador holográfico',
                'parrafo' => 'Acústica en Restaurantes: Diseño para una Experiencia Culinaria Óptima',
                'descripcion' => 'Aprende sobre materiales y técnicas de diseño para crear ambientes acústicamente agradables',
                'imagen_principal' => 'https://i.imgur.com/ZgElRO5.png'
            ]
        ];
        DB::table('blogs')->insert($blog);
    }
}
