<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('deadline');

            // Status field
            $table->enum('status', ['pending', 'proses', 'selesai', 'dibatalkan'])
                ->default('pending');

            // Foreign key fields
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('assigned_by_manager')->nullable();
            $table->unsignedBigInteger('target_manager_id')->nullable();

            // Target assignment fields
            $table->enum('target_type', ['karyawan', 'divisi', 'manager'])->default('karyawan');
            $table->string('target_divisi')->nullable();
            $table->boolean('is_broadcast')->default(false);

            // Notes fields
            $table->text('catatan')->nullable();
            $table->text('catatan_update')->nullable();

            // Timestamp fields
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Soft deletes
            $table->softDeletes();

            // Foreign keys
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_by_manager')->references('id')->on('users')->onDelete('set null');
            $table->foreign('target_manager_id')->references('id')->on('users')->onDelete('set null');

            // Indexes for better performance
            $table->index('status');
            $table->index('created_by');
            $table->index('assigned_to');
            $table->index('target_type');
            $table->index('target_divisi');
            $table->index('deadline');
            $table->index(['created_by', 'status']);
            $table->index('deleted_at'); // Index untuk soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};