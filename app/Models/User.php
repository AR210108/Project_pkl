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
        'gaji',
        'alamat',
        'kontak',
        'status_kerja',
        'status_karyawan',
        'foto',
        'email_verified_at',
        'sisa_cuti'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'gaji' => 'decimal:2'
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

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Buat record karyawan otomatis jika user baru dibuat
            if (in_array($user->role, ['owner', 'admin', 'general_manager', 'manager_divisi', 'finance', 'karyawan'])) {
                $karyawanData = [
                    'user_id' => $user->id,
                    'nama' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'alamat' => $user->alamat,
                    'kontak' => $user->kontak,
                    'gaji' => $user->gaji,
                    'foto' => $user->foto
                ];

                // Tambahkan divisi jika ada
                if ($user->divisi) {
                    $karyawanData['divisi'] = $user->divisi->divisi;
                }

                // Cek apakah karyawan sudah ada
                if (!$user->karyawan()->exists()) {
                    $user->karyawan()->create($karyawanData);
                }
            }
        });

        static::updated(function ($user) {
            // Update karyawan jika user diupdate
            if ($user->karyawan) {
                $karyawan = $user->karyawan;
                $karyawan->nama = $user->name;
                $karyawan->email = $user->email;
                $karyawan->role = $user->role;
                $karyawan->alamat = $user->alamat;
                $karyawan->kontak = $user->kontak;
                $karyawan->gaji = $user->gaji;
                
                if ($user->divisi) {
                    $karyawan->divisi = $user->divisi->divisi;
                }
                
                if ($user->foto) {
                    $karyawan->foto = $user->foto;
                }
                
                $karyawan->save();
            }
        });

        static::deleting(function ($user) {
            // Hapus karyawan terkait saat user dihapus
            if ($user->karyawan) {
                $user->karyawan->delete();
            }
        });
    }

    /**
     * Method untuk membuat karyawan dari user
     */
    public function createKaryawan()
    {
        if (!$this->karyawan()->exists()) {
            $karyawanData = [
                'user_id' => $this->id,
                'nama' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'alamat' => $this->alamat,
                'kontak' => $this->kontak,
                'gaji' => $this->gaji,
                'foto' => $this->foto
            ];

            if ($this->divisi) {
                $karyawanData['divisi'] = $this->divisi->divisi;
            }

            return $this->karyawan()->create($karyawanData);
        }

        return $this->karyawan;
    }

    /**
     * Method untuk update karyawan dari user
     */
    public function updateKaryawan()
    {
        if ($this->karyawan) {
            $karyawan = $this->karyawan;
            $karyawan->nama = $this->name;
            $karyawan->email = $this->email;
            $karyawan->role = $this->role;
            $karyawan->alamat = $this->alamat;
            $karyawan->kontak = $this->kontak;
            $karyawan->gaji = $this->gaji;
            
            if ($this->divisi) {
                $karyawan->divisi = $this->divisi->divisi;
            }
            
            if ($this->foto) {
                $karyawan->foto = $this->foto;
            }
            
            return $karyawan->save();
        }

        return false;
    }

    /**
     * Method untuk menghapus karyawan terkait
     */
    public function deleteKaryawan()
    {
        if ($this->karyawan) {
            return $this->karyawan->delete();
        }

        return false;
    }

    /**
     * Scope untuk mendapatkan users yang belum memiliki karyawan
     */
    public function scopeWithoutKaryawan($query)
    {
        return $query->whereNotIn('id', function ($query) {
            $query->select('user_id')
                ->from('karyawan')
                ->whereNotNull('user_id');
        });
    }

    /**
     * Scope untuk mendapatkan users dengan role karyawan
     */
    public function scopeKaryawanRole($query)
    {
        return $query->whereIn('role', [
            'karyawan', 'staff', 'manager', 'supervisor', 'direktur', 
            'kepala_divisi', 'general_manager', 'admin', 'finance', 
            'hrd', 'intern', 'magang'
        ]);
    }

    /**
     * Get status kerja text
     */
    public function getStatusKerjaTextAttribute()
    {
        $statuses = [
            'aktif' => 'Aktif',
            'resign' => 'Resign',
            'phk' => 'PHK'
        ];

        return $statuses[$this->status_kerja] ?? $this->status_kerja;
    }

    /**
     * Get status karyawan text
     */
    public function getStatusKaryawanTextAttribute()
    {
        $statuses = [
            'tetap' => 'Karyawan Tetap',
            'kontrak' => 'Karyawan Kontrak',
            'freelance' => 'Freelance'
        ];

        return $statuses[$this->status_karyawan] ?? $this->status_karyawan;
    }

    /**
     * Check if user is active employee
     */
    public function getIsActiveAttribute()
    {
        return $this->status_kerja === 'aktif';
    }

    /**
     * Get foto URL
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            // Cek jika foto sudah berupa URL lengkap
            if (str_starts_with($this->foto, 'http')) {
                return $this->foto;
            }
            
            // Cek jika foto sudah memiliki storage path
            if (str_starts_with($this->foto, 'storage/')) {
                return asset($this->foto);
            }
            
            // Default ke storage path
            return asset('storage/' . $this->foto);
        }
        
        return asset('images/default-avatar.png');
    }

    /**
     * Get full data for karyawan form
     */
    public function getKaryawanFormData()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'divisi_id' => $this->divisi_id,
            'gaji' => $this->gaji,
            'alamat' => $this->alamat,
            'kontak' => $this->kontak,
            'status_kerja' => $this->status_kerja,
            'status_karyawan' => $this->status_karyawan,
            'foto' => $this->foto_url,
            'sisa_cuti' => $this->sisa_cuti ?? 12
        ];
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
            
            if (!$karyawan->role || $karyawan->role === '') {
                $karyawan->role = $this->role;
            }
            
            $karyawan->save();
        }
    }


}
