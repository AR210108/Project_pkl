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
                'alamat' => 'Jl. Owner No. 1',
                'kontak' => '08111111111',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
            ],
            [
                'name' => 'General Manager',
                'email' => 'general@gmail.com',
                'role' => 'general_manager',
                'divisi' => null,
                'alamat' => 'Jl. General Manager No. 2',
                'kontak' => '08222222222',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
            ],
            [
                'name' => 'Finance',
                'email' => 'finance@gmail.com',
                'role' => 'finance',
                'divisi' => null,
                'alamat' => 'Jl. Finance No. 3',
                'kontak' => '08333333333',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@gmail.com',
                'role' => 'manager_divisi',
                'divisi' => 'programmer',
                'alamat' => 'Jl. Ahmad Fauzi No. 4',
                'kontak' => '08444444444',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'programmer',
                'alamat' => 'Jl. Dewi Lestari No. 5',
                'kontak' => '08555555555',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
            ],
            [
                'name' => 'Agus Wijaya',
                'email' => 'agus@gmail.com',
                'role' => 'manager_divisi',
                'divisi' => 'digital_marketing',
                'alamat' => 'Jl. Agus Wijaya No. 6',
                'kontak' => '08666666666',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
            ],
            [
                'name' => 'Lisa Marlina',
                'email' => 'lisa@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'digital_marketing',
                'alamat' => 'Jl. Lisa Marlina No. 7',
                'kontak' => '08777777777',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
            ],
            [
                'name' => 'Yuni Astuti',
                'email' => 'yuni@gmail.com',
                'role' => 'manager_divisi',
                'divisi' => 'desainer',
                'alamat' => 'Jl. Yuni Astuti No. 8',
                'kontak' => '08888888888',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
            ],
            [
                'name' => 'Ferdy Kurniawan',
                'email' => 'ferdy@gmail.com',
                'role' => 'karyawan',
                'divisi' => 'desainer',
                'alamat' => 'Jl. Ferdy Kurniawan No. 9',
                'kontak' => '08999999999',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
            ],
            [
                'name' => 'Admin Utama',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'divisi' => null,
                'alamat' => 'Jl. Admin No. 10',
                'kontak' => '08101010101',
                'gaji' => 0,
                'status_kerja' => 'aktif',
                'status_karyawan' => 'tetap',
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
                    'alamat'    => $user['alamat'],
                    'kontak'    => $user['kontak'],
                    'gaji'      => $user['gaji'],
                    'status_kerja' => $user['status_kerja'],
                    'status_karyawan' => $user['status_karyawan'],
                    'sisa_cuti' => 12, // Default cuti 12 hari
                ]
            );
        }
    }
}