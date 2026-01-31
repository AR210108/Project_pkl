<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // TASKS TABLE
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('deadline');
            $table->enum('status', ['pending', 'proses', 'selesai', 'dibatalkan'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            
            // Foreign keys - tanpa constraint dulu
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('assigned_by_manager')->nullable();
            
            $table->enum('target_type', ['karyawan', 'divisi', 'manager'])->default('karyawan');
            $table->string('target_divisi')->nullable();
            $table->boolean('is_broadcast')->default(false);
            
            $table->string('submission_file')->nullable();
            $table->text('submission_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            
            $table->text('catatan')->nullable();
            $table->text('catatan_update')->nullable();
            
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // TASK_FILES TABLE
        Schema::create('task_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->bigInteger('size')->default(0);
            $table->string('mime_type')->nullable();
            $table->text('description')->nullable();
            
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });

        // TASK_COMMENTS TABLE
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            
            $table->text('content');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('task_files');
        Schema::dropIfExists('tasks');
    }
};