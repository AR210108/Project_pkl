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
        'divisi',
        // ... kolom lainnya
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
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin','finance']);
    }

    /**
     * Cek apakah user adalah karyawan
     */
    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }

    /**
     * Cek apakah user adalah general manager
     */
    public function isGeneralManager(): bool
    {
        return $this->role === 'general_manager';
    }

    /**
     * Cek apakah user adalah manager divisi
     */
    public function isManagerDivisi(): bool
    {
        return $this->role === 'manager_divisi';
    }

    /**
     * Cek apakah user adalah owner
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Relasi dengan pengumuman
     */
    public function pengumuman()
    {
        return $this->belongsToMany(Pengumuman::class, 'pengumuman_user');
    }

    /**
     * Relasi sebagai creator pengumuman
     */
    public function createdPengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'user_id');
    }

    /**
     * Relasi dengan catatan rapat sebagai peserta
     */
    public function catatanRapatPeserta()
    {
        return $this->belongsToMany(CatatanRapat::class, 'catatan_rapat_peserta');
    }

    /**
     * Relasi dengan catatan rapat sebagai penugasan
     */
    public function catatanRapatPenugasan()
    {
        return $this->belongsToMany(CatatanRapat::class, 'catatan_rapat_penugasan');
    }

    /**
     * Scope untuk user dengan role tertentu
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk user dalam divisi tertentu
     */
    public function scopeByDivisi($query, $divisi)
    {
        return $query->where('divisi', $divisi);
    }

    /**
     * Accessor untuk nama lengkap dengan role
     */
    public function getFullNameWithRoleAttribute(): string
    {
        return "{$this->name} ({$this->role})";
    }

    /**
     * Accessor untuk inisial nama
     */
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

/**
 * Pengumuman yang ditugaskan ke user ini
 */
public function pengumumanDiterima()
{
    return $this->belongsToMany(Pengumuman::class, 'pengumuman_user', 'user_id', 'pengumuman_id')
        ->withTimestamps();
}
}