<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Categorias;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'admin@gmail.com',
            'celular' => '980473810',
            'seccion' => 'Ventas',
            'fecha' => now()->format('Y-m-d'),
            'cat_id' => Categorias::all()->random()->cat_id,
            'password' => Hash::make('admin'), 
        ]);

        $user->assignRole('ADMIN');
    }
}
