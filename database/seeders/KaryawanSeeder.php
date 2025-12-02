<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class KaryawanSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Karyawan Satu',
            'email' => 'karyawan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'karyawan'
        ]);
    }
}
