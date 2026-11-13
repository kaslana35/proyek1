<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // 2. Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@sewa.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('Admin123@'),
            ]
        );
        $admin->assignRole($adminRole);

        // 3. Create Users
        $users = [
            ['name' => 'User Biasa', 'email' => 'user@sewa.com'],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('UserLaptop@2024'),
                ]
            );
            $user->assignRole($userRole);
        }

        // 4. Create Laptops via LaptopSeeder
        $this->call(LaptopSeeder::class);
    }
}
