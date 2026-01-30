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
        'nama',
        'deskripsi',
        'harga',
        'deadline',
        'progres',
        'status',
        'penanggung_jawab_id',
    ];

    // TAMBAHKAN CASTING
    protected $casts = [
        'progres' => 'integer',
        'status' => 'string', // Cast sebagai string
        'deadline' => 'datetime',
        'harga' => 'integer',
    ];

    // Atau gunakan mutator untuk status
    public function setStatusAttribute($value)
    {
        // Normalisasi status
        $statusMap = [
            'pending' => 'Pending',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
        ];
        
        $lowerValue = strtolower($value);
        $this->attributes['status'] = $statusMap[$lowerValue] ?? ucfirst($value);
    }

}