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
            
            $table->boolean('is_early_checkout')->default(false);
            $table->text('early_checkout_reason')->nullable();

            $table->enum('status', ['Tepat Waktu', 'Terlambat', 'Tidak Masuk', 'Cuti', 'Sakit', 'Izin', 'Dinas Luar']);
            $table->enum('status_type', ['on-time', 'late', 'no-show', 'absent']);
            $table->integer('late_minutes')->default(0);
            $table->text('reason')->nullable(); // Alasan untuk izin/dinas
            $table->string('location', 255)->nullable();
            $table->string('purpose', 255)->nullable();
            
            // --- TAMBAHKAN INI ---
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->text('rejection_reason')->nullable(); // Untuk alasan penolakan oleh admin
            // --- AKHIR TAMBAHKAN ---
            
            $table->timestamps();
            
            $table->unique(['user_id', 'tanggal']);
            $table->index(['user_id', 'tanggal']);
            $table->index('tanggal');
            $table->index('status_type');
            $table->index('approval_status');
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
};