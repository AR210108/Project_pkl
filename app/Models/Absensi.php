<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     * @var string
     */
    protected $table = 'absensis'; 

    /**
     * Koneksi database yang digunakan (jika ada multiple connections).
     * @var string
     */
    protected $connection = 'mysql'; 

    /**
     * Atribut yang dapat diisi secara massal (mass assignment).
     * Menggunakan $fillable lebih aman dan eksplisit.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'status_type',
        'late_minutes',
        'reason',
        'location',
        'purpose',
    ];

    /**
     * Atribut yang harus di-cast (konversi tipe otomatis).
     * INI ADALAH BAGIAN TERPENTING untuk menyelesaikan masalah timezone.
     * Dengan ini, Laravel akan otomatis mengubah string dari DB menjadi objek Carbon,
     * dan mengonversinya ke format ISO 8601 saat dikirim sebagai JSON.
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime',
        'jam_pulang' => 'datetime',
        'late_minutes' => 'integer',
    ];

    // =================================================================
    // RELATIONSHIPS
    // =================================================================

    /**
     * Mendapatkan user yang terkait dengan data absensi ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // =================================================================
    // SCOPES (Query Builder Lokal)
    // =================================================================

    /**
     * Scope untuk memfilter data absensi hari ini.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', now()->format('Y-m-d'));
    }

    /**
     * Scope untuk memfilter data absensi berdasarkan user ID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk memfilter data absensi pada bulan ini.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
    }
}