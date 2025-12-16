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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_order')->unique();
            $table->string('nama_perusahaan');
            $table->string('nama_klien');
            $table->text('alamat');
            $table->text('deskripsi');
            $table->decimal('harga', 15, 2);
            $table->integer('qty')->default(1); // Quantity field
            $table->decimal('total', 15, 2); // Total amount
            $table->decimal('pajak', 5, 2); // Tax percentage
            $table->enum('metode_pembayaran', ['Bank Transfer', 'E-Wallet', 'Credit Card', 'Cash']);
            $table->date('tanggal'); // Date field
            
            // Indexes for query performance
            $table->index('nama_perusahaan');
            $table->index('nama_klien');
            $table->index('tanggal');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};