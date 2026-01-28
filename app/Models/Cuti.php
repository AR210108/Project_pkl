<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuti extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with model.
     *
     * @var string
     */
    protected $table = 'cuti';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',               // [REVISI] Diganti dari karyawan_id
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi',
        'keterangan',
        'jenis_cuti',
        'status',
        'disetujui_oleh',
        'catatan_penolakan',
        'disetujui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'disetujui_pada' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        // [REVISI] Relasi ke User, bukan Karyawan
        return $this->belongsTo(User::class, 'user_id');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function histories()
    {
        return $this->hasMany(CutiHistory::class);
    }

    /**
     * Scopes
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }

    public function scopeTahunIni($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    // [REVISI] Scope untuk filter user, bukan karyawan
    public function scopeUntukUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Accessors & Mutators
     */
    public function getTanggalMulaiFormattedAttribute()
    {
        return $this->tanggal_mulai->translatedFormat('d F Y');
    }

    public function getTanggalSelesaiFormattedAttribute()
    {
        return $this->tanggal_selesai->translatedFormat('d F Y');
    }

    public function getPeriodeAttribute()
    {
        return $this->tanggal_mulai->format('d/m/Y') . ' - ' . $this->tanggal_selesai->format('d/m/Y');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'menunggu' => '<span class="status-badge status-menunggu">Menunggu</span>',
            'disetujui' => '<span class="status-badge status-disetujui">Disetujui</span>',
            'ditolak' => '<span class="status-badge status-ditolak">Ditolak</span>',
        ];

        return $badges[$this->status] ?? '';
    }

    public function getJenisCutiTextAttribute()
    {
        $jenis = [
            'tahunan' => 'Cuti Tahunan',
            'sakit' => 'Cuti Sakit',
            'penting' => 'Cuti Penting',
            'melahirkan' => 'Cuti Melahirkan',
            'lainnya' => 'Cuti Lainnya',
        ];

        return $jenis[$this->jenis_cuti] ?? 'Cuti Lainnya';
    }

    /**
     * Business Logic Methods
     */
    public function dapatDisetujui()
    {
        return $this->status === 'menunggu';
    }

    public function dapatDiubah()
    {
        return $this->status === 'menunggu';
    }

    public function dapatDihapus()
    {
        return $this->status === 'menunggu';
    }

    /**
     * Check if cuti overlaps with existing cuti
     * [REVISI] Menggunakan user_id
     */
    public function isOverlapping()
    {
        return self::where('user_id', $this->user_id) // [REVISI] user_id
            ->where('id', '!=', $this->id)
            ->where('status', 'disetujui')
            ->where(function ($query) {
                $query->whereBetween('tanggal_mulai', [$this->tanggal_mulai, $this->tanggal_selesai])
                    ->orWhereBetween('tanggal_selesai', [$this->tanggal_mulai, $this->tanggal_selesai])
                    ->orWhere(function ($q) {
                        $q->where('tanggal_mulai', '<=', $this->tanggal_mulai)
                            ->where('tanggal_selesai', '>=', $this->tanggal_selesai);
                    });
            })
            ->exists();
    }
}