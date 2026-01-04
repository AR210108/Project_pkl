<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah 'karyawan' menjadi 'karyawans' di sini
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('jabatan', 100);
            $table->string('gaji', 50);
            $table->text('alamat');
            $table->string('kontak', 20);
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Dan juga ubah 'karyawan' menjadi 'karyawans' di sini
        Schema::dropIfExists('karyawans');
    }
};