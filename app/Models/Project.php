<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice; // Changed from Layanan
use App\Models\User;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';

    protected $fillable = [
        'invoice_id',
        'nama',
        'deskripsi',
        'harga',
        'tanggal_mulai_pengerjaan',
        'tanggal_selesai_pengerjaan',
        'tanggal_mulai_kerjasama',
        'tanggal_selesai_kerjasama',
        'status_kerjasama',
        'status_pengerjaan',
        'progres',
        'penanggung_jawab_id',
    ];

    protected $casts = [
        'progres' => 'integer',
        'status_pengerjaan' => 'string',
        'status_kerjasama' => 'string',
        'tanggal_mulai_pengerjaan' => 'datetime',
        'tanggal_selesai_pengerjaan' => 'datetime',
        'tanggal_mulai_kerjasama' => 'datetime',
        'tanggal_selesai_kerjasama' => 'datetime',
        'harga' => 'integer',
    ];

    // **RELASI INVOICE (mengganti layanan)**
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // **RELASI PENANGGUNG JAWAB**
    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    // Accessor untuk status pengerjaan yang sudah diformat
    public function getStatusPengerjaanFormattedAttribute()
    {
        $statusMap = [
            'pending' => 'Pending',
            'dalam_pengerjaan' => 'Dalam Pengerjaan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        return $statusMap[$this->status_pengerjaan] ?? ucfirst($this->status_pengerjaan);
    }

    // Accessor untuk status kerjasama yang sudah diformat
    public function getStatusKerjasamaFormattedAttribute()
    {
        $statusMap = [
            'aktif' => 'Aktif',
            'selesai' => 'Selesai',
            'ditangguhkan' => 'Ditangguhkan',
        ];

        return $statusMap[$this->status_kerjasama] ?? ucfirst($this->status_kerjasama);
    }

    // Scope untuk proyek dengan status pengerjaan tertentu
    public function scopeStatusPengerjaan($query, $status)
    {
        return $query->where('status_pengerjaan', $status);
    }

    // Scope untuk proyek dengan status kerjasama tertentu
    public function scopeStatusKerjasama($query, $status)
    {
        return $query->where('status_kerjasama', $status);
    }

    // Scope untuk proyek yang sedang berjalan
    public function scopeSedangBerjalan($query)
    {
        return $query->where('status_pengerjaan', 'dalam_pengerjaan')
                     ->where('status_kerjasama', 'aktif');
    }

    // Scope untuk proyek aktif (kerjasama aktif)
    public function scopeAktif($query)
    {
        return $query->where('status_kerjasama', 'aktif');
    }

    /**
     * Event ketika project dibuat
     */
    protected static function boot()
    {
        parent::boot();

        // Ketika project dibuat, ambil data dari invoice
        static::creating(function ($project) {
            if ($project->invoice_id && !$project->nama) {
                $invoice = Invoice::find($project->invoice_id);
                if ($invoice) {
                    $project->nama = $invoice->judul ?? 'Project dari Invoice #' . $invoice->id;
                    $project->deskripsi = $invoice->deskripsi ?? '';
                    $project->harga = $invoice->total ?? 0;
                    
                    // Ambil tanggal dari invoice jika ada
                    if ($invoice->tanggal_mulai && !$project->tanggal_mulai_kerjasama) {
                        $project->tanggal_mulai_kerjasama = $invoice->tanggal_mulai;
                    }
                    
                    if ($invoice->tanggal_selesai && !$project->tanggal_selesai_kerjasama) {
                        $project->tanggal_selesai_kerjasama = $invoice->tanggal_selesai;
                    }
                }
            }
            
            // Set tanggal mulai kerjasama ke tanggal sekarang jika tidak diisi
            if (!$project->tanggal_mulai_kerjasama) {
                $project->tanggal_mulai_kerjasama = now();
            }
            
            // Set default status
            if (!$project->status_kerjasama) {
                $project->status_kerjasama = 'aktif';
            }
            
            if (!$project->status_pengerjaan) {
                $project->status_pengerjaan = 'pending';
            }
            
            if (!$project->progres) {
                $project->progres = 0;
            }
        });
    }
}