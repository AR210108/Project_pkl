<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === OWNER & MANAGEMENT ===
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'owner',
            'divisi' => null
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'general_manager',
            'divisi' => null
        ]);

        // === DIVISI PROGRAMMER ===
        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'manager_divisi',
            'divisi' => 'programmer'
        ]);

        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'c',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'programmer'
        ]);

        User::create([
            'name' => 'Rizki Pratama',
            'email' => 'rizki@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'programmer'
        ]);

        User::create([
            'name' => 'Maya Indah',
            'email' => 'maya@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'programmer'
        ]);

        // === DIVISI DIGITAL MARKETING ===
        User::create([
            'name' => 'Agus Wijaya',
            'email' => 'agus@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'manager_divisi',
            'divisi' => 'digital_marketing'
        ]);

        User::create([
            'name' => 'Lisa Marlina',
            'email' => 'lisa@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'digital_marketing'
        ]);

        User::create([
            'name' => 'Rudi Hartono',
            'email' => 'rudi.hartono@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'digital_marketing'
        ]);

        User::create([
            'name' => 'Nina Sari',
            'email' => 'nina@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'digital_marketing'
        ]);

        // === DIVISI DESAINER ===
        User::create([
            'name' => 'Yuni Astuti',
            'email' => 'yuni@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'manager_divisi',
            'divisi' => 'desainer'
        ]);

        User::create([
            'name' => 'Ferdy Kurniawan',
            'email' => 'ferdy@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'desainer'
        ]);

        User::create([
            'name' => 'Ratna Dewi',
            'email' => 'ratna@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'desainer'
        ]);

        User::create([
            'name' => 'Hendra Setiawan',
            'email' => 'hendra@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'desainer'
        ]);

        // === KARYAWAN TAMBAHAN ===
        User::create([
            'name' => 'Joko Susilo',
            'email' => 'joko@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'programmer'
        ]);

        User::create([
            'name' => 'Rina Melati',
            'email' => 'rina@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'digital_marketing'
        ]);

        User::create([
            'name' => 'Bambang Surya',
            'email' => 'bambang@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'karyawan',
            'divisi' => 'desainer'
        ]);
          User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'divisi' => null
        ]);
    }
}