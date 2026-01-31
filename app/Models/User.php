<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'divisi_id',
        'sisa_cuti',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke tabel karyawan
     */
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'user_id');
    }

    /**
     * Relasi langsung ke tabel cuti
     */
    public function cuti()
    {
        return $this->hasMany(Cuti::class, 'user_id');
    }

    public function cutiMenunggu()
    {
        return $this->cuti()->where('status', 'menunggu');
    }

    public function cutiDisetujui()
    {
        return $this->cuti()->where('status', 'disetujui');
    }

    public function cutiDitolak()
    {
        return $this->cuti()->where('status', 'ditolak');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'finance']);
    }

    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }

    public function isGeneralManager(): bool
    {
        return $this->role === 'general_manager';
    }

    public function isManagerDivisi(): bool
    {
        return $this->role === 'manager_divisi';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function pengumuman()
    {
        return $this->belongsToMany(Pengumuman::class, 'pengumuman_user');
    }

    public function createdPengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'user_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function catatanRapatPeserta()
    {
        return $this->belongsToMany(CatatanRapat::class, 'catatan_rapat_peserta');
    }

    public function catatanRapatPenugasan()
    {
        return $this->belongsToMany(CatatanRapat::class, 'catatan_rapat_penugasan');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByDivisiId($query, $divisiId)
    {
        return $query->where('divisi_id', $divisiId);
    }

    public function getFullNameWithRoleAttribute(): string
    {
        return "{$this->name} ({$this->role})";
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials;
    }

    public function catatanRapats()
    {
        return $this->belongsToMany(CatatanRapat::class, 'catatan_rapat_penugasan', 'user_id', 'catatan_rapat_id');
    }

    public function catatanRapatPenugasans()
    {
        return $this->hasMany(CatatanRapatPenugasan::class, 'user_id');
    }

    public function pengumumanDiterima()
    {
        return $this->belongsToMany(Pengumuman::class, 'pengumuman_user', 'user_id', 'pengumuman_id')
            ->withTimestamps();
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    /**
     * Sinkronkan data ke karyawan jika ada
     */
    public function syncToKaryawan(): void
    {
        if ($this->karyawan) {
            $karyawan = $this->karyawan;
            $karyawan->nama = $this->name;
            $karyawan->email = $this->email;
            
            if ($this->divisi) {
                $karyawan->divisi = $this->divisi->divisi;
            }
            
            if (!$karyawan->jabatan || $karyawan->jabatan === '') {
                $karyawan->jabatan = $this->role;
            }
            
            $karyawan->save();
        }
    }


}
