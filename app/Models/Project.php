<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Opsional: jika pakai soft deletes

class Project extends Model
{
    use HasFactory;
    // use SoftDeletes; // Uncomment jika tabel ada kolom deleted_at

    /**
     * Nama tabel yang terkait dengan model.
     * Sesuaikan dengan nama tabel di database Anda.
     * - Konvensi Laravel: 'projects' (Plural)
     * - Jika tabel di DB Anda 'project', biarkan $table ini aktif.
     */
    protected $table = 'project';

    /**
     * Primary key (opsional, jika id bukan default)
     */
    // protected $primaryKey = 'id_project';

    /**
     * Kolom yang bisa diisi secara mass-assignment.
     */
    protected $fillable = [
        'layanan_id',
        'nama', // Sesuaikan dengan DB: 'nama' atau 'name'
        'deskripsi', // Sesuaikan dengan DB: 'deskripsi' atau 'description'
        'harga',
        'deadline',
        'progres',
        'status',
        'penanggung_jawab_id',
        'manager_id', // Tambahan: ID manager yang membuat/menangani
    ];

    /**
     * Type Casting (Otomatis ubah tipe data)
     */
    protected $casts = [
        'progres' => 'integer', // Pastikan di DB 0-100
        'status' => 'string',
        'deadline' => 'datetime',
        'harga' => 'integer', // Atau 'decimal' jika perlu presisi uang
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =========================================================================
    // RELASI (RELATIONSHIPS)
    // =========================================================================

    /**
     * Relasi ke Layanan (Service/Category)
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    /**
     * Relasi ke Penanggung Jawab (User)
     * User yang bertanggung jawab langsung atas project ini.
     */
    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id')
                    ->select(['id', 'name', 'email', 'avatar']); // Select spesifik utk performa
    }

    /**
     * Relasi ke Manager/User Creator (Opsional)
     * User yang membuat project ini di database.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
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
     * Contoh: input 'pending' -> disimpan 'Pending' (atau format lain yg diinginkan)
     */
    public function setStatusAttribute($value)
    {
        // Logika: Ubah input jadi lowercase dulu, lalu map ke format standard
        $lowerValue = strtolower(trim($value));
        
        $statusMap = [
            'pending' => 'Pending',
            'proses'  => 'Proses',
            'selesai' => 'Selesai',
            'process' => 'Proses', // Alternatif bahasa inggris
            'done'    => 'Selesai',
        ];

        // Jika ada di map, pakai map-nya. Jika tidak, pakai input asli (Capitalize first letter)
        $this->attributes['status'] = $statusMap[$lowerValue] ?? ucfirst($lowerValue);
    }

    /**
     * Accessor: Mengambil status yang sudah diformat dengan aman
     * Dipanggil via: $project->status_formatted
     */
    public function getStatusFormattedAttribute()
    {
        // Ambil nilai status mentah dari atribut DB
        $rawStatus = $this->attributes['status'] ?? null;

        // Jika null, kembalikan string kosong atau default
        if (!$rawStatus) {
            return '-';
        }

        $lowerStatus = strtolower($rawStatus);
        
        // Map status agar tampilan konsisten di frontend
        $statusMap = [
            'pending' => 'Pending',
            'proses'  => 'Dalam Proses', // Tampilan lebih panjang
            'selesai' => 'Selesai',
        ];

        return $statusMap[$lowerStatus] ?? ucfirst($rawStatus);
    }

    /**
     * Accessor Helper: Cek apakah project overdue (Lewat deadline)
     */
    public function getIsOverdueAttribute()
    {
        return $this->deadline && $this->deadline->isPast() && $this->status !== 'Selesai';
    }

    // =========================================================================
    // SCOPES (Query Helpers)
    // =========================================================================

    /**
     * Scope: Filter project milik manager tertentu
     * Penggunaan: Project::managedBy($userId)->get();
     */
    public function scopeManagedBy($query, $userId)
    {
        return $query->where('manager_id', $userId);
    }

    /**
     * Scope: Filter project aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'Selesai');
    }
}