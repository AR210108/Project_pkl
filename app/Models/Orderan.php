<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderan extends Model
{
    use HasFactory;
    
    // Tambahkan baris ini
    protected $table = 'orderan'; // Beri tahu model untuk menggunakan tabel 'orderan'
    
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