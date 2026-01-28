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

    // Relasi ke tim berdasarkan nama divisi
    public function tims()
    {
        return $this->hasMany(Tim::class, 'divisi', 'divisi');
    }

    // Accessor untuk jumlah_tim yang dihitung dinamis
    public function getJumlahTimAttribute()
    {
        return $this->tims()->count();
    }

    // Boot method untuk menghitung ulang jumlah_tim saat create/update/delete
    public static function boot()
    {
        parent::boot();

        // Saat divisi dibuat, set jumlah_tim = 0
        static::creating(function ($model) {
            $model->jumlah_tim = '0';
        });

        // Saat tim dibuat/diupdate/dihapus, update jumlah_tim divisi
        static::saved(function ($model) {
            $model->updateJumlahTim();
        });
    }

    // Method untuk update jumlah_tim
    public function updateJumlahTim()
    {
        $count = $this->tims()->count();
        $this->update(['jumlah_tim' => (string)$count]);
    }
}