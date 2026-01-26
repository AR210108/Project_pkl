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
        'user_id', // ✅ Pastikan ini ada
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

    // ✅ TAMBAHKAN: Default values
    protected $attributes = [
        'user_id' => 1, // Default ke admin jika tidak diisi
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
            return route('api.tasks.files.download', ['file' => $this->id]);
        }
        return null;
    }

    // ✅ TAMBAHKAN: Boot method untuk handle user_id
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($taskFile) {
            // Jika user_id tidak diisi, gunakan authenticated user atau default
            if (empty($taskFile->user_id)) {
                $taskFile->user_id = auth()->id() ?? 1;
            }
            
            // Pastikan uploaded_at diisi
            if (empty($taskFile->uploaded_at)) {
                $taskFile->uploaded_at = now();
            }
        });
    }
}