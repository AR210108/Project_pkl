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
     * Relasi ke Project - PERBAIKAN: gunakan 'project' bukan 'projects'
     */
    public function projects()
    {
        protected $fillable = [
            'nama_layanan',
            'deskripsi',
            'hpp',
            'harga',
            'foto',
        ];
}