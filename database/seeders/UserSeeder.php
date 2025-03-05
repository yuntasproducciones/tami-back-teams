<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
<<<<<<< HEAD
            'celular' => '980473810',
            'fecha' => now()->format('Y-m-d'),
=======
            'celular' => '000111000',
>>>>>>> cb7a0679ccd3cd7e181b9be26c196fdead5f8e83
            'password' => bcrypt('admin'), 
        ]);

        $user->assignRole('ADMIN');
    }
}
