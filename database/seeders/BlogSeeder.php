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
                'imagen_principal' => 'https://img.interempresas.net/fotos/3883098.jpeg'
            ],
            [
                'titulo' => 'Soldadora lingba',
                'parrafo' => 'Diseño Sostenible: Interiores Eco-Friendly para Oficinas Modernas',
                'descripcion' => 'Descubre cómo incorporar materiales reciclados y energías renovables',
                'imagen_principal' => 'https://media.istockphoto.com/id/1488819764/es/foto/soldador-industrial.jpg?s=612x612&w=0&k=20&c=J4iMWq-E0X48DiEzPblLKVCPNmEzfn6QXw_zZFzyhGA='
            ],
            [
                'titulo' => 'Soldadora spark',
                'parrafo' => 'Iluminación Inteligente: Optimizando Espacios de Trabajo',
                'descripcion' => 'Explora las últimas tendencias en iluminación LED y sistemas de control',
                'imagen_principal' => 'https://s.alicdn.com/@sc04/kf/H8fd5f5ef870d485ca434275360cfb246q.jpg_300x300.jpg'
            ],
            [
                'titulo' => 'Ventilador holográfico',
                'parrafo' => 'Acústica en Restaurantes: Diseño para una Experiencia Culinaria Óptima',
                'descripcion' => 'Aprende sobre materiales y técnicas de diseño para crear ambientes acústicamente agradables',
                'imagen_principal' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5b9HVA0zU8WDAnAo2ilVQp2f0EdbkPfqXnZIWqiicVzHkJnnA'
            ]
        ];
        DB::table('blogs')->insert($blog);
    }
}
