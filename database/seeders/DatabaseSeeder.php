<?php

namespace Database\Seeders;

use App\Models\Categorias;
use App\Models\Productos;
use App\Models\Productos_Categorias;
use App\Models\Detalles_Productos;
use App\Models\Contacto;
use App\Models\Contactos;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            AssignPermissionsToRolesSeeder::class,
            UserSeeder::class,
        ]);

        Categorias::factory(10)->create();

        Productos::factory(10)->create();
        
        Detalles_Productos::factory(10)->create();

        Contacto::factory(10)->create();
    }
}
