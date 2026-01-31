<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Tabel Layanan
            $table->foreignId('layanan_id')
                ->constrained('layanans') 
                ->cascadeOnDelete();
            
            // Penanggung jawab projek
            $table->foreignId('penanggung_jawab_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            
            // Kolom utama projek
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            
            // REVISI: Gunakan decimal untuk harga (Presisi mata uang)
            $table->decimal('harga', 15, 2)->nullable();
            
            // REVISI: Gunakan datetime untuk deadline (Bisa set jam menit)
            $table->dateTime('deadline')->nullable();
            
            $table->integer('progres')->default(0);
            
            // Status projek
            // Disesuaikan dengan controller logic: Pending, Proses, Selesai
            $table->enum('status', ['Pending', 'Proses', 'Selesai', 'Dibatalkan'])->default('Pending');
            
            // Kolom tambahan untuk integrasi dengan sistem
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('User yang membuat projek');
            
            $table->timestamps();
            
            // Soft Deletes (Hapus komentar di bawah jika ingin fitur Recycle Bin)
            // $table->softDeletes(); 
            
            // Indexes untuk performa
            $table->index('status');
            $table->index('deadline');
            $table->index('layanan_id');
            $table->index('penanggung_jawab_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project');
    }
};