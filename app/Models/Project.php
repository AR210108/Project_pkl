<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes; // Aktif karena migrasi Anda memiliki $table->softDeletes()

    /**
     * Nama tabel yang terkait dengan model.
     * Default Laravel adalah 'projects', tapi kita pakai 'project' sesuai request.
     */
    protected $table = 'project';

    /**
     * Kolom yang bisa diisi secara mass-assignment.
     */
    protected $fillable = [
        'layanan_id',
        'divisi_id',          // Kolom baru dari revisi migrasi
        'penanggung_jawab_id',
        'created_by',         // User yang membuat project
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
        'status',
    ];

    /**
     * Type Casting (Otomatis ubah tipe data)
     */
    protected $casts = [
        'deadline' => 'datetime',
        'harga' => 'decimal:2', // Penting: Decimal agar uang presisi (contoh: 100000.50)
        'progres' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // =========================================================================
    // RELASI (RELATIONSHIPS)
    // =========================================================================

    /**
     * Relasi ke Layanan
     */
    public function layanan()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Relasi ke Penanggung Jawab (User)
     */
    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id')
                    ->select(['id', 'name', 'email', 'divisi_id']);
    }

    /**
     * Relasi ke Divisi
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    /**
     * Relasi ke User yang membuat Project (Creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke Tasks (Tugas-tugas di dalam project ini)
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    // =========================================================================
    // MUTATORS & ACCESSORS
    // =========================================================================

    /**
     * Mutator: Menormalisasi input status sebelum simpan ke DB
     */
    public function setStatusAttribute($value)
    {
        // Logika: Ubah input jadi lowercase dulu, lalu map ke format standard
        $lowerValue = strtolower(trim($value));
        
        $statusMap = [
            'pending' => 'Pending',
            'proses'  => 'Proses',
            'selesai' => 'Selesai',
            'process' => 'Proses', 
            'done'    => 'Selesai',
        ];

        // Simpan status yang sudah diformat
        $this->attributes['status'] = $statusMap[$lowerValue] ?? ucfirst($lowerValue);
    }
    
    /**
     * Event ketika project dibuat (Auto-populate dari Layanan)
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            // Jika ada layanan_id tapi nama project kosong, ambil dari data layanan
            if ($project->layanan_id && empty($project->nama)) {
                $layanan = Layanan::find($project->layanan_id);
                if ($layanan) {
                    // Sesuaikan field ini dengan tabel 'layanans' Anda
                    $project->nama = $layanan->nama_layanan ?? $layanan->nama ?? 'Project Baru';
                    $project->deskripsi = $layanan->deskripsi ?? null;
                    $project->harga = $layanan->harga ?? 0;
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

    /**
     * Accessor: Mengambil status yang sudah diformat
     */
    public function getStatusFormattedAttribute()
    {
        $rawStatus = $this->attributes['status'] ?? null;

        if (!$rawStatus) return '-';

        $lowerStatus = strtolower($rawStatus);
        
        $statusMap = [
            'pending' => 'Pending',
            'proses'  => 'Dalam Proses',
            'selesai' => 'Selesai',
        ];

        return $statusMap[$lowerStatus] ?? ucfirst($rawStatus);
    }

    /**
     * Accessor: Cek apakah project overdue
     */
    public function getIsOverdueAttribute()
    {
        // Cek jika ada deadline, tanggalnya sudah lewat, dan status bukan Selesai
        return $this->deadline && 
               $this->deadline->isPast() && 
               $this->status !== 'Selesai' && 
               $this->status !== 'Dibatalkan';
    }

    // =========================================================================
    // SCOPES (Query Helpers)
    // =========================================================================

    /**
     * Scope: Filter project aktif (bukan Selesai/Dibatalkan)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Pending', 'Proses']);
    }

    /**
     * Scope: Filter project berdasarkan divisi
     */
    public function scopeByDivisi($query, $divisiId)
    {
        return $query->where('divisi_id', $divisiId);
    }

    /**
     * Scope: Filter project yang menjadi tanggung jawab user tertentu
     */
    public function scopeByPenanggungJawab($query, $userId)
    {
        return $query->where('penanggung_jawab_id', $userId);
    }
}