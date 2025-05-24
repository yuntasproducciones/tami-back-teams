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
            ['producto_id' => 1, 'url_imagen' => 'https://es.huaqiaopm.com/uploads-kunba/1m/1709.jpg', 'texto_alt_SEO' => 'Imagen del producto 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'url_imagen' => 'https://imagedelivery.net/4fYuQyy-r8_rpBpcY7lH', 'texto_alt_SEO' => 'Imagen del producto 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'url_imagen' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:', 'texto_alt_SEO' => 'Imagen del producto 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'url_imagen' => 'https://http2.mlstatic.com', 'texto_alt_SEO' => 'Imagen del producto 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 2
            ['producto_id' => 2, 'url_imagen' => 'https://image.made-in-china.com/oa-Podwer-Packing.webp', 'texto_alt_SEO' => 'Imagen del producto 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-2-2', 'texto_alt_SEO' => 'Imagen del producto 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'url_imagen' => 'https://image.made-in-china.com/2f0j00DAbWUuOIfJqr/Chinese-Herbal-Flavored-Slimming-Tea.webp', 'texto_alt_SEO' => 'Imagen del producto 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'url_imagen' => 'https://image.made-in-china.com/2ackaging-with-Label-Bag-Packing-Machine-Manufacturers.webp', 'texto_alt_SEO' => 'Imagen del producto 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3
            ['producto_id' => 3, 'url_imagen' => 'https://encrypted-tbn0.gsCas8LMqY0m7zCvlqXz4Nar51--CXOcCs', 'texto_alt_SEO' => 'Imagen del producto 3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H84dd2ddJ.jpg_300x300.jpg', 'texto_alt_SEO' => 'Imagen del producto 3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H035cba3b54b275d9fbe6302V.jpg_300x300.jpg', 'texto_alt_SEO' => 'Imagen del producto 3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'url_imagen' => 'https://image.made-in-china.com/2f0jeds-Granule-Sachet-Packaging-Machine.webp', 'texto_alt_SEO' => 'Imagen del producto 3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

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
