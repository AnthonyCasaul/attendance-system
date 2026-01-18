<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate([
            'email' => 'admin@attendance.com'
        ], [
            'name' => 'Administrator',
            'email' => 'admin@attendance.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create test user
        User::firstOrCreate([
            'email' => 'user@attendance.com'
        ], [
            'name' => 'Test User',
            'email' => 'user@attendance.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
