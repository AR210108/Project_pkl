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
        'foto',
        'email'
    ];

    /**
     * Relasi ke tabel users.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getJabatanFromUserAttribute()
    {
        if ($this->user) {
            return $this->user->role;
        }
        return $this->jabatan;
    }
    
    public function getNamaFromUserAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->nama;
    }

    public function cuti()
    {
        return $this->hasMany(Cuti::class);
    }

    public function cutiMenunggu()
    {
        return $this->hasMany(Cuti::class)->where('status', 'menunggu');
    }

    public function cutiDisetujui()
    {
        return $this->hasMany(Cuti::class)->where('status', 'disetujui');
    }

    public function cutiDitolak()
    {
        return $this->hasMany(Cuti::class)->where('status', 'ditolak');
    }

    public function cutiTahunan()
    {
        return $this->hasMany(Cuti::class)->where('jenis_cuti', 'tahunan');
    }

}