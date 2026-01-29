<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'user_id',
        'nama',
        'jabatan',
        'divisi',
        'gaji',
        'alamat',
        'kontak',
        'foto'
    ];

    /**
     * Relasi ke tabel users.
     * Setiap karyawan dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel cuti.
     * Satu karyawan dapat memiliki banyak cuti.
     */
    public function cuti()
    {
        return $this->hasMany(Cuti::class);
    }

    /**
     * Relasi ke tabel cuti dengan status menunggu.
     */
    public function cutiMenunggu()
    {
        return $this->hasMany(Cuti::class)->where('status', 'menunggu');
    }

    /**
     * Relasi ke tabel cuti dengan status disetujui.
     */
    public function cutiDisetujui()
    {
        return $this->hasMany(Cuti::class)->where('status', 'disetujui');
    }

    /**
     * Relasi ke tabel cuti dengan status ditolak.
     */
    public function cutiDitolak()
    {
        return $this->hasMany(Cuti::class)->where('status', 'ditolak');
    }

    /**
     * Relasi ke tabel cuti tahunan.
     */
    public function cutiTahunan()
    {
        return $this->hasMany(Cuti::class)->where('jenis_cuti', 'tahunan');
    }
}