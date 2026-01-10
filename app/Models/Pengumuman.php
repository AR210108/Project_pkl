<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;
    
    // Tambahkan baris ini untuk menentukan nama tabel secara manual
    protected $table = 'pengumuman';

    protected $casts = [
    'kepada' => 'array',
    ];

    protected $fillable = [
        'judul',
        'isi_pesan',
        'kepada',
        'lampiran',
        'tanggal',
    ];

    public function users()
{
    return $this->belongsToMany(User::class);
}

}