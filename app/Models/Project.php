<?php
// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $table = 'project';
    
    protected $fillable = [
        'layanan_id',
        'penanggung_jawab_id', // tambahkan ini
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
    
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
    
    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }
}