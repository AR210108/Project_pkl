<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tasks';

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
        'target_divisi_id', // HANYA target_divisi_id
        'target_manager_id',
        'is_broadcast',
        'catatan',
        'catatan_update',
        'submission_file',
        'submission_notes',
        'submitted_at',
        'assigned_at',
        'completed_at',
        'progress_percentage',
        'kategori',
        'parent_task_id',
        'project_id',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_broadcast' => 'boolean',
        'progress_percentage' => 'integer',
    ];

    protected $attributes = [
        'status' => 'pending',
        'priority' => 'medium',
        'progress_percentage' => 0,
        'is_broadcast' => false,
    ];

    // ========== RELATIONSHIPS ==========
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to')
                    ->withDefault([
                        'name' => 'Tidak Ditugaskan',
                        'email' => '-'
                    ]);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')
                    ->withDefault([
                        'name' => 'Tidak Diketahui',
                        'email' => '-'
                    ]);
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by_manager')
                    ->withDefault([
                        'name' => 'Tidak Diketahui',
                        'email' => '-'
                    ]);
    }

    public function targetManager()
    {
        return $this->belongsTo(User::class, 'target_manager_id')
                    ->withDefault([
                        'name' => 'Tidak Diketahui',
                        'email' => '-'
                    ]);
    }

    public function targetDivisi()
    {
        return $this->belongsTo(Divisi::class, 'target_divisi_id')
                    ->withDefault([
                        'divisi' => 'Tidak Diketahui'
                    ]);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class)->orderBy('created_at', 'desc');
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class)->orderBy('created_at', 'desc');
    }

    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id')
                    ->withDefault([
                        'nama' => 'Tidak terkait proyek',
                        'layanan_id' => null
                    ]);
    }

    public function projectLayanan()
    {
        return $this->hasOneThrough(
            Layanan::class,
            Project::class,
            'id',
            'id',
            'project_id',
            'layanan_id'
        );
    }

    // ========== ACCESSORS ==========
    public function getIsOverdueAttribute()
    {
        return $this->deadline && 
               now()->gt($this->deadline) && 
               !in_array($this->status, ['selesai', 'dibatalkan']);
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->deadline || in_array($this->status, ['selesai', 'dibatalkan'])) {
            return null;
        }
        
        return now()->diffInDays($this->deadline, false);
    }

    public function getFormattedDeadlineAttribute()
    {
        if (!$this->deadline) {
            return '-';
        }
        
        $today = now()->startOfDay();
        $deadlineDate = $this->deadline->startOfDay();
        
        if ($deadlineDate->eq($today)) {
            return 'Hari ini ' . $this->deadline->format('H:i');
        } elseif ($deadlineDate->eq($today->copy()->addDay())) {
            return 'Besok ' . $this->deadline->format('H:i');
        } elseif ($deadlineDate->eq($today->copy()->subDay())) {
            return 'Kemarin ' . $this->deadline->format('H:i');
        }
        
        return $this->deadline->translatedFormat('d M Y H:i');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'proses' => 'Dalam Proses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'review' => 'Perlu Review',
        ];
        
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }

    public function getPriorityLabelAttribute()
    {
        $labels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'urgent' => 'Mendesak',
        ];
        
        return $labels[$this->priority] ?? 'Sedang';
    }

    public function getAssigneeNameAttribute()
    {
        if ($this->target_type === 'karyawan' && $this->assignee) {
            return $this->assignee->name;
        } elseif ($this->target_type === 'divisi') {
            // PERBAIKAN: Gunakan relasi targetDivisi
            $divisiName = $this->targetDivisi ? $this->targetDivisi->divisi : '-';
            return 'Seluruh Divisi ' . $divisiName;
        } elseif ($this->target_type === 'manager' && $this->targetManager) {
            return 'Manager: ' . $this->targetManager->name;
        } elseif ($this->assigned_to && $this->assignee) {
            return $this->assignee->name;
        }
        
        return 'Belum Ditugaskan';
    }

    public function getAssigneeDetailAttribute()
    {
        if ($this->target_type === 'karyawan' && $this->assignee) {
            $divisiName = $this->assignee->divisi ? $this->assignee->divisi->divisi : '-';
            return $this->assignee->name . ' (' . $divisiName . ')';
        } elseif ($this->target_type === 'divisi') {
            $divisiName = $this->targetDivisi ? $this->targetDivisi->divisi : '-';
            return 'Divisi: ' . $divisiName;
        } elseif ($this->target_type === 'manager' && $this->targetManager) {
            $divisiName = $this->targetManager->divisi ? $this->targetManager->divisi->divisi : '-';
            return 'Manager: ' . $this->targetManager->name . ' (' . $divisiName . ')';
        }
        
        return '-';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'proses' => 'info',
            'selesai' => 'success',
            'dibatalkan' => 'danger',
            'review' => 'primary',
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'urgent' => 'danger',
        ];
        
        return $colors[$this->priority] ?? 'secondary';
    }

    public function getProgressLabelAttribute()
    {
        if ($this->progress_percentage >= 100) {
            return 'Selesai';
        } elseif ($this->progress_percentage >= 75) {
            return 'Hampir Selesai';
        } elseif ($this->progress_percentage >= 50) {
            return 'Setengah Jalan';
        } elseif ($this->progress_percentage >= 25) {
            return 'Dalam Proses';
        } elseif ($this->progress_percentage > 0) {
            return 'Baru Dimulai';
        }
        
        return 'Belum Dimulai';
    }

    // Accessor untuk backward compatibility (jika ada kode masih panggil target_divisi)
    public function getTargetDivisiAttribute()
    {
        return $this->targetDivisi ? $this->targetDivisi->divisi : null;
    }

    // ========== ACCESSOR BARU: PROJECT ==========
    public function getProjectNameAttribute()
    {
        return $this->project ? $this->project->nama : 'Tidak terkait proyek';
    }

    public function getProjectLayananAttribute()
    {
        return $this->project && $this->project->layanan 
            ? $this->project->layanan->nama 
            : '-';
    }

    public function getProjectDeadlineAttribute()
    {
        return $this->project && $this->project->deadline
            ? $this->project->deadline->format('d M Y')
            : '-';
    }

    public function getProjectProgressAttribute()
    {
        return $this->project ? $this->project->progres : 0;
    }

    public function getIsRelatedToProjectAttribute()
    {
        return !is_null($this->project_id);
    }

    // ========== ACCESSOR LAINNYA ==========
    public function getIsAssignedToMeAttribute()
    {
        if (!auth()->check()) {
            return false;
        }
        
        $userId = auth()->id();
        
        // Jika tugas untuk divisi dan user adalah bagian dari divisi tersebut
        if ($this->target_type === 'divisi' && 
            auth()->user()->divisi_id == $this->target_divisi_id) {
            return true;
        }
        
        // Jika tugas langsung untuk user
        if ($this->assigned_to == $userId) {
            return true;
        }
        
        // Jika tugas dari manager ke user (sebagai target manager)
        if ($this->target_manager_id == $userId) {
            return true;
        }
        
        return false;
    }

    public function getCanEditAttribute()
    {
        if (!auth()->check()) {
            return false;
        }
        
        $userId = auth()->id();
        $userRole = auth()->user()->role;
        
        if (in_array($userRole, ['admin', 'general_manager'])) {
            return true;
        }
        
        if ($this->created_by == $userId) {
            return true;
        }
        
        if ($this->assigned_by_manager == $userId) {
            return true;
        }
        
        if ($userRole === 'manager_divisi' && 
            auth()->user()->divisi_id == $this->target_divisi_id) {
            return true;
        }
        
        return false;
    }

    public function getCanDeleteAttribute()
    {
        if (!auth()->check()) {
            return false;
        }
        
        $userId = auth()->id();
        $userRole = auth()->user()->role;
        
        if (in_array($userRole, ['admin', 'general_manager'])) {
            return true;
        }
        
        if ($this->created_by == $userId && 
            !$this->assigned_to && 
            $this->status === 'pending') {
            return true;
        }
        
        return false;
    }

    // ========== SCOPES ==========
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

    public function scopeDibatalkan($query)
    {
        return $query->where('status', 'dibatalkan');
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
                    ->whereNotIn('status', ['selesai', 'dibatalkan']);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('deadline', today())
                    ->whereNotIn('status', ['selesai', 'dibatalkan']);
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('deadline', [now(), now()->addWeek()])
                    ->whereNotIn('status', ['selesai', 'dibatalkan']);
    }

    public function scopeForDivisi($query, $divisiId)
    {
        // PERBAIKAN: Gunakan target_divisi_id
        return $query->where('target_divisi_id', $divisiId)
                    ->orWhereHas('assignee', function($q) use ($divisiId) {
                        $q->where('divisi_id', $divisiId);
                    });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('assigned_to', $userId)
                    ->orWhere(function($q) use ($userId) {
                        $q->where('target_type', 'divisi')
                          ->whereHas('targetDivisi.users', function($q) use ($userId) {
                              $q->where('users.id', $userId);
                          });
                    })
                    ->orWhere('target_manager_id', $userId)
                    ->orWhere('created_by', $userId);
    }

    public function scopeForManager($query, $managerId)
    {
        // PERBAIKAN: Gunakan target_divisi_id bukan target_divisi
        return $query->where(function($q) use ($managerId) {
            $q->where('created_by', $managerId)
              ->orWhere('assigned_by_manager', $managerId)
              ->orWhere('target_manager_id', $managerId);
        });
    }

    public function scopeBroadcast($query)
    {
        return $query->where('is_broadcast', true);
    }

    public function scopeIndividual($query)
    {
        return $query->where('is_broadcast', false);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    // ========== SCOPE BARU: FILTER BERDASARKAN PROJECT ==========
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeWithProject($query)
    {
        return $query->whereNotNull('project_id');
    }

    public function scopeWithoutProject($query)
    {
        return $query->whereNull('project_id');
    }

    public function scopeForLayanan($query, $layananId)
    {
        return $query->whereHas('project', function($q) use ($layananId) {
            $q->where('layanan_id', $layananId);
        });
    }

    public function scopeForProjectStatus($query, $status)
    {
        return $query->whereHas('project', function($q) use ($status) {
            $q->where('status', $status);
        });
    }

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
              ->orWhere('catatan', 'like', "%{$search}%")
              ->orWhere('kategori', 'like', "%{$search}%")
              ->orWhereHas('assignee', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('creator', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('project', function($q) use ($search) {
                  $q->where('nama', 'like', "%{$search}%");
              });
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        if (empty($status) || $status === 'all') {
            return $query;
        }
        
        return $query->where('status', $status);
    }

    public function scopeFilterByPriority($query, $priority)
    {
        if (empty($priority) || $priority === 'all') {
            return $query;
        }
        
        return $query->where('priority', $priority);
    }

    public function scopeFilterByKategori($query, $kategori)
    {
        if (empty($kategori) || $kategori === 'all') {
            return $query;
        }
        
        return $query->where('kategori', $kategori);
    }

    public function scopeFilterByProject($query, $projectId)
    {
        if (empty($projectId) || $projectId === 'all') {
            return $query;
        }
        
        return $query->where('project_id', $projectId);
    }

    public function scopeOrderByDeadline($query, $direction = 'asc')
    {
        return $query->orderBy('deadline', $direction);
    }

    public function scopeOrderByPriority($query, $direction = 'desc')
    {
        $priorityOrder = ['urgent' => 4, 'high' => 3, 'medium' => 2, 'low' => 1];
        
        return $query->orderByRaw(
            "CASE priority " . 
            "WHEN 'urgent' THEN 4 " .
            "WHEN 'high' THEN 3 " .
            "WHEN 'medium' THEN 2 " .
            "WHEN 'low' THEN 1 " .
            "ELSE 0 END " . $direction
        );
    }

    // ========== METHODS ==========
    public function markAsProses()
    {
        return $this->update([
            'status' => 'proses',
            'assigned_at' => now(),
        ]);
    }

    public function markAsSelesai($filePath = null, $notes = null)
    {
        return $this->update([
            'status' => 'selesai',
            'submission_file' => $filePath,
            'submission_notes' => $notes,
            'submitted_at' => now(),
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);
    }

    public function markAsDibatalkan($reason = null)
    {
        return $this->update([
            'status' => 'dibatalkan',
            'catatan_update' => $reason,
            'completed_at' => now(),
        ]);
    }

    public function updateProgress($percentage, $notes = null)
    {
        $percentage = max(0, min(100, $percentage));
        
        $data = [
            'progress_percentage' => $percentage,
        ];
        
        if ($percentage >= 100) {
            $data['status'] = 'selesai';
            $data['completed_at'] = now();
        } elseif ($percentage > 0 && $this->status === 'pending') {
            $data['status'] = 'proses';
        }
        
        if ($notes) {
            $data['catatan_update'] = $notes;
        }
        
        return $this->update($data);
    }

    public function assignTo($userId, $managerId = null)
    {
        return $this->update([
            'assigned_to' => $userId,
            'assigned_by_manager' => $managerId ?? auth()->id(),
            'assigned_at' => now(),
            'target_type' => 'karyawan',
            'is_broadcast' => false,
        ]);
    }

    public function broadcastToDivisi($divisiId)
    {
        // PERBAIKAN: Hanya set target_divisi_id, tidak perlu target_divisi
        return $this->update([
            'target_divisi_id' => $divisiId,
            'target_type' => 'divisi',
            'is_broadcast' => true,
            'assigned_to' => null,
        ]);
    }

    public function addComment($content, $userId = null)
    {
        return $this->comments()->create([
            'content' => $content,
            'user_id' => $userId ?? auth()->id(),
        ]);
    }

    public function addFile($filePath, $originalName, $userId = null)
    {
        return $this->files()->create([
            'file_path' => $filePath,
            'original_name' => $originalName,
            'user_id' => $userId ?? auth()->id(),
        ]);
    }

    public function createSubtask($data)
    {
        $subtaskData = array_merge($data, [
            'parent_task_id' => $this->id,
            'created_by' => auth()->id(),
            'target_divisi_id' => $this->target_divisi_id,
            'project_id' => $this->project_id,
        ]);
        
        return Task::create($subtaskData);
    }

    // ========== METHOD BARU: PROJECT RELATED ==========
    public function linkToProject($projectId)
    {
        $project = Project::find($projectId);
        
        if (!$project) {
            return false;
        }
        
        $this->update([
            'project_id' => $projectId,
            'kategori' => $project->layanan ? $project->layanan->nama : $this->kategori,
        ]);
        
        $this->updateProjectProgress();
        
        return true;
    }

    public function unlinkFromProject()
    {
        $this->update([
            'project_id' => null,
        ]);
        
        return true;
    }

    public function updateProjectProgress()
    {
        if (!$this->project_id) {
            return false;
        }
        
        $project = Project::find($this->project_id);
        
        if (!$project) {
            return false;
        }
        
        $averageProgress = Task::where('project_id', $this->project_id)
            ->avg('progress_percentage');
        
        $project->update([
            'progres' => round($averageProgress)
        ]);
        
        return true;
    }

    public function getProjectTasks()
    {
        if (!$this->project_id) {
            return collect();
        }
        
        return Task::where('project_id', $this->project_id)
            ->where('id', '!=', $this->id)
            ->get();
    }

    // ========== Boot method untuk event handling ==========
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (empty($task->status)) {
                $task->status = 'pending';
            }
            
            if (empty($task->priority)) {
                $task->priority = 'medium';
            }
            
            if (empty($task->progress_percentage)) {
                $task->progress_percentage = 0;
            }
            
            if ($task->project_id && empty($task->kategori)) {
                $project = Project::find($task->project_id);
                if ($project && $project->layanan) {
                    $task->kategori = $project->layanan->nama;
                }
            }
        });

        static::updated(function ($task) {
            if ($task->parent_task_id) {
                $parentTask = Task::find($task->parent_task_id);
                if ($parentTask) {
                    $subtasks = $parentTask->subtasks;
                    if ($subtasks->count() > 0) {
                        $averageProgress = $subtasks->avg('progress_percentage');
                        $parentTask->updateProgress(round($averageProgress));
                    }
                }
            }
            
            if ($task->isDirty('progress_percentage') || $task->isDirty('status')) {
                $task->updateProjectProgress();
            }
        });

        static::deleted(function ($task) {
            if ($task->project_id) {
                $task->updateProjectProgress();
            }
        });
    }

    // ========== Appended attributes for API ==========
    protected $appends = [
        'is_overdue',
        'days_remaining',
        'status_label',
        'priority_label',
        'formatted_deadline',
        'assignee_name',
        'assignee_detail',
        'status_color',
        'priority_color',
        'progress_label',
        'is_assigned_to_me',
        'can_edit',
        'can_delete',
        'project_name',
        'project_layanan',
        'project_deadline',
        'project_progress',
        'is_related_to_project',
        'target_divisi', // Untuk backward compatibility
    ];
}