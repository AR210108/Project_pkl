<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kwitansi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'kwitansis';

    /**
     * Atribut yang dapat diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'invoice_id',           // ID invoice yang terkait
        'nama_perusahaan',
        'nomor_order',
        'tanggal',
        'nama_klien',
        'deskripsi',
        'harga',
        'sub_total',
        'fee_maintenance',
        'total',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data asli.
     */
    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'fee_maintenance' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Mendapatkan invoice yang terkait dengan kwitansi ini.
     * Relasi: Satu Invoice bisa memiliki banyak Kwitansi.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
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
     * Get formatted sub_total attribute.
     * 
     * @return string
     */
    public function getFormattedSubTotalAttribute()
    {
        return 'Rp ' . number_format($this->sub_total, 0, ',', '.');
    }
    
    /**
     * Get formatted fee_maintenance attribute.
     * 
     * @return string
     */
    public function getFormattedFeeMaintenanceAttribute()
    {
        return 'Rp ' . number_format($this->fee_maintenance, 0, ',', '.');
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
     * Boot model untuk menghitung total otomatis.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Calculate total before saving
        static::saving(function ($kwitansi) {
            // Jika sub_total dan fee_maintenance ada, hitung total
            if (isset($kwitansi->sub_total) && isset($kwitansi->fee_maintenance)) {
                $kwitansi->total = $kwitansi->sub_total + $kwitansi->fee_maintenance;
            }
            // Jika hanya harga yang ada, gunakan harga sebagai sub_total
            elseif (isset($kwitansi->harga) && !isset($kwitansi->sub_total)) {
                $kwitansi->sub_total = $kwitansi->harga;
                $kwitansi->total = $kwitansi->harga + ($kwitansi->fee_maintenance ?? 0);
            }
        });
    }
}