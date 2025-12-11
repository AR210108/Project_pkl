<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_klien',
        'nomor_order',
        'detail_layanan',
        'harga',
        'pajak',
        'metode_pembayaran',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'pajak' => 'decimal:2',
    ];

    /**
     * Get the total amount including tax
     */
    public function getTotalAttribute()
    {
        return $this->harga + $this->pajak;
    }
}