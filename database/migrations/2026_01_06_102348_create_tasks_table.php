<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ========== TASKS TABLE ==========
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('deadline');
            
            // Status
            $table->enum('status', ['pending', 'proses', 'selesai', 'dibatalkan'])
                ->default('pending');
            
            // Priority - untuk filter & sorting
            $table->enum('priority', ['low', 'medium', 'high'])
                ->default('medium');
            
            // ====== KOLOM SUBMISSION ======
            // File hasil tugas (path di storage)
            $table->string('submission_file')->nullable();
            
            // Catatan saat submit tugas
            $table->text('submission_notes')->nullable();
            
            // Waktu submit (auto ketika status selesai)
            $table->timestamp('submitted_at')->nullable();
            // ===============================
            
            // Foreign keys
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('assigned_by_manager')->nullable();
            $table->unsignedBigInteger('target_manager_id')->nullable();
            
            // Target assignment
            $table->enum('target_type', ['karyawan', 'divisi', 'manager'])->default('karyawan');
            $table->string('target_divisi')->nullable();
            $table->boolean('is_broadcast')->default(false);
            
            // Notes
            $table->text('catatan')->nullable();
            $table->text('catatan_update')->nullable();
            
            // Timestamps
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Soft deletes
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_by_manager')->references('id')->on('users')->onDelete('set null');
            $table->foreign('target_manager_id')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('status');
            $table->index('priority');
            $table->index('created_by');
            $table->index('assigned_to');
            $table->index('target_type');
            $table->index('target_divisi');
            $table->index('deadline');
            $table->index('submitted_at');
            $table->index(['assigned_to', 'status']);
            $table->index(['created_by', 'status']);
            $table->index('deleted_at');
            $table->index(['priority', 'deadline']); // Untuk sorting
            $table->index(['status', 'submitted_at']); // Untuk laporan
        });

        // ========== COMMENTS TABLE ==========
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
            
            // Indexes
            $table->index(['task_id', 'created_at']);
            $table->index('user_id');
            $table->index(['task_id', 'user_id']);
        });

        // ========== TASK_FILES TABLE ==========
        Schema::create('task_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            
            // ✅ PERBAIKAN: user_id dengan default value
            $table->foreignId('user_id')
                ->default(1) // Default ke admin (user_id = 1)
                ->constrained('users')
                ->onDelete('cascade');
            
            $table->string('filename'); // Nama file di storage
            $table->string('original_name'); // Nama asli file
            $table->string('path'); // Path di storage
            $table->bigInteger('size')->default(0); // Size in bytes
            $table->string('mime_type')->nullable(); // File type
            $table->text('description')->nullable(); // ✅ Tambahkan description
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
            
            // Indexes
            $table->index(['task_id', 'user_id']);
            $table->index('uploaded_at');
            $table->index('mime_type');
            $table->index(['task_id', 'uploaded_at']);
        });
        
        // ✅ TAMBAHKAN: Ensure admin user exists
        $this->ensureAdminUserExists();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop dependent tables first
        Schema::dropIfExists('task_files');
        Schema::dropIfExists('comments');
        
        // Drop main table
        Schema::dropIfExists('tasks');
    }
    
    /**
     * Ensure admin user exists for foreign key constraint
     */
    private function ensureAdminUserExists(): void
    {
        // Cek apakah ada user dengan ID 1
        $adminExists = DB::table('users')->where('id', 1)->exists();
        
        if (!$adminExists) {
            // Buat user admin default jika tidak ada
            DB::table('users')->insert([
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@system.local',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};