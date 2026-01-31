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
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            
            // [REVISI] Mengubah karyawan_id menjadi user_id agar user bisa cuti 
            // meskipun data profil karyawan belum lengkap.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('durasi');
            $table->text('keterangan');
            $table->enum('jenis_cuti', ['tahunan', 'sakit', 'penting', 'melahirkan', 'lainnya'])->default('lainnya');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_penolakan')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id'); // Index diganti ke user_id
            $table->index('status');
            $table->index('tanggal_mulai');
            $table->index('tanggal_selesai');
        });

        // Create cuti_histories table for tracking changes
        Schema::create('cuti_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuti_id')->constrained('cuti')->onDelete('cascade');
            $table->string('action'); // created, updated, approved, rejected
            $table->text('changes')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuti_histories');
        Schema::dropIfExists('cuti');
    }
};