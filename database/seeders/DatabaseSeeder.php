<?php

namespace Database\Seeders;

//use App\Models\Usuarios_Registro;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Usuarios_Registro::factory(10)->create();

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
