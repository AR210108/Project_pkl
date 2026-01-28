<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Divisi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // mapping: nama_divisi => id
        $divisiMap = Divisi::pluck('id', 'divisi')->toArray();

        $users = [
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
                    'name'      => $user['name'],
                    'password'  => Hash::make('123'),
                    'role'      => $user['role'],
                    'divisi_id' => $user['divisi']
                        ? ($divisiMap[$user['divisi']] ?? null)
                        : null,
                ]
            );
        }
    }
}
