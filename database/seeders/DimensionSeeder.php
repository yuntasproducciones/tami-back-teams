<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DimensionSeeder extends Seeder
{
    public function run(): void
    {
        $dimensiones = [
            // Producto 1: Letreros Neón LED
            [
                'id_producto' => 1,
                'alto' => '30',
                'largo' => '60',
                'ancho' => '5',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_producto' => 2,
                'alto' => '40',
                'largo' => '80',
                'ancho' => '6',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_producto' => 3,
                'alto' => '50',
                'largo' => '100',
                'ancho' => '7',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_producto' => 4,
                'alto' => '60',
                'largo' => '120',
                'ancho' => '8',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_producto' => 5,
                'alto' => '70',
                'largo' => '140',
                'ancho' => '9',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('dimensions')->insert($dimensiones);
    }
}
