<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Layanan; // Tambahkan ini
use App\Models\User; // Tambahkan ini

class Project extends Model
{
    use HasFactory;
    
    protected $table = 'project'; // Nama tabel singular
    
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

    // **TAMBAHKAN RELASI LAYANAN**
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    // **TAMBAHKAN RELASI PENANGGUNG JAWAB**
    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    // Atau gunakan mutator untuk status
    public function setStatusAttribute($value)
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
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

    // **TAMBAHKAN ACCESSOR UNTUK STATUS YANG AMAN**
    public function getStatusFormattedAttribute()
    {
        $status = $this->attributes['status'] ?? $this->status;
        
        $statusMap = [
            'pending' => 'Pending',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'Pending' => 'Pending',
            'Proses' => 'Proses',
            'Selesai' => 'Selesai',
        ];
        
        $lowerStatus = strtolower($status);
        return $statusMap[$lowerStatus] ?? $status;
    }
}