<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi'; // pakai nama tabel asli di database

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang'
    ];
}
