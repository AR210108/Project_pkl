<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    /**
     * The table associated with model.
     *
     * @var string
     */
    protected $table = 'absensis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'tanggal',
        'tanggal_akhir', // Untuk cuti
        'jam_masuk',
        'jam_pulang',
        'is_early_checkout',
        'early_checkout_reason',
        'status',
        'status_type',
        'jenis_cuti', // Untuk cuti: Cuti Tahunan, Cuti Sakit, dll
        'late_minutes',
        'reason', // Alasan untuk izin/dinas
        'alasan_cuti', // Alasan khusus untuk cuti
        'location',
        'purpose',
        'approval_status',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'tanggal_akhir' => 'date',
            'jam_masuk' => 'datetime:H:i',
            'jam_pulang' => 'datetime:H:i',
            'is_early_checkout' => 'boolean',
        ];
    }

    /**
     * Mendapatkan user yang memiliki data absensi ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
public function user()
{
    return $this->belongsTo(User::class);
}

    /**
     * Scope untuk filter data absensi (bukan cuti)
     */
    public function scopeAbsensi($query)
    {
        return $query->whereNotIn('status', ['Cuti']);
    }

    /**
     * Scope untuk filter data cuti
     */
    public function scopeCuti($query)
    {
        return $query->where('status', 'Cuti');
    }

    /**
     * Accessor untuk mendapatkan tipe data (absensi atau cuti)
     */
    public function getTipeAttribute()
    {
        return $this->status === 'Cuti' ? 'cuti' : 'absensi';
    }

    /**
     * Accessor untuk mendapatkan durasi cuti
     */
    public function getDurasiCutiAttribute()
    {
        if ($this->status !== 'Cuti' || !$this->tanggal_akhir) {
            return null;
        }
        
        $start = \Carbon\Carbon::parse($this->tanggal);
        $end = \Carbon\Carbon::parse($this->tanggal_akhir);
        
        return $start->diffInDays($end) + 1; // +1 untuk inklusif
    }
}