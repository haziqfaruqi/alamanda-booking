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
        // Admin Account
        User::create([
            'name' => 'Admin',
            'email' => 'admin@alamanda.com',
            'phone' => '0123456789',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        // Staff Account
        User::create([
            'name' => 'Staff',
            'email' => 'staff@alamanda.com',
            'phone' => '0123456788',
            'role' => 'staff',
            'password' => bcrypt('staff123'),
        ]);

        // Regular User Account
        User::create([
            'name' => 'Ainaa Kamaruddin',
            'email' => 'ainaa@email.com',
            'phone' => '0123456787',
            'role' => 'user',
            'password' => bcrypt('user123'),
        ]);

        // Additional Test User
        User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'phone' => '0198765432',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);
    }
}
