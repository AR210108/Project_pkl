<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;
    
    // Tambahkan baris ini untuk menentukan nama tabel secara manual
    protected $table = 'pengumuman';
    
    protected $fillable = [
        'judul',
        'judul_informasi',
        'isi_pesan',
        'lampiran',
    ];
}