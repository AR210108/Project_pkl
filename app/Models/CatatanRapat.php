<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanRapat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'peserta',
        'topik',
        'hasil_diskusi',
        'keputusan',
        'penugasan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal->format('d/m/Y');
    }
}