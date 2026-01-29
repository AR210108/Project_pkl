<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tim extends Model
{
    use HasFactory;

    protected $table = 'tim';

    protected $fillable = [
        'tim',
        'divisi',
        'jumlah_anggota'
    ];

    // Relasi ke divisi berdasarkan nama divisi
    public function divisiRel()
    {
        return $this->belongsTo(Divisi::class, 'divisi', 'divisi');
    }

}