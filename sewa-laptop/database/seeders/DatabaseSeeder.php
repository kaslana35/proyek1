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
            ['email' => 'admin123@gmail.com'],
            [
                'name' => 'Admin SewaLaptop',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole($adminRole);

        // 3. Create Users
        $users = [
            ['name' => 'Bayu Pratama', 'email' => 'bayu123@gmail.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi456@gmail.com'],
            ['name' => 'Siti Aminah', 'email' => 'siti789@gmail.com'],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password123'),
                ]
            );
            $user->assignRole($userRole);
        }

        // 4. Create Laptops via LaptopSeeder
        $this->call(LaptopSeeder::class);
    }
}
