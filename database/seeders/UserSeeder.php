<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun HCM (Admin)
        User::create([
            'name' => 'Admin HCM',
            'email' => 'hcm@jagooit.com',
            'role' => 'hcm',
            'password' => Hash::make('password123'),
        ]);

        // 2. Akun Account Manager
        User::create([
            'name' => 'Manager AM',
            'email' => 'am@jagooit.com',
            'role' => 'am',
            'password' => Hash::make('password123'),
        ]);

        // 3. Akun Direktur
        User::create([
            'name' => 'Direktur Utama',
            'email' => 'direktur@jagooit.com',
            'role' => 'direktur',
            'password' => Hash::make('password123'),
        ]);
    }
} 
