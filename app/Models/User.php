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
        'username',
        'phone',
        'role',
        'divisi',
        'location',
        'bio',
        'foto',
        'language',
        'timezone',
        'date_format',
        'email_notifications',
        'push_notifications',
        'sms_notifications',
        'weekly_reports',
        'product_updates',
        'special_offers',
        'two_factor_enabled'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'weekly_reports' => 'boolean',
        'product_updates' => 'boolean',
        'special_offers' => 'boolean',
        'two_factor_enabled' => 'boolean'
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

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
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

    /**
     * Accessor untuk URL foto profil
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/profiles/' . $this->foto);
        }
        
        // Default avatar jika tidak ada foto
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Mendapatkan preferensi notifikasi user
     */
    public function getNotificationPreferences()
    {
        return [
            'email_notifications' => $this->email_notifications ?? true,
            'push_notifications' => $this->push_notifications ?? true,
            'sms_notifications' => $this->sms_notifications ?? false,
            'weekly_reports' => $this->weekly_reports ?? false,
            'product_updates' => $this->product_updates ?? true,
            'special_offers' => $this->special_offers ?? true,
        ];
    }

    /**
     * Update preferensi notifikasi user
     */
    public function updateNotificationPreferences($preferences)
    {
        $this->update([
            'email_notifications' => $preferences['email_notifications'] ?? $this->email_notifications,
            'push_notifications' => $preferences['push_notifications'] ?? $this->push_notifications,
            'sms_notifications' => $preferences['sms_notifications'] ?? $this->sms_notifications,
            'weekly_reports' => $preferences['weekly_reports'] ?? $this->weekly_reports,
            'product_updates' => $preferences['product_updates'] ?? $this->product_updates,
            'special_offers' => $preferences['special_offers'] ?? $this->special_offers,
        ]);
    }

    /**
     * Mendapatkan preferensi akun user
     */
    public function getAccountPreferences()
    {
        return [
            'username' => $this->username,
            'language' => $this->language ?? 'id',
            'timezone' => $this->timezone ?? 'Asia/Jakarta',
            'date_format' => $this->date_format ?? 'd/m/Y',
        ];
    }

    /**
     * Update preferensi akun user
     */
    public function updateAccountPreferences($preferences)
    {
        $this->update([
            'username' => $preferences['username'] ?? $this->username,
            'language' => $preferences['language'] ?? $this->language,
            'timezone' => $preferences['timezone'] ?? $this->timezone,
            'date_format' => $preferences['date_format'] ?? $this->date_format,
        ]);
    }
}