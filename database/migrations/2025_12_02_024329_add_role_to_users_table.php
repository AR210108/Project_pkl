<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan 'admin' ke enum roles
            $table->enum('role', [
                'owner',
                'admin', // TAMBAH INI
                'general_manager', 
                'manager_divisi',
                'finance',
                'karyawan'
            ])->default('karyawan');
            
            $table->enum('divisi', [
                'programmer',
                'digital_marketing', 
                'desainer'
            ])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'divisi']);
        });
    }
};