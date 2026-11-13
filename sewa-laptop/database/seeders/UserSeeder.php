<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Update atau buat Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@sewa.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('Admin123@'),  // harus punya huruf besar, angka, special char
            ]
        );
        $admin->syncRoles([$adminRole]);

        // Update atau buat User
        $user = User::updateOrCreate(
            ['email' => 'user@sewa.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('UserLaptop@2024'),  // harus punya huruf besar, angka, special char
            ]
        );
        $user->syncRoles([$userRole]);
    }
}
