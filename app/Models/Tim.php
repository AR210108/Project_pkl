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

    // Boot method untuk update jumlah_tim di divisi saat tim berubah
    public static function boot()
    {
        parent::boot();

        // Saat tim dibuat
        static::created(function ($tim) {
            if ($tim->divisiRel) {
                $tim->divisiRel->updateJumlahTim();
            }
        });

        // Saat tim diupdate
        static::updated(function ($tim) {
            // Jika divisi berubah, update jumlah_tim di divisi lama dan baru
            if ($tim->isDirty('divisi')) {
                $oldDivisi = Divisi::where('divisi', $tim->getOriginal('divisi'))->first();
                $newDivisi = Divisi::where('divisi', $tim->divisi)->first();
                
                if ($oldDivisi) {
                    $oldDivisi->updateJumlahTim();
                }
                if ($newDivisi) {
                    $newDivisi->updateJumlahTim();
                }
            } else if ($tim->divisiRel) {
                $tim->divisiRel->updateJumlahTim();
            }
        });

        // Saat tim dihapus
        static::deleted(function ($tim) {
            if ($tim->divisiRel) {
                $tim->divisiRel->updateJumlahTim();
            }
        });
    }
}