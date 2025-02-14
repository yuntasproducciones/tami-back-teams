<?php

namespace Database\Seeders;

use App\Models\Comentarios;
use App\Models\Seccion;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Comentarios::factory(10)->create();
        Seccion::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            AssignPermissionsToRolesSeeder::class,
            UserSeeder::class,
            
            // Productos
            ProductoSeeder::class,
            EspecificacionSeeder::class,
            DimensionSeeder::class,
            ImagenProductoSeeder::class,
            ProductoRelacionadoSeeder::class,
        ]);

        
    }
}
