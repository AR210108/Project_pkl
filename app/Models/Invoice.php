<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomor_order',
        'nama_perusahaan',
        'nama_klien',
        'alamat',
        'deskripsi',
        'harga',
        'qty',
        'total',
        'pajak',
        'metode_pembayaran',
        'tanggal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'decimal:2',
        'pajak' => 'decimal:2',
        'total' => 'decimal:2',
        'qty' => 'integer',
    ];

    /**
     * Get kwitansis for the invoice.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kwitansis()
    {
        return $this->hasMany(Kwitansi::class, 'invoice_id');
    }
    
    /**
     * Get formatted harga attribute.
     * 
     * @return string
     */
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
    
    /**
     * Get formatted total attribute.
     * 
     * @return string
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
    
    /**
     * Get formatted tanggal attribute.
     * 
     * @return string
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d M Y') : '';
    }
    
    /**
     * Calculate total automatically before saving.
     * 
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // Calculate total if harga, qty, and pajak are set
        if (isset($this->harga) && isset($this->qty) && isset($this->pajak)) {
            $subtotal = $this->harga * $this->qty;
            $this->total = $subtotal + ($subtotal * $this->pajak / 100);
        }
        
        return parent::save($options);
    }
}