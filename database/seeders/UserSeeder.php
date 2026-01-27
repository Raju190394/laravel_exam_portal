<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Examiner User',
            'email' => 'examiner@examiner.com',
            'password' => Hash::make('password'),
            'role' => 'examiner',
        ]);

        User::create([
            'name' => 'Student User',
            'email' => 'student@student.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
}
