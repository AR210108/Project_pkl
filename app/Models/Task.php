<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'full_description',
        'status',
        'priority',
        'category',
        'deadline',
        'file_path',
        'file_notes',
        'completed_at',
        'user_id',
        'assigned_by',
        'assigner_name',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scope untuk filter umum
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessor: format deadline Indonesia
    public function getFormattedDeadlineAttribute()
    {
        return Carbon::parse($this->deadline)->translatedFormat('d F Y H:i');
    }
}