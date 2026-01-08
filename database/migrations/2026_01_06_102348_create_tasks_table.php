<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('prioritas', ['tinggi', 'normal', 'rendah'])->default('normal');
            $table->dateTime('deadline');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->enum('status', ['pending', 'proses', 'selesai', 'dibatalkan'])->default('pending');
            $table->enum('target_type', ['karyawan', 'divisi', 'manager'])->default('karyawan');
            $table->string('target_divisi')->nullable(); // Ubah dari foreign key ke string
            $table->unsignedBigInteger('target_manager_id')->nullable();
            $table->string('kategori')->nullable();
            $table->text('catatan')->nullable();
            $table->text('catatan_update')->nullable();
            $table->boolean('is_broadcast')->default(false);
            $table->unsignedBigInteger('assigned_by_manager')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_manager_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_by_manager')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};