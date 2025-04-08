<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImagenProductoSeeder extends Seeder
{
    public function run(): void
    {
        $imagenes = [
            // Producto 1
            ['id_producto' => 1, 'url_imagen' => 'https://es.huaqiaopm.com/uploads-kunba/1m/1709/GLF-500-Automatic-Induction-Sealing-Machine-1.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'url_imagen' => 'https://imagedelivery.net/4fYuQyy-r8_rpBpcY7lH_A/falabellaPE/115505705_01/public', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'url_imagen' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR-Uk2gDmaZnoy9OX6DUiCILhzVPYQkq6vwZmdk3ikhaiXGUJy2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'url_imagen' => 'https://http2.mlstatic.com/D_NQ_NP_805204-MLU78693643278_092024-O.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 2
            ['id_producto' => 2, 'url_imagen' => 'https://image.made-in-china.com/202f0j00kSGqfcUFaTop/Automatic-Tea-Leaves-Pepper-Grain-Flour-Podwer-Coffee-Cocoa-Podwer-Packing.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-2-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'url_imagen' => 'https://image.made-in-china.com/2f0j00DAbWUuOIfJqr/Chinese-Herbal-Flavored-Slimming-Tea.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'url_imagen' => 'https://image.made-in-china.com/2f0j00azYfoujdOtgw/Automatic-Tea-Packaging-with-Label-Bag-Packing-Machine-Manufacturers.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3
            ['id_producto' => 3, 'url_imagen' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQvzyh0CP8sb1uQnycFCas8LMqY0m7zCvlqXz4Nar51--CXOcCs', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H869b266259db44edabf73164334dd2ddJ.jpg_300x300.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H035cb64befd041a3b54b275d9fbe6302V.jpg_300x300.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'url_imagen' => 'https://image.made-in-china.com/2f0j00udZVGDzsYPpU/Automatic-Candy-Cereal-Peanuts-Sugar-Salt-Grain-Seeds-Granule-Sachet-Packaging-Machine.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 4
            ['id_producto' => 4, 'url_imagen' => 'https://image.made-in-china.com/43f34j00tRTkeKcjJbuG/Hot-Sale-Sodium-Dehydroacetate-in-Stock-CAS-4418-26-2-Additive.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'url_imagen' => 'https://www.viaindustrial.com/imagenes/prod/P244989.JPG', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'url_imagen' => 'https://image.made-in-china.com/202f0j00tZdYAeHEbhqR/Ice-Pop-Filling-and-Sealing-Machine-Ice-Bag.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'url_imagen' => 'https://m.media-amazon.com/images/I/41Q9yzRMstL._AC_UF894,1000_QL80_.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 5
            ['id_producto' => 5, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/Ha6da89b00c8a487ca40ca6fd9d3d0b04J.jpg_720x720q50.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H36c6e8b7da584cb29400616252117311G.jpg_720x720q50.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'url_imagen' => 'https://s.alicdn.com/@sc04/kf/H962abebb0efb45f59073c160de2639e5R.jpg_300x300.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'url_imagen' => 'https://mascaradesoldar.com/wp-content/uploads/2022/05/M-quina-de-soldadura-semiautom-tica-3-en-1-MIG-ARC-soldador-Tig-MMA-sin-gas-2.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 6
            ['id_producto' => 6, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-6-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-6-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-6-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-6-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 7
            ['id_producto' => 7, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-7-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-7-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-7-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-7-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 8
            ['id_producto' => 8, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-8-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-8-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-8-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-8-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 9
            ['id_producto' => 9, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-9-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-9-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-9-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-9-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 10
            ['id_producto' => 10, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-10-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-10-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-10-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-10-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 11
            ['id_producto' => 11, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-11-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-11-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-11-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-11-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('imagen_productos')->insert($imagenes);
    }
}
