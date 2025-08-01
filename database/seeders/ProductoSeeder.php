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
                'link' => 'selladora-botellas',
                'subtitulo' => 'Protege, conserva y destaca con cada botella',
                'descripcion' => 'Utiliza el principio de inducción electromagnética...',
                'stock' => 10,
                'precio' => 299.99,
                'seccion' => 'Trabajo',
            ],
            [
                'id' => 2,
                'nombre' => 'MÁQUINA DE EMBALAJE',
                'titulo' => 'MÁQUINA DE EMBALAJE',
                'link' => 'maquina-embalaje',
                'subtitulo' => 'Protege, conserva y destaca EN CADA EMBALAJE',
                'descripcion' => 'El motor escalonado controla el papel...',
                'stock' => 5,
                'precio' => 499.99,
                'seccion' => 'Trabajo',
            ],
            [
                'id' => 3,
                'nombre' => 'SELLADORA DE BOLSAS',
                'titulo' => 'SELLADORA DE BOLSAS',
                'link' => 'selladora-bolsas',
                'subtitulo' => 'Protege, conserva y destaca EN CADA SELLADO DE BOLSAS',
                'descripcion' => 'Empaca de manera eficiente...',
                'stock' => 15,
                'precio' => 199.99,
                'seccion' => 'Trabajo',
            ],
            [
                'id' => 4,
                'nombre' => 'SELLADORA DE BOLSAS DE CHIFLES',
                'titulo' => 'SELLADORA DE BOLSAS DE CHIFLES',
                'link' => 'selladora-chifles',
                'subtitulo' => '¡MANTEN LOS CHIFLES FRESCOS Y CROCANTES!',
                'descripcion' => 'Empaca de manera eficiente...',
                'stock' => 20,
                'precio' => 149.99,
                'seccion' => 'Decoracion',
            ],
            [
                'id' => 5,
                'nombre' => 'SOLDADORA LINGBA',
                'titulo' => 'SOLDADORA LINGBA',
                'link' => 'soldadora-lingba',
                'subtitulo' => 'TUS SOLDADURAS PERFECTAS',
                'descripcion' => 'Incorpora tecnología de inversor...',
                'stock' => 8,
                'precio' => 399.99,
                'seccion' => 'Decoracion',
            ],
            [
                'id' => 6,
                'nombre' => 'IMPRESORA 3D',
                'titulo' => 'IMPRESORA 3D',
                'link' => 'impresora-3d',
                'subtitulo' => 'CREA TUS PROPIOS DISEÑOS',
                'descripcion' => 'Impresora 3D de alta precisión...',
                'stock' => 12,
                'precio' => 599.99,
                'seccion' => 'Decoracion',
            ],
            [
                'id' => 7,
                'nombre' => 'TALADRO INALÁMBRICO',
                'titulo' => 'TALADRO INALÁMBRICO',
                'link' => 'taladro-inalambrico',
                'subtitulo' => 'POTENCIA Y PRECISIÓN EN TUS MANOS',
                'descripcion' => 'Taladro inalámbrico de alta potencia...',
                'stock' => 25,
                'precio' => 89.99,
                'seccion' => 'Decoracion',
            ],
            [
                'id' => 8,
                'nombre' => 'SIERRA CIRCULAR',
                'titulo' => 'SIERRA CIRCULAR',
                'link' => 'sierra-circular',
                'subtitulo' => 'CORTES PRECISOS Y RÁPIDOS',
                'descripcion' => 'Sierra circular de alta precisión...',
                'stock' => 10,
                'precio' => 129.99,
                'seccion' => 'Negocio',
            ],
            [
                'id' => 9,
                'nombre' => 'LIJADORA ELÉCTRICA',
                'titulo' => 'LIJADORA ELÉCTRICA',
                'link' => 'lijadora-electrica',
                'subtitulo' => 'ACABADOS PERFECTOS EN TUS PROYECTOS',
                'descripcion' => 'Lijadora eléctrica de alta potencia...',
                'stock' => 18,
                'precio' => 79.99,
                'seccion' => 'Negocio',
            ],
            [
                'id' => 10,
                'nombre' => 'ASPIRADORA INDUSTRIAL',
                'titulo' => 'ASPIRADORA INDUSTRIAL',
                'link' => 'aspiradora-industrial',
                'subtitulo' => 'POTENCIA Y EFICIENCIA EN LA LIMPIEZA',
                'descripcion' => 'Aspiradora industrial de alta potencia...',
                'stock' => 7,
                'precio' => 299.99,
                'seccion' => 'Negocio',
            ],
            [
                'id' => 11,
                'nombre' => 'COMPRESOR DE AIRE',
                'titulo' => 'COMPRESOR DE AIRE',
                'link' => 'compresor-aire',
                'subtitulo' => 'POTENCIA Y VERSATILIDAD EN TUS MANOS',
                'descripcion' => 'Compresor de aire de alta potencia...',
                'stock' => 9,
                'precio' => 249.99,
                'seccion' => 'Negocio',
            ]
        ];
        DB::table('productos')->insert($productos);
    }
}
