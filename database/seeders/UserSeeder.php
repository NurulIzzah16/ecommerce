<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'joko',
                'email' => 'joko@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'adit',
                'email' => 'adit@example.com',
                'password' => Hash::make('password456'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        User::factory(5)->create(); // Generate 5 random users
    }
}
