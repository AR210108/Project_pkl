<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Divisi extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'divisi';

    protected $fillable = [
        'divisi',
        'jumlah_tim'
    ];

    /**
     * Relasi: Satu Divisi memiliki banyak Tim.
     * Asumsi: Tabel 'tims' memiliki kolom 'divisi' (nama divisi).
     */
    public function tims()
    {
        return $this->hasMany(Tim::class, 'divisi', 'divisi');
    }

    /**
     * Accessor: Mengambil nilai jumlah_tim.
     * Kita tidak perlu menghitung dinamis di sini agar tidak membebani query,
     * cukup ambil dari database yang sudah diperbarui oleh event observer.
     */
    public function getJumlahTimAttribute($value)
    {
        return (int) $value;
    }

    /**
     * Method Khusus: Update manual jumlah tim jika diperlukan.
     * Menggunakan updateQuietly untuk MENCEGAH trigger event saved (anti-looping).
     */
    public function updateJumlahTim()
    {
        try {
            // Hitung jumlah tim berdasarkan relasi
            $count = $this->tims()->count();
            
            // Gunakan updateQuietly agar tidak memicu event model lain (mencegah infinite loop)
            $this->updateQuietly(['jumlah_tim' => $count]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Gagal update jumlah tim untuk divisi {$this->divisi}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Boot Method: Mendaftarkan Event Observer
     * Ini akan otomatis menjalankan updateJumlahTim setiap ada perubahan data Tim.
     */
    protected static function booted()
    {
        // Event: Saat Tim dibuat
        static::created(function ($divisi) {
            $divisi->updateJumlahTim();
        });

        // Event: Saat Tim dihapus
        static::deleted(function ($divisi) {
            $divisi->updateJumlahTim();
        });
    }
}