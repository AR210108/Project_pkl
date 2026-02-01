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
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');

            $table->string('company_name');
            $table->text('company_address');

            $table->string('client_name');
            $table->string('order_number')->nullable();

            // TAMBAHKAN FIELD BARU DI SINI
            $table->string('nama_layanan')->nullable();
            $table->enum('status_pembayaran', ['pembayaran awal', 'lunas'])->default('pembayaran awal');

            $table->string('payment_method');
            $table->text('description')->nullable();

            $table->integer('subtotal')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('total')->default(0);

            $table->timestamps();

            // Indexes
            $table->index('company_name');
            $table->index('client_name');
            $table->index('invoice_date');
            $table->index('nama_layanan'); // Tambah index
            $table->index('status_pembayaran'); // Tambah index
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
