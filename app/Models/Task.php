<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'target_manager_id',
        'target_type',
        'target_divisi',
        'is_broadcast',
        'catatan',
        'catatan_update',
        'assigned_at',
        'completed_at',
        'submission_file',
        'submission_notes',
        'submitted_at',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_broadcast' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROSES = 'proses';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    // Target type constants
    const TARGET_TYPE_KARYAWAN = 'karyawan';
    const TARGET_TYPE_DIVISI = 'divisi';
    const TARGET_TYPE_MANAGER = 'manager';

    // ========== RELASI ==========

    /**
     * Relationship dengan user yang ditugaskan (assignee)
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Alias untuk assignedUser - untuk backward compatibility
     */
    public function assignee(): BelongsTo
    {
        return $this->assignedUser();
    }

    /**
     * Relationship dengan user yang membuat tugas
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship dengan manager target (jika target_type = 'manager')
     */
    public function targetManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_manager_id');
    }

    /**
     * Relationship dengan manager yang menugaskan
     */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_manager');
    }

    /**
     * Relationship dengan komentar-komentar tugas
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    /**
     * Relationship dengan file-file tugas
     */
    public function files(): HasMany
    {
        return $this->hasMany(TaskFile::class)->orderBy('uploaded_at', 'desc');
    }

    // ========== SCOPES ==========

    /**
     * Scope untuk tugas yang ditugaskan ke user tertentu
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status === 'all' || empty($status)) {
            return $query;
        }
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan prioritas
     */
    public function scopeByPriority($query, $priority)
    {
        if ($priority === 'all' || empty($priority)) {
            return $query;
        }
        return $query->where('priority', $priority);
    }

    /**
     * Scope untuk tugas yang dibuat oleh user tertentu
     */
    public function scopeByCreator($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Scope untuk tugas berdasarkan divisi target
     */
    public function scopeByDivisi($query, $divisi)
    {
        if (empty($divisi)) {
            return $query;
        }
        return $query->where('target_divisi', $divisi);
    }

    /**
     * Scope untuk tugas yang sudah melewati deadline
     */
    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
                    ->whereNotIn('status', [self::STATUS_SELESAI, self::STATUS_DIBATALKAN]);
    }

    /**
     * Scope untuk tugas yang aktif (belum selesai/dibatalkan)
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_SELESAI, self::STATUS_DIBATALKAN]);
    }

    /**
     * Scope untuk tugas yang sudah ditugaskan
     */
    public function scopeAssigned($query)
    {
        return $query->whereNotNull('assigned_at');
    }

    /**
     * Scope untuk tugas broadcast
     */
    public function scopeBroadcast($query)
    {
        return $query->where('is_broadcast', true);
    }

    /**
     * Scope untuk tugas yang sudah disubmit
     */
    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    /**
     * Scope untuk tugas yang memiliki submission file
     */
    public function scopeWithSubmission($query)
    {
        return $query->whereNotNull('submission_file');
    }

    /**
     * Scope untuk mencari tugas berdasarkan kata kunci
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
              ->orWhere('submission_notes', 'like', "%{$search}%")
              ->orWhere('catatan', 'like', "%{$search}%")
              ->orWhere('catatan_update', 'like', "%{$search}%");
        });
    }

    // ========== ATTRIBUTES ==========

    /**
     * Cek apakah tugas sudah melewati deadline
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->deadline && 
               now()->gt($this->deadline) && 
               !in_array($this->status, [self::STATUS_SELESAI, self::STATUS_DIBATALKAN]);
    }

    /**
     * Label status dalam bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROSES => 'Proses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
        ];
        
        return $labels[$this->status] ?? 'Unknown';
    }

    /**
     * Label prioritas dalam bahasa Indonesia
     */
    public function getPriorityLabelAttribute(): string
    {
        $labels = [
            self::PRIORITY_LOW => 'Rendah',
            self::PRIORITY_MEDIUM => 'Sedang',
            self::PRIORITY_HIGH => 'Tinggi',
        ];
        
        return $labels[$this->priority] ?? 'Sedang';
    }

    /**
     * Deadline yang diformat
     */
    public function getFormattedDeadlineAttribute(): string
    {
        return $this->deadline ? $this->deadline->translatedFormat('d M Y H:i') : '-';
    }

    /**
     * Waktu submit yang diformat
     */
    public function getFormattedSubmittedAtAttribute(): string
    {
        return $this->submitted_at ? $this->submitted_at->translatedFormat('d M Y H:i') : '-';
    }

    /**
     * Waktu pembuatan yang diformat
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at ? $this->created_at->translatedFormat('d M Y H:i') : '-';
    }

    /**
     * Waktu assignment yang diformat
     */
    public function getFormattedAssignedAtAttribute(): string
    {
        return $this->assigned_at ? $this->assigned_at->translatedFormat('d M Y H:i') : '-';
    }

    /**
     * Waktu selesai yang diformat
     */
    public function getFormattedCompletedAtAttribute(): string
    {
        return $this->completed_at ? $this->completed_at->translatedFormat('d M Y H:i') : '-';
    }

    /**
     * Sisa waktu hingga deadline
     */
    public function getTimeRemainingAttribute(): ?string
    {
        if (!$this->deadline) {
            return null;
        }
        
        $now = Carbon::now();
        if ($this->deadline->gt($now)) {
            return 'Sisa ' . $now->diffForHumans($this->deadline, true);
        }
        
        return 'Terlambat ' . $this->deadline->diffForHumans($now, true);
    }

    /**
     * Jumlah hari hingga deadline (bisa negatif jika terlambat)
     */
    public function getDaysUntilDeadlineAttribute(): ?int
    {
        if (!$this->deadline) {
            return null;
        }
        
        return now()->diffInDays($this->deadline, false);
    }

    /**
     * Jumlah komentar
     */
    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    /**
     * Jumlah file
     */
    public function getFilesCountAttribute(): int
    {
        return $this->files()->count();
    }

    /**
     * Nama assignee berdasarkan tipe target
     */
    public function getAssigneeNameAttribute(): string
    {
        if ($this->target_type === self::TARGET_TYPE_KARYAWAN && $this->assignedUser) {
            return $this->assignedUser->name;
        } elseif ($this->target_type === self::TARGET_TYPE_DIVISI) {
            return 'Divisi ' . ($this->target_divisi ?? '-');
        } elseif ($this->target_type === self::TARGET_TYPE_MANAGER && $this->targetManager) {
            return 'Manager: ' . $this->targetManager->name;
        }
        
        return '-';
    }

    /**
     * Nama pemberi tugas
     */
    public function getAssignerNameAttribute(): string
    {
        if ($this->assigned_by_manager && $this->assigner) {
            return $this->assigner->name;
        } elseif ($this->creator) {
            return $this->creator->name;
        }
        
        return 'Admin';
    }

    /**
     * URL untuk download submission file
     */
    public function getSubmissionUrlAttribute(): ?string
    {
        if (!$this->submission_file) {
            return null;
        }
        
        try {
            return Storage::url($this->submission_file);
        } catch (\Exception $e) {
            Log::error('Error generating submission URL', [
                'task_id' => $this->id,
                'file_path' => $this->submission_file,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Cek apakah sudah ada submission file
     */
    public function getHasSubmissionAttribute(): bool
    {
        return !empty($this->submission_file);
    }

    /**
     * Warna badge untuk status
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_PROSES => 'primary',
            self::STATUS_SELESAI => 'success',
            self::STATUS_DIBATALKAN => 'danger',
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Warna badge untuk prioritas
     */
    public function getPriorityColorAttribute(): string
    {
        $colors = [
            self::PRIORITY_LOW => 'success',
            self::PRIORITY_MEDIUM => 'warning',
            self::PRIORITY_HIGH => 'danger',
        ];
        
        return $colors[$this->priority] ?? 'secondary';
    }

    /**
     * Persentase progres tugas (simulasi)
     */
    public function getProgressPercentageAttribute(): int
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 0;
            case self::STATUS_PROSES:
                return 50;
            case self::STATUS_SELESAI:
                return 100;
            case self::STATUS_DIBATALKAN:
                return 0;
            default:
                return 0;
        }
    }

    /**
     * Cek apakah tugas bisa diedit oleh user tertentu
     */
    public function canEdit($userId): bool
    {
        return $this->created_by == $userId || 
               $this->assigned_by_manager == $userId || 
               $this->assigned_to == $userId;
    }

    // ========== METHODS ==========

    /**
     * Menugaskan tugas ke user tertentu
     */
    public function assignToUser($userId, $managerId = null): bool
    {
        $this->assigned_to = $userId;
        $this->assigned_at = now();
        $this->status = self::STATUS_PENDING;
        
        if ($managerId) {
            $this->assigned_by_manager = $managerId;
        }
        
        $this->is_broadcast = false;
        
        return $this->save();
    }

    /**
     * Update status tugas
     */
    public function updateStatus($status): bool
    {
        $oldStatus = $this->status;
        $this->status = $status;
        
        if ($status === self::STATUS_SELESAI && !$this->completed_at) {
            $this->completed_at = now();
        }
        
        if (in_array($status, [self::STATUS_DIBATALKAN, self::STATUS_PENDING]) && $this->completed_at) {
            $this->completed_at = null;
        }
        
        $saved = $this->save();
        
        if ($saved) {
            Log::info('Task status updated', [
                'task_id' => $this->id,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'user_id' => auth()->id(),
            ]);
        }
        
        return $saved;
    }

    /**
     * Submit tugas dengan file
     */
    public function submitWithFile($filePath, $notes = null): self
    {
        $this->update([
            'status' => self::STATUS_SELESAI,
            'submission_file' => $filePath,
            'submission_notes' => $notes,
            'submitted_at' => now(),
            'completed_at' => now(),
        ]);
        
        // Tambahkan komentar otomatis
        $this->addComment(
            "✅ **Telah mengirimkan file hasil tugas**\n" .
            "📄 **File:** " . basename($filePath) . 
            ($notes ? "\n📝 **Catatan:** " . $notes : '') .
            "\n⏰ **Waktu:** " . now()->translatedFormat('d F Y H:i'),
            auth()->id() ?? $this->assigned_to
        );
        
        return $this;
    }

    /**
     * Submit tugas tanpa file
     */
    public function submitWithoutFile($notes = null): self
    {
        $this->update([
            'status' => self::STATUS_SELESAI,
            'submission_notes' => $notes,
            'submitted_at' => now(),
            'completed_at' => now(),
        ]);
        
        // Tambahkan komentar otomatis
        $this->addComment(
            "✅ **Telah menyelesaikan tugas**" . 
            ($notes ? "\n📝 **Catatan:** " . $notes : '') .
            "\n⏰ **Waktu:** " . now()->translatedFormat('d F Y H:i'),
            auth()->id() ?? $this->assigned_to
        );
        
        return $this;
    }

    /**
     * Menandai tugas sebagai selesai dengan file
     */
    public function markAsCompletedWithFile($filePath, $notes = null): self
    {
        return $this->submitWithFile($filePath, $notes);
    }

    /**
     * Menambahkan komentar pada tugas
     */
    public function addComment($content, $userId)
    {
        return $this->comments()->create([
            'user_id' => $userId,
            'content' => $content,
        ]);
    }

    /**
     * Menambahkan file ke tugas
     */
    public function attachFile($file, $userId, $originalName = null)
    {
        $path = $file->store("tasks/{$this->id}/files", 'public');
        
        return TaskFile::create([
            'task_id' => $this->id,
            'user_id' => $userId,
            'filename' => basename($path),
            'original_name' => $originalName ?? $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_at' => now(),
        ]);
    }

    /**
     * Update catatan tugas
     */
    public function updateCatatan($catatan, $isUpdate = false): bool
    {
        if ($isUpdate) {
            $current = $this->catatan_update ?: '';
            $this->catatan_update = $current . "\n[" . now()->format('Y-m-d H:i:s') . "] " . $catatan;
        } else {
            $this->catatan = $catatan;
        }
        
        return $this->save();
    }

    /**
     * Menghapus submission file
     */
    public function removeSubmissionFile(): bool
    {
        if ($this->submission_file) {
            try {
                Storage::delete($this->submission_file);
            } catch (\Exception $e) {
                Log::error('Error deleting submission file', [
                    'task_id' => $this->id,
                    'file_path' => $this->submission_file,
                    'error' => $e->getMessage()
                ]);
            }
            
            $this->submission_file = null;
            $this->submission_notes = null;
            $this->submitted_at = null;
            
            return $this->save();
        }
        
        return false;
    }

    /**
     * ========== TAMBAHAN METHOD UNTUK HIERARKI ==========
     */
    
    /**
     * Cek apakah tugas ini adalah tugas broadcast dari GM ke Divisi
     */
    public function isGmToDivisionTask(): bool
    {
        return $this->target_type === 'divisi' && 
               $this->created_by !== $this->assigned_to;
    }

    /**
     * Cek apakah tugas ini adalah tugas dari Manager ke Karyawan
     */
    public function isManagerToEmployeeTask(): bool
    {
        return $this->target_type === 'karyawan' && 
               $this->assigned_by_manager !== $this->assigned_to;
    }

    /**
     * Dapatkan tugas parent (untuk karyawan melihat asal tugas)
     */
    public function getParentTask()
    {
        if ($this->target_type === 'karyawan' && $this->assigned_by_manager) {
            // Cari tugas yang dibuat oleh manager untuk karyawan/divisi ini
            return Task::where('created_by', $this->assigned_by_manager)
                      ->where(function($q) {
                          $q->where('assigned_to', $this->assigned_to)
                            ->orWhere('target_divisi', $this->assignedUser->divisi ?? null);
                      })
                      ->where('id', '!=', $this->id)
                      ->first();
        }
        return null;
    }

    /**
     * Dapatkan subtasks (untuk manager melihat tugas turunan)
     */
    public function getChildTasks()
    {
        if ($this->target_type === 'divisi' || ($this->target_type === 'manager' && $this->assigned_to)) {
            return Task::where('assigned_by_manager', $this->created_by)
                      ->where('target_divisi', $this->target_divisi)
                      ->where('target_type', 'karyawan')
                      ->get();
        }
        return collect();
    }

    /**
     * Cek apakah tugas ini bisa diassign ke karyawan oleh manager
     */
    public function canBeAssignedToEmployee($employeeId): bool
    {
        if ($this->target_type !== 'divisi' || !$this->is_broadcast) {
            return false;
        }
        
        $employee = User::find($employeeId);
        if (!$employee || $employee->divisi !== $this->target_divisi) {
            return false;
        }
        
        return true;
    }

    /**
     * Cek apakah tugas ini untuk divisi tertentu
     */
    public function isForDivision($divisionName): bool
    {
        return $this->target_divisi === $divisionName;
    }

    /**
     * Cek apakah user dapat mengakses tugas ini
     */
    public function canBeAccessedBy($userId, $userRole, $userDivision = null): bool
    {
        if ($userRole === 'general_manager') {
            return true; // GM bisa akses semua
        }
        
        if ($userRole === 'manager_divisi') {
            // Manager bisa akses tugas di divisinya
            return $this->target_divisi === $userDivision || 
                   $this->created_by === $userId ||
                   $this->assigned_to === $userId ||
                   $this->target_manager_id === $userId;
        }
        
        if ($userRole === 'karyawan') {
            // Karyawan hanya bisa akses tugas yang ditugaskan ke mereka
            return $this->assigned_to === $userId;
        }
        
        return false;
    }

    // ========== STATIC METHODS ==========

    /**
     * Opsi status yang tersedia
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROSES => 'Proses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
        ];
    }

    /**
     * Opsi prioritas yang tersedia
     */
    public static function getPriorityOptions(): array
    {
        return [
            self::PRIORITY_LOW => 'Rendah',
            self::PRIORITY_MEDIUM => 'Sedang',
            self::PRIORITY_HIGH => 'Tinggi',
        ];
    }

    /**
     * Opsi tipe target yang tersedia
     */
    public static function getTargetTypeOptions(): array
    {
        return [
            self::TARGET_TYPE_KARYAWAN => 'Karyawan',
            self::TARGET_TYPE_DIVISI => 'Divisi',
            self::TARGET_TYPE_MANAGER => 'Manager',
        ];
    }

    /**
     * Statistik tugas untuk user tertentu
     */
    public static function getStatisticsForUser($userId): array
    {
        return [
            'total' => self::assignedTo($userId)->count(),
            'pending' => self::assignedTo($userId)->where('status', self::STATUS_PENDING)->count(),
            'proses' => self::assignedTo($userId)->where('status', self::STATUS_PROSES)->count(),
            'selesai' => self::assignedTo($userId)->where('status', self::STATUS_SELESAI)->count(),
            'dibatalkan' => self::assignedTo($userId)->where('status', self::STATUS_DIBATALKAN)->count(),
            'overdue' => self::assignedTo($userId)
                ->where('deadline', '<', now())
                ->whereNotIn('status', [self::STATUS_SELESAI, self::STATUS_DIBATALKAN])
                ->count(),
            'with_submission' => self::assignedTo($userId)->whereNotNull('submission_file')->count(),
        ];
    }

    /**
     * Mendapatkan tugas untuk karyawan dengan filter
     */
    public static function getTasksForKaryawan($userId, $filters = [])
    {
        $query = self::with(['creator', 'assigner', 'comments.user', 'files.uploader'])
                    ->where('assigned_to', $userId)
                    ->orderBy('deadline', 'asc')
                    ->orderBy('created_at', 'desc');

        // Apply filters
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority']) && $filters['priority'] !== 'all') {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('submission_notes', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        return isset($filters['paginate']) && $filters['paginate'] 
            ? $query->paginate($filters['per_page'] ?? 15)
            : $query->get();
    }

    /**
     * Mendapatkan statistik global
     */
    public static function getGlobalStatistics(): array
    {
        return [
            'total_tasks' => self::count(),
            'active_tasks' => self::active()->count(),
            'overdue_tasks' => self::overdue()->count(),
            'completed_tasks' => self::where('status', self::STATUS_SELESAI)->count(),
            'pending_tasks' => self::where('status', self::STATUS_PENDING)->count(),
            'tasks_with_submission' => self::withSubmission()->count(),
            'broadcast_tasks' => self::broadcast()->count(),
        ];
    }

    // ========== BOOT METHOD ==========

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (empty($task->priority)) {
                $task->priority = self::PRIORITY_MEDIUM;
            }
            
            if (empty($task->status)) {
                $task->status = self::STATUS_PENDING;
            }
            
            if (empty($task->created_by) && auth()->check()) {
                $task->created_by = auth()->id();
            }
        });

        static::created(function ($task) {
            Log::info('Task created', [
                'id' => $task->id,
                'judul' => $task->judul,
                'assigned_to' => $task->assigned_to,
                'created_by' => $task->created_by,
                'status' => $task->status,
                'priority' => $task->priority,
            ]);
        });

        static::updated(function ($task) {
            $changes = [];
            
            if ($task->isDirty('status')) {
                $changes['status'] = [
                    'from' => $task->getOriginal('status'),
                    'to' => $task->status,
                ];
            }
            
            if ($task->isDirty('submission_file')) {
                $changes['submission'] = [
                    'old_file' => $task->getOriginal('submission_file'),
                    'new_file' => $task->submission_file,
                ];
            }
            
            if ($task->isDirty('assigned_to')) {
                $changes['assignment'] = [
                    'from' => $task->getOriginal('assigned_to'),
                    'to' => $task->assigned_to,
                ];
            }
            
            if (!empty($changes)) {
                Log::info('Task updated', [
                    'task_id' => $task->id,
                    'changes' => $changes,
                    'updated_by' => auth()->id() ?? 'system',
                    'updated_at' => now(),
                ]);
            }
        });

        static::deleted(function ($task) {
            if ($task->isForceDeleting()) {
                // Hapus file terkait jika soft delete
                try {
                    Storage::deleteDirectory("tasks/{$task->id}");
                } catch (\Exception $e) {
                    Log::error('Error deleting task files', [
                        'task_id' => $task->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            Log::info('Task deleted', [
                'id' => $task->id,
                'judul' => $task->judul,
                'deleted_by' => auth()->id() ?? 'system',
                'permanent' => $task->isForceDeleting(),
            ]);
        });
    }

    // ========== APPENDS ==========
    
    /**
     * Attributes yang akan ditambahkan ke array/JSON
     */
    protected $appends = [
        'is_overdue',
        'status_label',
        'priority_label',
        'formatted_deadline',
        'formatted_submitted_at',
        'formatted_created_at',
        'time_remaining',
        'assignee_name',
        'assigner_name',
        'submission_url',
        'has_submission',
        'status_color',
        'priority_color',
        'progress_percentage',
    ];
}