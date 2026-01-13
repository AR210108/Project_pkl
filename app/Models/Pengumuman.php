<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pengumuman extends Model
{
    use HasFactory;
    
    // Nama tabel
    protected $table = 'pengumuman';

    // Kolom yang dapat diisi
    protected $fillable = [
        'user_id',      // ID pembuat pengumuman
        'judul',
        'isi_pesan',
        'lampiran',
    ];

    // Casting (tidak perlu untuk kepada karena menggunakan pivot)
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * User yang membuat pengumuman
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Users yang menerima pengumuman (many-to-many)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,           // Model terkait
            'pengumuman_user',     // Nama tabel pivot
            'pengumuman_id',       // Foreign key di pivot untuk model ini
            'user_id'              // Foreign key di pivot untuk model User
        )->withTimestamps();
    }

    /**
     * Scope untuk pengumuman yang ditujukan ke user tertentu
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }

    /**
     * Accessor untuk lampiran URL
     */
    public function getLampiranUrlAttribute()
    {
        if (!$this->lampiran) {
            return null;
        }
        
        return asset('storage/' . $this->lampiran);
    }

    /**
     * Accessor untuk tanggal format Indonesia
     */
    public function getTanggalIndoAttribute()
    {
        return $this->created_at->translatedFormat('d F Y H:i');
    }

    /**
     * Accessor untuk ringkasan isi pesan
     */
    public function getRingkasanAttribute()
    {
        return strlen($this->isi_pesan) > 100 
            ? substr($this->isi_pesan, 0, 100) . '...' 
            : $this->isi_pesan;
    }
}