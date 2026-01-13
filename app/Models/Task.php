<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'judul',
        'deskripsi',
        'deadline',
        'status',
        'assigned_to',
        'created_by',
        'assigned_by_manager',
        'target_manager_id',
        'target_type',
        'target_divisi',
        'is_broadcast',
        'catatan',
        'catatan_update',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_broadcast' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'deadline',
        'assigned_at',
        'completed_at',
        'deleted_at',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROSES = 'proses';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';

    // Target type constants
    const TARGET_TYPE_KARYAWAN = 'karyawan';
    const TARGET_TYPE_DIVISI = 'divisi';
    const TARGET_TYPE_MANAGER = 'manager';

    // Relasi ke user yang ditugaskan
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Relasi ke user yang membuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke manager target
    public function targetManager()
    {
        return $this->belongsTo(User::class, 'target_manager_id');
    }

    // Relasi ke manager yang menugaskan
    public function assignedByManager()
    {
        return $this->belongsTo(User::class, 'assigned_by_manager');
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk filter berdasarkan pembuat
    public function scopeByCreator($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    // Scope untuk filter berdasarkan divisi
    public function scopeByDivisi($query, $divisi)
    {
        return $query->where('target_divisi', $divisi);
    }

    // Scope untuk tugas yang overdue
    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
                    ->whereNotIn('status', [self::STATUS_SELESAI, self::STATUS_DIBATALKAN]);
    }

    // Scope untuk tugas aktif (bukan selesai atau dibatalkan)
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_SELESAI, self::STATUS_DIBATALKAN]);
    }

    // Scope untuk tugas yang sudah ditugaskan
    public function scopeAssigned($query)
    {
        return $query->whereNotNull('assigned_at');
    }

    // Scope untuk tugas broadcast
    public function scopeBroadcast($query)
    {
        return $query->where('is_broadcast', true);
    }

    // Check apakah task overdue
    public function getIsOverdueAttribute()
    {
        return $this->deadline && 
               now()->gt($this->deadline) && 
               !in_array($this->status, [self::STATUS_SELESAI, self::STATUS_DIBATALKAN]);
    }

    // Get status label dengan warna
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROSES => 'Dalam Proses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
        ];

        return $statusLabels[$this->status] ?? 'Unknown';
    }

    // Get status color untuk UI
    public function getStatusColorAttribute()
    {
        $statusColors = [
            self::STATUS_PENDING => 'blue',
            self::STATUS_PROSES => 'yellow',
            self::STATUS_SELESAI => 'green',
            self::STATUS_DIBATALKAN => 'red',
        ];

        return $statusColors[$this->status] ?? 'gray';
    }

    // Get deadline format yang readable
    public function getFormattedDeadlineAttribute()
    {
        return $this->deadline ? $this->deadline->format('d M Y H:i') : '-';
    }

    // Get sisa waktu deadline
    public function getTimeRemainingAttribute()
    {
        if (!$this->deadline) return null;
        
        $now = Carbon::now();
        if ($this->deadline->gt($now)) {
            return $now->diffForHumans($this->deadline, true);
        }
        
        return 'Terlambat ' . $this->deadline->diffForHumans($now, true);
    }

    // Assign task ke user
    public function assignToUser($userId, $managerId = null)
    {
        $this->assigned_to = $userId;
        $this->assigned_at = now();
        if ($managerId) {
            $this->assigned_by_manager = $managerId;
        }
        $this->is_broadcast = false;
        return $this->save();
    }

    // Mark task sebagai selesai
    public function markAsCompleted()
    {
        $this->status = self::STATUS_SELESAI;
        $this->completed_at = now();
        return $this->save();
    }

    // Mark task sebagai dibatalkan
    public function markAsCancelled()
    {
        $this->status = self::STATUS_DIBATALKAN;
        return $this->save();
    }

    // Get all available status options
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROSES => 'Dalam Proses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
        ];
    }

    // Get all target type options
    public static function getTargetTypeOptions()
    {
        return [
            self::TARGET_TYPE_KARYAWAN => 'Karyawan',
            self::TARGET_TYPE_DIVISI => 'Divisi',
            self::TARGET_TYPE_MANAGER => 'Manager',
        ];
    }

    // Boot method untuk model events
    protected static function boot()
    {
        parent::boot();

        // Event ketika task dibuat
        static::created(function ($task) {
            // Log atau notifikasi bisa ditambahkan di sini
        });

        // Event ketika task diupdate
        static::updated(function ($task) {
            // Log atau notifikasi bisa ditambahkan di sini
            // Contoh: kirim notifikasi jika status berubah
        });
    }
}