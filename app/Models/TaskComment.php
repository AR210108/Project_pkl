<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'user_id',
        'content',
    ];

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->translatedFormat('d M Y H:i');
    }

    public function getShortContentAttribute()
    {
        return Str::limit($this->content, 100);
    }

    // Appends
    protected $appends = [
        'formatted_created_at',
        'short_content',
    ];
}