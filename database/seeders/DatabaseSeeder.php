<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);

        // Karyawan default
        User::create([
            'name' => 'Ikhsan',
            'email' => 'karyawan@mail.com',
            'password' => bcrypt('karyawan123'),
            'role' => 'karyawan'
        ]);
        User::create([
            'name' => 'Pajar',
            'email' => 'karyawan1@mail.com',
            'password' => bcrypt('karyawan123'),
            'role' => 'karyawan'
        ]);
    }
}
