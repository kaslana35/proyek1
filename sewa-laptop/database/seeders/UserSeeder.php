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
                'password' => Hash::make('password123'),
            ]
        );
        $admin->syncRoles([$adminRole]);

        // Update atau buat User
        $user = User::updateOrCreate(
            ['email' => 'user@sewa.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('password123'),
            ]
        );
        $user->syncRoles([$userRole]);
    }
}
