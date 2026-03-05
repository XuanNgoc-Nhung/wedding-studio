<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Dùng firstOrCreate để chạy seed nhiều lần không bị trùng email.
     */
    public function run(): void
    {
        $defaults = [
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ];

        // Tài khoản Admin (role = 1)
        User::firstOrCreate(
            ['email' => 'admin@wedding-studio.com'],
            array_merge($defaults, ['name' => 'Admin', 'role' => User::ROLE_ADMIN])
        );

        // Tài khoản nhân viên mẫu (role = 2)
        User::firstOrCreate(
            ['email' => 'nhanvien@wedding-studio.com'],
            array_merge($defaults, ['name' => 'Nhân viên Demo', 'role' => User::ROLE_NHAN_VIEN])
        );

        // Tài khoản người dùng mẫu (role = 3)
        User::firstOrCreate(
            ['email' => 'user@wedding-studio.com'],
            array_merge($defaults, ['name' => 'Người dùng Demo', 'role' => User::ROLE_NGUOI_DUNG])
        );
    }
}
