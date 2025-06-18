<?php

namespace Database\Seeders;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlogImagenes;
use App\Models\BlogParrafos;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $blog1 = Blog::create([
                'titulo' => 'Panel de fibra de bamboo',
                'producto_id' => 1,
                'link' => 'panel-fibra-bambu',
                'subtitulo1' => 'Panel de Fibra de Bambú: Sostenibilidad y Estética para la construcción moderna',
                'subtitulo2' => 'Futuro verde en la construcción Beneficios del bambú',
                'subtitulo3' => 'Futuro verde en la construcción  Beneficios del bambú',
                'video_url' => 'https://youtu.be/rmLrwbUiRrs?si=v9eZvgvF2KpGzeVi',
                'video_titulo' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'created_at' => Carbon::now()
            ]);

            $parrafos1 = [
                'El bambú se ha convertido en uno de los materiales más revolucionarios en la industria de la construcción gracias a su rápido crecimiento, su resistencia y su impacto ambiental reducido. En comparación con la madera tradicional, el bambú es una opción altamente renovable, ya que puede crecer hasta un metro por día y regenerarse después de la cosecha sin necesidad de replantación.',
            ];

            foreach ($parrafos1 as $contenido) {
                BlogParrafos::create([
                    'parrafo' => $contenido,
                    'blog_id' => $blog1->id
                ]);
            }

            $imagenes1 = [
                [
                    'ruta_imagen' => 'https://i.imgur.com/bKisDUE.png',
                    'texto_alt' => 'Panel de Fibra de Bambú'
                ],
            ];

            foreach ($imagenes1 as $imagen) {
                BlogImagenes::create([
                    'ruta_imagen' => $imagen['ruta_imagen'],
                    'texto_alt' => $imagen['texto_alt'],
                    'blog_id' => $blog1->id
                ]);
            }
    
//=======================BLOG 2=========================

            $blog2 = Blog::create([
                'titulo' => 'Soldadora lingba',
                'producto_id' => 2,
                'link' => 'soldadora-lingba',
                'subtitulo1' => 'Diseño Sostenible: Interiores Eco-Friendly para Oficinas Modernas',
                'subtitulo2' => 'Descubre cómo incorporar materiales reciclados y energías renovables',
                'subtitulo3' => 'Soldadora Lingba: Innovación y Eficiencia en la Industria de la Construcción',
                'video_url' => 'https://youtu.be/p6QQtwnVPOE?si=725EhgfUmkx-2fyU',
                'video_titulo' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'created_at' => Carbon::now()
            ]);

            $parrafos2 = [
                'La soldadora Lingba es una herramienta esencial para la industria de la construcción, diseñada para ofrecer un rendimiento excepcional y una durabilidad inigualable. Con su tecnología avanzada, esta soldadora permite realizar uniones de alta calidad en una variedad de materiales, garantizando resultados precisos y resistentes.',
            ];

            foreach ($parrafos2 as $contenido) {
                BlogParrafos::create([
                    'parrafo' => $contenido,
                    'blog_id' => $blog2->id
                ]);
            }

            $imagenes2 = [
                [
                    'ruta_imagen' => 'https://i.imgur.com/vgxpLns.png',
                    'texto_alt' => 'Soldadora Lingba'
                ],
            ];

            foreach ($imagenes2 as $imagen) {
                BlogImagenes::create([
                    'ruta_imagen' => $imagen['ruta_imagen'],
                    'texto_alt' => $imagen['texto_alt'],
                    'blog_id' => $blog2->id
                ]);
            }

//=======================BLOG 3=========================
            $blog3 = Blog::create([
                'titulo' => 'Soldadora spark',
                'producto_id' => 3,
                'link' => 'soldadora-spark',
                'subtitulo1' => 'Iluminación Inteligente: Optimizando Espacios de Trabajo',
                'subtitulo2' => 'Explora las últimas tendencias en iluminación LED y sistemas de control',
                'subtitulo3' => 'Soldadora Spark: Innovación y Eficiencia en la Industria de la Construcción',
                'video_url' => 'https://youtu.be/kVFGNUe12eM?si=lygYRD8R-OcrBYwb',
                'video_titulo' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'created_at' => Carbon::now()
            ]);

            $parrafos3 = [
                'La soldadora Spark es una herramienta esencial para la industria de la construcción, diseñada para ofrecer un rendimiento excepcional y una durabilidad inigualable. Con su tecnología avanzada, esta soldadora permite realizar uniones de alta calidad en una variedad de materiales, garantizando resultados precisos y resistentes.',
            ];

            foreach ($parrafos3 as $contenido) {
                BlogParrafos::create([
                    'parrafo' => $contenido,
                    'blog_id' => $blog3->id
                ]);
            }

            $imagenes3 = [
                [
                    'ruta_imagen' => 'https://i.imgur.com/ZfXUcxC.png',
                    'texto_alt' => 'Soldadora Spark'
                ],
            ];

            foreach ($imagenes3 as $imagen) {
                BlogImagenes::create([
                    'ruta_imagen' => $imagen['ruta_imagen'],
                    'texto_alt' => $imagen['texto_alt'],
                    'blog_id' => $blog3->id
                ]);
            }

//=======================BLOG 4=========================
            $blog4 = Blog::create([
                'titulo' => 'Ventilador holográfico',
                'producto_id' => 4,
                'link' => 'ventilador-holografico',
                'subtitulo1' => 'Acústica en Restaurantes: Diseño para una Experiencia Culinaria Óptima',
                'subtitulo2' => 'Aprende sobre materiales y técnicas de diseño para crear ambientes acústicamente agradables',
                'subtitulo3' => 'Ventilador Holográfico: Innovación y Eficiencia en la Industria de la Construcción',
                'video_url' => 'https://youtu.be/2_Nj6_8Bsao?si=WZKCQBSgvOU3UcTA',
                'video_titulo' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'created_at' => Carbon::now()
            ]);

            $parrafos4 = [
                'El ventilador holográfico es una herramienta esencial para la industria de la construcción, diseñada para ofrecer un rendimiento excepcional y una durabilidad inigualable. Con su tecnología avanzada, este ventilador permite realizar uniones de alta calidad en una variedad de materiales, garantizando resultados precisos y resistentes.',
            ];

            foreach ($parrafos4 as $contenido) {
                BlogParrafos::create([
                    'parrafo' => $contenido,
                    'blog_id' => $blog4->id
                ]);
            }

            $imagenes4 = [
                [
                    'ruta_imagen' => 'https://i.imgur.com/ZgElRO5.png',
                    'texto_alt' => 'Ventilador Holográfico'
                ],
            ];

            foreach ($imagenes4 as $imagen) {
                BlogImagenes::create([
                    'ruta_imagen' => $imagen['ruta_imagen'],
                    'texto_alt' => $imagen['texto_alt'],
                    'blog_id' => $blog4->id
                ]);
            }

    }
}
