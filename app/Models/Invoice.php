<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    // Use the actual database column names (Indonesian) used in migrations
    protected $fillable = [
        'nomor_order',
        'nama_perusahaan',
        'nama_klien',
        'alamat',
        'deskripsi',
        'harga',
        'qty',
        'invoice_no',
        'invoice_date',
        'company_name',
        'company_address',
        'client_name',
        'order_number',
        'payment_method',
        'description',
        'subtotal',
        'tax',
        'total',
        'pajak',
        'metode_pembayaran',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'decimal:2',
        'total' => 'decimal:2',
        'pajak' => 'decimal:2',
        'qty' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
