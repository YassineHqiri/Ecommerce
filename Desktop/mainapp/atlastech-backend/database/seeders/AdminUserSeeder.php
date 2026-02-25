<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@atlastech.com'],
            [
                'name' => 'Admin User',
                'password' => 'admin123456',
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@atlastech.com'],
            [
                'name' => 'Manager',
                'password' => 'manager123456',
                'role' => 'admin',
            ]
        );
    }
}
