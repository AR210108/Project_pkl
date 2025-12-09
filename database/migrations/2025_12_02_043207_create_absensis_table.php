<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            
            // Early checkout tracking
            $table->boolean('is_early_checkout')->default(false);
            $table->text('early_checkout_reason')->nullable();

            // Status fields
            $table->enum('status', ['Tepat Waktu', 'Terlambat', 'Tidak Masuk', 'Cuti', 'Sakit', 'Izin', 'Dinas Luar']);
            $table->enum('status_type', ['on-time', 'late', 'no-show', 'absent']);
            $table->integer('late_minutes')->default(0);
            
            // Reason fields for different status types
            $table->text('reason')->nullable(); // Alasan untuk izin/dinas
            $table->string('location', 255)->nullable();
            $table->string('purpose', 255)->nullable();
            
            // --- CUTI FIELDS ---
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->text('rejection_reason')->nullable(); // Untuk alasan penolakan oleh admin
            
            // Additional fields for leave tracking
            $table->date('tanggal_akhir')->nullable(); // End date for leave
            $table->string('jenis_cuti')->nullable(); // Type of leave: Cuti Tahunan, Cuti Sakit, etc.
            $table->text('alasan_cuti')->nullable(); // Specific reason for leave
            // --- END CUTI FIELDS ---
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->unique(['user_id', 'tanggal']);
            $table->index(['user_id', 'tanggal']);
            $table->index('tanggal');
            $table->index('status');
            $table->index('status_type');
            $table->index('approval_status');
            $table->index('tanggal_akhir'); // For leave date range queries
            $table->index('jenis_cuti'); // For leave type filtering
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
}