<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
<<<<<<< HEAD

            // Relasi ke divisi
            $table->foreignId('divisi_id')->nullable()->constrained('divisi')->onDelete('set null');

=======
            
            // --- TAMBAHAN KOLOM YANG HILANG ---
            
            // Kolom Role (Sesuai enum di error sebelumnya)
            $table->enum('role', ['owner', 'admin', 'general_manager', 'manager_divisi', 'finance', 'karyawan'])->default('karyawan');
            
            // Kolom Divisi (Agar tidak error saat group by divisi)
            $table->string('divisi')->nullable();
            
            // Kolom Sisa Cuti (Default 12 hari, sesuai request terakhir)
            $table->integer('sisa_cuti')->default(12);
            
            // ------------------------------------
            
>>>>>>> e0a79ac350ede919391a158f9e73131b9e8ede18
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};