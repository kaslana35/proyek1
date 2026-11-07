<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Pastikan role ada
        $adminRole = SpatieRole::firstOrCreate(['name' => 'admin']);
        $userRole = SpatieRole::firstOrCreate(['name' => 'user']);

        // 2. Update atau Buat Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@sewa.com'],
            [
                'name' => 'Admin Sewa',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);

        // 3. Update atau Buat User
        $user = User::updateOrCreate(
            ['email' => 'user@sewa.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole($userRole);
    }
}
