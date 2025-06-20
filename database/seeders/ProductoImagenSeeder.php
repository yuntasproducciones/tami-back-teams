<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductoImagenSeeder extends Seeder
{
    public function run(): void
    {
        $imagenes = [
            // Producto 1
            ['producto_id' => 1, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/Hd532068024494ddfb25db211ab1b02f83.png_720x720q50.jpg', 'texto_alt_SEO' => 'Imagen del producto 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'url_imagen' => 'https://durantis.pe/wp-content/uploads/2024/12/maquina-selladora-tapadora-de-botella.jpg', 'texto_alt_SEO' => 'Imagen del producto 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'url_imagen' => 'https://m.media-amazon.com/images/I/61LlHi7CVCL._AC_SX679_.jpg', 'texto_alt_SEO' => 'Imagen del producto 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'url_imagen' => 'https://m.media-amazon.com/images/I/613MqQXlgZL.__AC_SX300_SY300_QL70_FMwebp_.jpg', 'texto_alt_SEO' => 'Imagen del producto 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 2
            ['producto_id' => 2, 'url_imagen' => 'https://per-pack.com/wp-content/uploads/2021/08/XP650-ALX-T-pop-scaled.jpg', 'texto_alt_SEO' => 'Imagen del producto 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'url_imagen' => 'https://liderpac.es/wp-content/uploads/2019/09/maquinaria-embalaje-precintadoras-300x240.jpg', 'texto_alt_SEO' => 'Imagen del producto 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'url_imagen' => 'https://www.alephgraphics.com/es/wp-content/uploads/2020/09/Maquina-de-Embalaje-AG-A3540-scaled.jpg', 'texto_alt_SEO' => 'Imagen del producto 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'url_imagen' => 'https://www.bfrsystems.com/uploads/medias/66757143a7fb9.jpeg', 'texto_alt_SEO' => 'Imagen del producto 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3
            ['producto_id' => 3, 'url_imagen' => 'https://maqjoyo.com/wp-content/uploads/2022/10/selladora-FR-300-3.jpg', 'texto_alt_SEO' => 'Imagen del producto 3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H84dd2ddJ.jpg_300x300.jpg', 'texto_alt_SEO' => 'Imagen del producto 3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H035cba3b54b275d9fbe6302V.jpg_300x300.jpg', 'texto_alt_SEO' => 'Imagen del producto 3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'url_imagen' => 'https://www.ofertay.com/75-large_default/selladora-de-bolsas-de-pedal-pfs-450.jpg', 'texto_alt_SEO' => 'Imagen del producto 3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 4
            ['producto_id' => 4, 'url_imagen' => 'https://image.made-in-china.com/ock-CAS-4418-26-2-Additive.jpg', 'texto_alt_SEO' => 'Imagen del producto 4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'url_imagen' => 'https://www.viaindustrial.com/imagenes/prod/P244989.JPG', 'texto_alt_SEO' => 'Imagen del producto 4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'url_imagen' => 'https://image.made-in-china.com/202f0j00tZdYAeHEbhqR/Ice-Pop-Filling-and-Sealing-Machine-Ice-Bag.webp', 'texto_alt_SEO' => 'Imagen del producto 4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'url_imagen' => 'https://m.media-amazon.com/images/I/41Q9yzRMstL._AC_UF894,1000_QL80_.jpg', 'texto_alt_SEO' => 'Imagen del producto 4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 5
            ['producto_id' => 5, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/Ha6da89b00c8a487ca40ca6fd9d3d0b04J.jpg_720x720q50.jpg', 'texto_alt_SEO' => 'Imagen del producto 5', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 5, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H36c6e8b7da584cb29400616252117311G.jpg_720x720q50.jpg', 'texto_alt_SEO' => 'Imagen del producto 5', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 5, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H962abebb0efb45f59073c160de2639e5R.jpg_300x300.jpg', 'texto_alt_SEO' => 'Imagen del producto 5', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 5, 'url_imagen' => 'https://mascaradesoldar.com/MIG-ARC-soldador-Tig-MMA-sin-gas-2.jpg', 'texto_alt_SEO' => 'Imagen del producto 5', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('producto_imagenes')->insert($imagenes);
    }
}
