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
        'priority',
        'assigned_to',
        'created_by',
        'assigned_by_manager',
        'target_type',
        'target_divisi',
        'is_broadcast',
        'catatan',
        'catatan_update',
        'submission_file',
        'submission_notes',
        'submitted_at',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_broadcast' => 'boolean',
    ];

    // Relationships
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by_manager');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    // Accessors
    public function getIsOverdueAttribute()
    {
        return $this->deadline && 
               now()->gt($this->deadline) && 
               !in_array($this->status, ['selesai', 'dibatalkan']);
    }

    public function getFormattedDeadlineAttribute()
    {
        return $this->deadline ? $this->deadline->translatedFormat('d M Y H:i') : '-';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'proses' => 'Dalam Proses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];
        
        return $labels[$this->status] ?? 'Unknown';
    }

    public function getPriorityLabelAttribute()
    {
        $labels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
        ];
        
        return $labels[$this->priority] ?? 'Sedang';
    }

    public function getAssigneeNameAttribute()
    {
        if ($this->target_type === 'karyawan' && $this->assignee) {
            return $this->assignee->name;
        } elseif ($this->target_type === 'divisi') {
            return 'Divisi ' . ($this->target_divisi ?? '-');
        } elseif ($this->target_type === 'manager' && $this->assigner) {
            return 'Manager: ' . $this->assigner->name;
        }
        
        return '-';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'proses' => 'primary',
            'selesai' => 'success',
            'dibatalkan' => 'danger',
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
        ];
        
        return $colors[$this->priority] ?? 'secondary';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProses($query)
    {
        return $query->where('status', 'proses');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
                    ->whereNotIn('status', ['selesai', 'dibatalkan']);
    }

    public function scopeForDivisi($query, $divisi)
    {
        return $query->where('target_divisi', $divisi);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%");
        });
    }

    // Methods
    public function canEdit($userId)
    {
        return $this->created_by == $userId || 
               $this->assigned_by_manager == $userId ||
               $this->assigned_to == $userId;
    }

    public function submit($filePath = null, $notes = null)
    {
        $this->update([
            'status' => 'selesai',
            'submission_file' => $filePath,
            'submission_notes' => $notes,
            'submitted_at' => now(),
            'completed_at' => now(),
        ]);
        
        return $this;
    }

    protected $appends = [
        'is_overdue',
        'status_label',
        'priority_label',
        'formatted_deadline',
        'assignee_name',
        'status_color',
        'priority_color',
    ];
}