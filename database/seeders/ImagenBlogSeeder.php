<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImagenBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imagenesBlog = [
            [
                'id_blog' => 1,
                'url_imagen' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxITEhUSEhIVFRUXGBoYFhcVFxUVFRUVGBcaFxUVGBUYHSggGBolHRgXITEhJSkrLi4uFx8zODMtNygtLi0BCgoKDg0OGxAQFy0mHR0tLS0tLS03LS0tLS0tLS0tLS0tLS0wLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAOEA4QMBIgACEQEDEQH/xAAcAAACAgMBAQAAAAAAAAAAAAADBAACAQUGBwj/xABKEAABAgIDCQwHBQgDAAMAAAABAAIDEQQhMRJBUWFxcrIGIiMyMzRSkbHR8BNCU4GhwtIVJENigwcUU2OSorPhF5PxFkRUg/+QAGQEBAQADAQAAAAAAAAAAAAAAAAECAwQF/8QAKREBAAEDAgYCAgIDAAAAAAAAAAECAxExQQQSEyEyYVFxQoEUwSIzUv/aAAwDAQACEQMRAD8As08B2eNYo7eel+XYUGGzgP6xusjNHH+74rFQI',
                'parrafo_imagen' => 'El bambú se ha convertido en uno de los materiales más revolucionarios en la industria de la construcción gracias a su rápido crecimiento, su resistencia y su impacto ambiental reducido. En comparación con la madera tradicional, el bambú es una opción altamente renovable, ya que puede crecer hasta un metro por día y regenerarse después de la cosecha sin necesidad de replantación.
                Los paneles de fibra de bambú son el resultado de un proceso innovador que convierte esta materia prima en una superficie resistente, flexible y de gran atractivo visual. Estos paneles han demostrado ser una solución ideal para proyectos arquitectónicos que buscan equilibrar funcionalidad, estética y sostenibilidad.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_blog' => 1,
                'url_imagen' => 'https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcSppkDE26380kKF7nzUswbcVkEVHKixWJtRlhr_l8YEshN9nJuR',
                'parrafo_imagen' => 'Los paneles de fibra de bambú combinan sostenibilidad, resistencia y estética, convirtiéndose en una excelente opción para interiores y exteriores. Su acabado natural aporta calidez y se adapta a diferentes estilos decorativos, desde modernos hasta rústicos.
                Son altamente resistentes, comparables a algunas maderas duras, pero con mayor flexibilidad y menor riesgo de fracturas. Además, su producción tiene un bajo impacto ambiental, ya que el bambú crece rápidamente y se regenera sin necesidad de replantación, reduciendo la tala de bosques.
                Su textura elegante y sus tonos neutros permiten distintos acabados, como mate, brillante o envejecido. Requieren poco mantenimiento, siendo resistentes a la humedad y al desgaste. Gracias a estas características, los paneles de fibra de bambú son ideales para revestimientos, mobiliario y proyectos de construcción sostenible.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_blog' => 2,
                'url_imagen' => 'https://ejemplo.com/imagen-blog-2-1.jpg',
                'parrafo_imagen' => 'El bambú se ha convertido en uno de los materiales más revolucionarios en la industria de la construcción gracias a su rápido crecimiento, su resistencia y su impacto ambiental reducido. En comparación con la madera tradicional, el bambú es una opción altamente renovable, ya que puede crecer hasta un metro por día y regenerarse después de la cosecha sin necesidad de replantación.
                Los paneles de fibra de bambú son el resultado de un proceso innovador que convierte esta materia prima en una superficie resistente, flexible y de gran atractivo visual. Estos paneles han demostrado ser una solución ideal para proyectos arquitectónicos que buscan equilibrar funcionalidad, estética y sostenibilidad.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_blog' => 2,
                'url_imagen' => 'https://ejemplo.com/imagen-blog-2-2.jpg',
                'parrafo_imagen' => 'Los paneles de fibra de bambú combinan sostenibilidad, resistencia y estética, convirtiéndose en una excelente opción para interiores y exteriores. Su acabado natural aporta calidez y se adapta a diferentes estilos decorativos, desde modernos hasta rústicos.
                Son altamente resistentes, comparables a algunas maderas duras, pero con mayor flexibilidad y menor riesgo de fracturas. Además, su producción tiene un bajo impacto ambiental, ya que el bambú crece rápidamente y se regenera sin necesidad de replantación, reduciendo la tala de bosques.
                Su textura elegante y sus tonos neutros permiten distintos acabados, como mate, brillante o envejecido. Requieren poco mantenimiento, siendo resistentes a la humedad y al desgaste. Gracias a estas características, los paneles de fibra de bambú son ideales para revestimientos, mobiliario y proyectos de construcción sostenible.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_blog' => 3,
                'url_imagen' => 'https://ejemplo.com/imagen-blog-3-1.jpg',
                'parrafo_imagen' => 'El bambú se ha convertido en uno de los materiales más revolucionarios en la industria de la construcción gracias a su rápido crecimiento, su resistencia y su impacto ambiental reducido. En comparación con la madera tradicional, el bambú es una opción altamente renovable, ya que puede crecer hasta un metro por día y regenerarse después de la cosecha sin necesidad de replantación.
                Los paneles de fibra de bambú son el resultado de un proceso innovador que convierte esta materia prima en una superficie resistente, flexible y de gran atractivo visual. Estos paneles han demostrado ser una solución ideal para proyectos arquitectónicos que buscan equilibrar funcionalidad, estética y sostenibilidad.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_blog' => 3,
                'url_imagen' => 'https://ejemplo.com/imagen-blog-3-2.jpg',
                'parrafo_imagen' => 'Los paneles de fibra de bambú combinan sostenibilidad, resistencia y estética, convirtiéndose en una excelente opción para interiores y exteriores. Su acabado natural aporta calidez y se adapta a diferentes estilos decorativos, desde modernos hasta rústicos.
                Son altamente resistentes, comparables a algunas maderas duras, pero con mayor flexibilidad y menor riesgo de fracturas. Además, su producción tiene un bajo impacto ambiental, ya que el bambú crece rápidamente y se regenera sin necesidad de replantación, reduciendo la tala de bosques.
                Su textura elegante y sus tonos neutros permiten distintos acabados, como mate, brillante o envejecido. Requieren poco mantenimiento, siendo resistentes a la humedad y al desgaste. Gracias a estas características, los paneles de fibra de bambú son ideales para revestimientos, mobiliario y proyectos de construcción sostenible.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_blog' => 4,
                'url_imagen' => 'https://ejemplo.com/imagen-blog-4-1.jpg',
                'parrafo_imagen' => 'El bambú se ha convertido en uno de los materiales más revolucionarios en la industria de la construcción gracias a su rápido crecimiento, su resistencia y su impacto ambiental reducido. En comparación con la madera tradicional, el bambú es una opción altamente renovable, ya que puede crecer hasta un metro por día y regenerarse después de la cosecha sin necesidad de replantación.
                Los paneles de fibra de bambú son el resultado de un proceso innovador que convierte esta materia prima en una superficie resistente, flexible y de gran atractivo visual. Estos paneles han demostrado ser una solución ideal para proyectos arquitectónicos que buscan equilibrar funcionalidad, estética y sostenibilidad.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_blog' => 4,
                'url_imagen' => 'https://ejemplo.com/imagen-blog-4-2.jpg',
                'parrafo_imagen' => 'Los paneles de fibra de bambú combinan sostenibilidad, resistencia y estética, convirtiéndose en una excelente opción para interiores y exteriores. Su acabado natural aporta calidez y se adapta a diferentes estilos decorativos, desde modernos hasta rústicos.
                Son altamente resistentes, comparables a algunas maderas duras, pero con mayor flexibilidad y menor riesgo de fracturas. Además, su producción tiene un bajo impacto ambiental, ya que el bambú crece rápidamente y se regenera sin necesidad de replantación, reduciendo la tala de bosques.
                Su textura elegante y sus tonos neutros permiten distintos acabados, como mate, brillante o envejecido. Requieren poco mantenimiento, siendo resistentes a la humedad y al desgaste. Gracias a estas características, los paneles de fibra de bambú son ideales para revestimientos, mobiliario y proyectos de construcción sostenible.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('imagen_blogs')->insert($imagenesBlog);
    }
}
