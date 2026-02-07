<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'foto',
    ];
    
    /**
     * Relasi ke Project
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'layanan_id');
    }
}