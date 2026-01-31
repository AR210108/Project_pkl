<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TaskFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'filename',
        'original_name',
        'path',
        'size',
        'mime_type',
        'description',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'size' => 'integer',
    ];

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessors
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        if ($bytes == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }

    public function getFileIconAttribute()
    {
        $mime = $this->mime_type ?? '';
        
        if (str_contains($mime, 'image')) {
            return 'image';
        } elseif (str_contains($mime, 'pdf')) {
            return 'picture_as_pdf';
        } elseif (str_contains($mime, 'word') || str_contains($mime, 'document')) {
            return 'description';
        } elseif (str_contains($mime, 'excel') || str_contains($mime, 'sheet')) {
            return 'table_chart';
        } elseif (str_contains($mime, 'zip') || str_contains($mime, 'rar')) {
            return 'folder_zip';
        } else {
            return 'insert_drive_file';
        }
    }

    public function getIsImageAttribute()
    {
        return str_contains($this->mime_type ?? '', 'image');
    }

    public function getIsDocumentAttribute()
    {
        $docMimes = ['pdf', 'word', 'document', 'excel', 'sheet', 'text'];
        $mime = $this->mime_type ?? '';
        
        foreach ($docMimes as $docMime) {
            if (str_contains($mime, $docMime)) {
                return true;
            }
        }
        return false;
    }

    public function getPreviewUrlAttribute()
    {
        if ($this->is_image && $this->path) {
            return Storage::url($this->path);
        }
        return null;
    }

    public function getDownloadUrlAttribute()
    {
        if ($this->path) {
            return Storage::url($this->path);
        }
        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($taskFile) {
            if (empty($taskFile->user_id)) {
                $taskFile->user_id = auth()->id() ?? 1;
            }
            
            if (empty($taskFile->uploaded_at)) {
                $taskFile->uploaded_at = now();
            }
        });
    }

    // Appends
    protected $appends = [
        'formatted_size',
        'file_icon',
        'is_image',
        'is_document',
        'preview_url',
        'download_url',
    ];
}