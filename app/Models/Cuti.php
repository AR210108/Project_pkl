<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuti extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cuti';

    protected $fillable = [
        'user_id',
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

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'disetujui_pada' => 'datetime',
    ];

    /**
     * RELASI
     */
    public function user()
    {
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
     * ACCESSORS (TANPA NIK)
     */
    public function getNamaKaryawanAttribute()
    {
        return $this->user ? $this->user->name : 'Unknown';
    }

    public function getDivisiKaryawanAttribute()
    {
        return $this->user ? $this->user->divisi : 'Unknown';
    }

   

    public function getSisaCutiKaryawanAttribute()
    {
        return $this->user ? $this->user->sisa_cuti : 0;
    }

    /**
     * Format tanggal dengan fallback
     */
    public function getTanggalMulaiFormattedAttribute()
    {
        return $this->tanggal_mulai ? $this->tanggal_mulai->format('d F Y') : '-';
    }

    public function getTanggalSelesaiFormattedAttribute()
    {
        return $this->tanggal_selesai ? $this->tanggal_selesai->format('d F Y') : '-';
    }

    public function getPeriodeAttribute()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) return '-';
        return $this->tanggal_mulai->format('d/m/Y') . ' - ' . $this->tanggal_selesai->format('d/m/Y');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'menunggu' => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];
        
        return $labels[$this->status] ?? ucfirst($this->status);
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
     * SCOPES
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

    public function scopeUntukUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * BUSINESS LOGIC
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

    public function isOverlapping()
    {
        return self::where('user_id', $this->user_id)
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