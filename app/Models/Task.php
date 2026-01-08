<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'prioritas',
        'deadline',
        'assigned_to',
        'created_by',
        'status',
        'target_type',
        'target_divisi',
        'target_manager_id',
        'kategori',
        'catatan',
        'catatan_update',
        'is_broadcast',
        'assigned_by_manager',
        'assigned_at',
        'completed_at'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    // Relationships
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function targetManager()
    {
        return $this->belongsTo(User::class, 'target_manager_id');
    }

    public function targetDivisiModel()
    {
        return $this->belongsTo(Divisi::class, 'target_divisi');
    }

    public function assignedByManager()
    {
        return $this->belongsTo(User::class, 'assigned_by_manager');
    }

    // Scopes
    public function scopeForDivisi($query, $divisi)
    {
        return $query->where('target_divisi', $divisi)
                    ->orWhereHas('assignedUser', function($q) use ($divisi) {
                        $q->where('divisi', $divisi);
                    });
    }

    public function scopeForManager($query, $managerId)
    {
        return $query->where('target_manager_id', $managerId)
                    ->orWhere('created_by', $managerId);
    }
}