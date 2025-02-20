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
            'name' => 'Juan',
            'email' => 'admin@gmail.com',
            'celular' => '980473810',
            'fecha' => now()->format('Y-m-d'),
            'password' => bcrypt('admin'), 
        ]);

        $user->assignRole('ADMIN');
    }
}
