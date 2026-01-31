<?php

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

    protected $casts = [
        'progres' => 'integer',
        'status' => 'string',
        'deadline' => 'datetime',
        'harga' => 'integer',
    ];
    
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }
    
    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }
    
    /**
     * Event ketika project dibuat
     */
    protected static function boot()
    {
        parent::boot();
        
        // Ketika project dibuat, ambil data dari layanan
        static::creating(function ($project) {
            if ($project->layanan_id && !$project->nama) {
                $layanan = Layanan::find($project->layanan_id);
                if ($layanan) {
                    $project->nama = $layanan->nama_layanan;
                    $project->deskripsi = $layanan->deskripsi;
                    $project->harga = $layanan->harga;
                }
            }
        });
    }
}