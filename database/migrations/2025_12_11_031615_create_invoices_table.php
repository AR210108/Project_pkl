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
            $table->string('nama_klien'); // Client name
            $table->string('nomor_order')->unique(); // Order number
            $table->text('detail_layanan'); // Service details
            $table->decimal('harga', 15, 2); // Price
            $table->decimal('pajak', 15, 2)->default(0); // Tax
            $table->string('metode_pembayaran'); // Payment method
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