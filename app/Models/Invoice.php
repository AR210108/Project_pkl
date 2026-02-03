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
        'status',
        'nama_layanan', // Tambah field baru
        'status_pembayaran' // Tambah field baru
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'integer',
        'tax' => 'integer',
        'total' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Optional: Tambahkan accessor jika perlu kompatibilitas dengan field lama
    public function getNamaPerusahaanAttribute()
    {
        return $this->company_name;
    }

    public function getNamaKlienAttribute()
    {
        return $this->client_name;
    }

    public function getAlamatAttribute()
    {
        return $this->company_address;
    }

    public function getPajakAttribute()
    {
        return $this->tax;
    }

    public function getTotalAttribute()
    {
        return $this->attributes['total'] ?? 0;
    }

    // Relationship dengan model Layanan (jika ada)
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'nama_layanan', 'nama_layanan');
    }
}