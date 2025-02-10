<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

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
            'password' => Hash::make('admin'), 
        ]);

        $user->assignRole('ADMIN');
    }
}
