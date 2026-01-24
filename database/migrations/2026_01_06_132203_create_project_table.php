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
            $table->foreignId('layanan_id')
              ->constrained('layanans') // kalau tabel kamu namanya "layanan"
              ->cascadeOnDelete();
            $table->string('nama');
            $table->text('deskripsi');
            $table->string('harga')->nullable();
            $table->date('deadline');
            $table->integer('progres')->nullable();
            $table->enum('status', ['Pending', 'Dalam Pengerjaan', 'Selesai', 'Dibatalkan'])->default('Pending');
            $table->timestamps();
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