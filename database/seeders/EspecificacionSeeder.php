<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EspecificacionSeeder extends Seeder
{
    public function run(): void
    {
        $especificaciones = [
            // Producto 1: Letreros Neón LED - Especificaciones
            ['producto_id' => 1, 'valor' => 'Materiales duraderos', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'valor' => '100% personalizables', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'valor' => 'Eficiencia energética', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'valor' => 'Adaptable a espacios', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 1: Letreros Neón LED - Beneficios
            ['producto_id' => 1, 'valor' => 'Iluminación con colores vibrantes', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'valor' => 'Facil instalación', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'valor' => 'Brinda personalidad', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'valor' => 'Atractivo visual', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 1: Letreros Neón LED - Imágenes extras
            ['producto_id' => 1, 'valor' => '/Productos/letrero-neon-beneficios.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 1, 'valor' => '/Productos/letrero-neon-banner.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 2: Sillas y Mesas LED - Especificaciones
            ['producto_id' => 2, 'valor' => 'Materiales de alta calidad', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'valor' => '100% personalizables', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'valor' => 'Bajo consumo de energía', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'valor' => 'Adaptable a interiores y exteriores', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 2: Sillas y Mesas LED - Beneficios
            ['producto_id' => 2, 'valor' => 'Crea un ambiente único', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'valor' => 'Fácil instalación', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'valor' => 'Toque exclusivo a tu evento', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'valor' => 'Adaptable a tu estilo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 2: Sillas y Mesas LED - Imágenes extras
            ['producto_id' => 2, 'valor' => '/Productos/sillas-mesas-beneficios.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 2, 'valor' => '/Productos/sillas-mesas-banner.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 3: Pisos LED - Especificaciones
            ['producto_id' => 3, 'valor' => 'Alta resolución y brillo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'valor' => 'Resistencia al peso', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'valor' => 'Superficie antideslizante', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'valor' => 'Soporta proyecciones HD', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 3: Pisos LED - Beneficios
            ['producto_id' => 3, 'valor' => 'Ambientes inmersivos', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'valor' => 'Mayor interacción', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'valor' => 'Flexibilidad de diseños', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'valor' => 'Aumento de visibilidad', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 3: Pisos LED - Imágenes extras
            ['producto_id' => 3, 'valor' => '/Productos/pisos-led-beneficios.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 3, 'valor' => '/Productos/pisos-led-banner.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 4: Barras Pixel LED - Especificaciones
            ['producto_id' => 4, 'valor' => 'Ideales para exposiciones', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'valor' => 'Imágenes de alto relieve', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'valor' => 'Tecnología eficiente y moderna', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'valor' => 'Colores vibrantes', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 4: Barras Pixel LED - Beneficios
            ['producto_id' => 4, 'valor' => 'Imágenes de alto impacto', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'valor' => 'Fácil instalación y mantenimiento', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'valor' => 'Personalización rápida', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'valor' => 'Alto atractivo visual', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 4: Barras Pixel LED - Imágenes extras
            ['producto_id' => 4, 'valor' => '/Productos/barras-pixel-beneficios.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['producto_id' => 4, 'valor' => '/Productos/barras-pixel-banner.webp', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
         ];

        DB::table('especificaciones')->insert($especificaciones);
    }
}
