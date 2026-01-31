<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Divisi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data users yang sudah ada
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Cek dan buat divisi jika belum ada
        $this->createDivisisIfNotExists();

        // Get divisi IDs
        $programmer = Divisi::where('divisi', 'programmer')->first();
        $digitalMarketing = Divisi::where('divisi', 'digital_marketing')->first();
        $desainer = Divisi::where('divisi', 'desainer')->first();

        // Pastikan divisi ditemukan
        if (!$programmer || !$digitalMarketing || !$desainer) {
            $this->command->error('Divisi tidak ditemukan!');
            return;
        }

        $users = [
            // User tanpa divisi (admin, owner, GM, finance)
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
                'divisi_id' => null,
            ],
            [
                'name' => 'Owner Agency',
                'email' => 'owner@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'owner',
                'divisi_id' => null,
            ],
            [
                'name' => 'General Manager',
                'email' => 'gm@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'general_manager',
                'divisi_id' => null,
            ],
            [
                'name' => 'Finance Department',
                'email' => 'finance@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'finance',
                'divisi_id' => null,
            ],
            
            // Programmer Divisi
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'manager_divisi',
                'divisi_id' => $programmer->id,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'karyawan',
                'divisi_id' => $programmer->id,
            ],
            [
                'name' => 'Rizki Pratama',
                'email' => 'rizki.pratama@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'karyawan',
                'divisi_id' => $programmer->id,
            ],
            
            // Digital Marketing Divisi
            [
                'name' => 'Agus Wijaya',
                'email' => 'agus.wijaya@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'manager_divisi',
                'divisi_id' => $digitalMarketing->id,
            ],
            [
                'name' => 'Lisa Marlina',
                'email' => 'lisa.marlina@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'karyawan',
                'divisi_id' => $digitalMarketing->id,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'karyawan',
                'divisi_id' => $digitalMarketing->id,
            ],
            
            // Desainer Divisi
            [
                'name' => 'Yuni Astuti',
                'email' => 'yuni.astuti@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'manager_divisi',
                'divisi_id' => $desainer->id,
            ],
            [
                'name' => 'Ferdy Kurniawan',
                'email' => 'ferdy.kurniawan@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'karyawan',
                'divisi_id' => $desainer->id,
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'karyawan',
                'divisi_id' => $desainer->id,
            ],
            
            // Admin biasa
            [
                'name' => 'Admin Support',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
                'divisi_id' => null,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('User seeder berhasil dijalankan! Total: ' . count($users) . ' user');
        $this->command->info('Password semua user: 123');
        $this->command->info('Email format: nama@gmail.com');
    }

    /**
     * Membuat data divisi jika belum ada - TANPA DESKRIPSI
     */
    private function createDivisisIfNotExists(): void
    {
        $divisis = ['programmer', 'digital_marketing', 'desainer'];

        foreach ($divisis as $namaDivisi) {
            Divisi::firstOrCreate(
                ['divisi' => $namaDivisi],
                ['divisi' => $namaDivisi]
            );
        }
    }
}