<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    // Tambahkan baris ini
    protected $table = 'project'; // Beri tahu model untuk menggunakan tabel 'orderan'
    
    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'deadline',
        'progres',
        'status'
    ];
    
    protected $casts = [
        'deadline' => 'date',
        'progres' => 'integer'
    ];
}