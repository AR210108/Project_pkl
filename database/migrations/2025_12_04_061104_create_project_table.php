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
    // Gunakan nama singular 'project'
    Schema::create('project', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('layanan_id')
            ->constrained('layanans') 
            ->cascadeOnDelete();
        
        $table->foreignId('divisi_id')
            ->nullable()
            ->constrained('divisi')
            ->onDelete('set null');

        $table->foreignId('penanggung_jawab_id')
            ->nullable()
            ->constrained('users')
            ->onDelete('set null');
        
        $table->string('nama');
        $table->text('deskripsi')->nullable();
        $table->decimal('harga', 15, 2)->nullable();
        $table->dateTime('deadline')->nullable();
        $table->integer('progres')->default(0);
        $table->enum('status', ['Pending', 'Proses', 'Selesai', 'Dibatalkan'])->default('Pending');
        
        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->onDelete('set null');
        
        $table->timestamps();
        $table->softDeletes();
        
        // Indexes
        $table->index('status');
        $table->index('deadline');
        $table->index('layanan_id');
        $table->index('divisi_id');
        $table->index('penanggung_jawab_id');
    });
}

public function down(): void
{
    Schema::dropIfExists('project');
}

 
};