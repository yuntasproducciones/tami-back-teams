<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'id' => 1,
                'nombre' => 'SELLADORA DE BOTELLAS',
                'titulo' => 'SELLADORA DE BOTELLAS',
                'subtitulo' => 'Protege, conserva y destaca con cada botella',
                'lema' => '¡Sella tus botellas con la mejor calidad!',
                'descripcion' => 'Utiliza el principio de inducción electromagnética...',
                'imagen_principal' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR-Uk2gDmaZnoy9OX6DUiCILhzVPYQkq6vwZmdk3ikhaiXGUJy2',
                'stock' => 10,
                'precio' => 299.99,
                'seccion' => 'Trabajo',
                'especificaciones' => json_encode([
                    'voltaje' => '220V',
                    'potencia' => '500W',
                    'material' => 'Acero inoxidable',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'nombre' => 'MÁQUINA DE EMBALAJE',
                'titulo' => 'MÁQUINA DE EMBALAJE',
                'subtitulo' => 'Protege, conserva y destaca EN CADA EMBALAJE',
                'lema' => '¡Embalaje perfecto para tus productos!',
                'descripcion' => 'El motor escalonado controla el papel...',
                'imagen_principal' => 'https://www.gapacker.com/uploads/photobank7-300x300.jpg',
                'stock' => 5,
                'precio' => 499.99,
                'seccion' => 'Trabajo',
                'especificaciones' => json_encode([
                    'peso' => '35kg',
                    'dimensiones' => '60x45x70cm',
                    'automatica' => true
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'nombre' => 'SELLADORA DE BOLSAS',
                'titulo' => 'SELLADORA DE BOLSAS',
                'subtitulo' => 'Protege, conserva y destaca EN CADA SELLADO DE BOLSAS',
                'lema' => '¡Conserva y mantén tus alimentos frescos por más tiempo!',
                'descripcion' => 'Empaca de manera eficiente...',
                'imagen_principal' => 'https://s.alicdn.com/@sc04/kf/H035cb64befd041a3b54b275d9fbe6302V.jpg_300x300.jpg',
                'stock' => 15,
                'precio' => 199.99,
                'seccion' => 'Trabajo',
                'especificaciones' => json_encode([
                    'tipos_de_sello' => ['plástico', 'aluminio', 'papel'],
                    'velocidad' => '30 bolsas/minuto'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 4,
                'nombre' => 'SELLADORA DE BOLSAS DE CHIFLES',
                'titulo' => 'SELLADORA DE BOLSAS DE CHIFLES',
                'subtitulo' => '¡MANTEN LOS CHIFLES FRESCOS Y CROCANTES!',
                'lema' => '¡La mejor opción para mantener tus chifles siempre frescos!',
                'descripcion' => 'Empaca de manera eficiente...',
                'imagen_principal' => 'https://image.made-in-china.com/318f0j00sTZflFyPLDbj/DZD-220%E4%B8%BB%E5%9B%BE%E8%A7%86%E9%A2%91.mp4.webp',
                'stock' => 20,
                'precio' => 149.99,
                'seccion' => 'Decoracion',
                'especificaciones' => json_encode([
                    'capacidad' => '500g por bolsa',
                    'material' => 'acero galvanizado'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 5,
                'nombre' => 'SOLDADORA LINGBA',
                'titulo' => 'SOLDADORA LINGBA',
                'subtitulo' => 'TUS SOLDADURAS PERFECTAS',
                'lema' => '¡La mejor opción para soldar con precisión!',
                'descripcion' => 'Incorpora tecnología de inversor...',
                'imagen_principal' => 'https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRRlsTCJB0OtQ9P4AUazhoxIc36hhHSuotJLDU9jg4GN7YZLWS5',
                'stock' => 8,
                'precio' => 399.99,
                'seccion' => 'Decoracion',
                'especificaciones' => json_encode([
                    'corriente' => '150A',
                    'frecuencia' => '60Hz'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 6,
                'nombre' => 'IMPRESORA 3D',
                'titulo' => 'IMPRESORA 3D',
                'subtitulo' => 'CREA TUS PROPIOS DISEÑOS',
                'lema' => '¡Imprime tus ideas en 3D!',
                'descripcion' => 'Impresora 3D de alta precisión...',
                'imagen_principal' => 'https://storage.googleapis.com/designtec-storage/impresora-3d-ender-3-v2-neo-5424.jpg',
                'stock' => 12,
                'precio' => 599.99,
                'seccion' => 'Decoracion',
                'especificaciones' => json_encode([
                    'resolucion' => '0.1mm',
                    'tipo_filamento' => ['PLA', 'ABS']
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 7,
                'nombre' => 'TALADRO INALÁMBRICO',
                'titulo' => 'TALADRO INALÁMBRICO',
                'subtitulo' => 'POTENCIA Y PRECISIÓN EN TUS MANOS',
                'lema' => '¡Taladra sin límites!',
                'descripcion' => 'Taladro inalámbrico de alta potencia...',
                'imagen_principal' => 'https://th.bing.com/th/id/OIP.xfxQRCHgqXKyEdhIIixswgHaEJ?rs=1&pid=ImgDetMain',
                'stock' => 25,
                'precio' => 89.99,
                'seccion' => 'Decoracion',
                'especificaciones' => json_encode([
                    'bateria' => '18V',
                    'velocidades' => 2
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 8,
                'nombre' => 'SIERRA CIRCULAR',
                'titulo' => 'SIERRA CIRCULAR',
                'subtitulo' => 'CORTES PRECISOS Y RÁPIDOS',
                'lema' => '¡Corta con precisión y rapidez!',
                'descripcion' => 'Sierra circular de alta precisión...',
                'imagen_principal' => 'https://resources.claroshop.com/medios-plazavip/s2/10835/1167074/5d3e7f16efb4c-sici-7-1-4a3-1600x1600.jpg',
                'stock' => 10,
                'precio' => 129.99,
                'seccion' => 'Negocio',
                'especificaciones' => json_encode([
                    'diametro_disco' => '7 1/4"',
                    'rpm' => '5200'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 9,
                'nombre' => 'LIJADORA ELÉCTRICA',
                'titulo' => 'LIJADORA ELÉCTRICA',
                'subtitulo' => 'ACABADOS PERFECTOS EN TUS PROYECTOS',
                'lema' => '¡Lija con precisión y facilidad!',
                'descripcion' => 'Lijadora eléctrica de alta potencia...',
                'imagen_principal' => 'https://i.blogs.es/13ec44/decker/1366_2000.jpg',
                'stock' => 18,
                'precio' => 79.99,
                'seccion' => 'Negocio',
                'especificaciones' => json_encode([
                    'tipo' => 'orbital',
                    'velocidad' => '13000 OPM'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 10,
                'nombre' => 'ASPIRADORA INDUSTRIAL',
                'titulo' => 'ASPIRADORA INDUSTRIAL',
                'subtitulo' => 'POTENCIA Y EFICIENCIA EN LA LIMPIEZA',
                'lema' => '¡Limpieza industrial eficiente!',
                'descripcion' => 'Aspiradora industrial de alta potencia...',
                'imagen_principal' => 'https://th.bing.com/th/id/OIP.aPk-OGPH8FPfX4t2P2qRSwHaFr?rs=1&pid=ImgDetMain',
                'stock' => 7,
                'precio' => 299.99,
                'seccion' => 'Negocio',
                'especificaciones' => json_encode([
                    'capacidad' => '60L',
                    'potencia' => '1200W'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 11,
                'nombre' => 'COMPRESOR DE AIRE',
                'titulo' => 'COMPRESOR DE AIRE',
                'subtitulo' => 'POTENCIA Y VERSATILIDAD EN TUS MANOS',
                'lema' => '¡Potencia y versatilidad en un solo equipo!',
                'descripcion' => 'Compresor de aire de alta potencia...',
                'imagen_principal' => 'https://images-na.ssl-images-amazon.com/images/I/716xF6bHfqL._AC_SL1400_.jpg',
                'stock' => 9,
                'precio' => 249.99,
                'seccion' => 'Negocio',
                'especificaciones' => json_encode([
                    'presion_maxima' => '8 bar',
                    'capacidad' => '50L',
                    'potencia' => '2HP'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('productos')->insert($productos);
    }
}
