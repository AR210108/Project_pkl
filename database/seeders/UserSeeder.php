<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // === OWNER & MANAGEMENT ===
            [
                'name' => 'Owner',
                'email' => 'owner@gmail.com',
                'role' => 'owner',
                'divisi' => null,
            ],
            [
                'name' => 'General Manager',
                'email' => 'general@gmail.com',
                'role' => 'general_manager',
                'divisi' => null,
            ],

            // === DIVISI PROGRAMMER ===
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@gmail.com',
                'role' => 'manager_divisi',
                'divisi' => 'programmer',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'programmer',
            ],
            [
                'name' => 'Rizki Pratama',
                'email' => 'rizki@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'programmer',
            ],
            [
                'name' => 'Maya Indah',
                'email' => 'maya@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'programmer',
            ],
            [
                'name' => 'Finance',
                'email' => 'finance@gmail.com',
                'role' => 'finance',
                'divisi' => null
            ],

            [
                'name' => 'General Manager',
                'email' => 'general@gmail.com',
                'role' => 'general_manager',
                'divisi' => null
            ],

            // === DIVISI DIGITAL MARKETING ===
            [
                'name' => 'Agus Wijaya',
                'email' => 'agus@gmail.com',
                'role' => 'manager_divisi',
                'divisi' => 'digital_marketing',
            ],
            [
                'name' => 'Lisa Marlina',
                'email' => 'lisa@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'digital_marketing',
            ],
            [
                'name' => 'Rudi Hartono',
                'email' => 'rudi.hartono@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'digital_marketing',
            ],
            [
                'name' => 'Nina Sari',
                'email' => 'nina@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'digital_marketing',
            ],

            // === DIVISI DESAINER ===
            [
                'name' => 'Yuni Astuti',
                'email' => 'yuni@gmail.com',
                'role' => 'manager_divisi',
                'divisi' => 'desainer',
            ],
            [
                'name' => 'Ferdy Kurniawan',
                'email' => 'ferdy@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'desainer',
            ],
            [
                'name' => 'Ratna Dewi',
                'email' => 'ratna@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'desainer',
            ],
            [
                'name' => 'Hendra Setiawan',
                'email' => 'hendra@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'desainer',
            ],
        [
            'name' => 'Dewi Lestari',
            'email' => 'dewi@gmail.com',
            'role' => 'karyawan',
            'divisi' => 'programmer'
        ],

            // === KARYAWAN TAMBAHAN ===
            [
                'name' => 'Joko Susilo',
                'email' => 'joko@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'programmer',
            ],
            [
                'name' => 'Rina Melati',
                'email' => 'rina@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'digital_marketing',
            ],
            [
                'name' => 'Bambang Surya',
                'email' => 'bambang@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'desainer',
            ],

            // === ADMIN ===
            [
                'name' => 'Admin Utama',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'divisi' => null,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('123'),
                    'role' => $user['role'],
                    'divisi' => $user['divisi'],
                ]
            );
        }
    }
}
