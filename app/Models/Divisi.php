<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';

    protected $fillable = [
        'divisi',
        'jumlah_tim'
    ];

    public function tims()
    {
        return $this->hasMany(Tim::class, 'divisi', 'divisi');
    }


    // Accessor untuk jumlah_tim yang dihitung dinamis
    public function getJumlahTimAttribute($value)
    {
        // Jika ada value, gunakan, jika tidak hitung
        if ($value !== null) {
            return $value;
        }
        return $this->tims()->count();
    }

    // Boot method untuk menghitung ulang jumlah_tim saat create/update/delete


    // Method untuk update jumlah_tim
public function updateJumlahTim()
{
    $count = $this->tims()->count(); // ← Ini memanggil query yang mungkin recursive
    $this->update(['jumlah_tim' => (string)$count]); // ← Ini trigger saved() event lagi!
}
}