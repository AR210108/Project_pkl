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
        'nama_tugas', // TAMBAHKAN INI
        'deskripsi',
        'deadline',
        'status',
        'priority',
        'assigned_to',
        'created_by',
        'assigned_by_manager',
        'target_type',
        'target_divisi_id',
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
        'nama_tugas' => '', // TAMBAHKAN DEFAULT VALUE
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
        ];
        
        return $labels[$this->status] ?? 'Tidak Diketahui';
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
            return $this->assignee->name;
        } elseif ($this->target_type === 'divisi') {
            $divisiName = $this->targetDivisi ? $this->targetDivisi->divisi : '-';
            return 'Divisi: ' . $divisiName;
        } elseif ($this->target_type === 'manager' && $this->targetManager) {
            return 'Manager: ' . $this->targetManager->name;
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

    // Accessor untuk nama_tugas fallback
    public function getNamaTugasAttribute($value)
    {
        // Jika nama_tugas kosong, gunakan judul sebagai fallback
        return $value ?? $this->judul;
    }

    public function getFullTaskNameAttribute()
    {
        if ($this->nama_tugas && $this->nama_tugas !== $this->judul) {
            return $this->judul . ' - ' . $this->nama_tugas;
        }
        return $this->judul;
    }

    // Accessor untuk backward compatibility
    public function getTargetDivisiAttribute()
    {
        return $this->targetDivisi ? $this->targetDivisi->divisi : null;
    }

    // Accessor untuk project
    public function getProjectNameAttribute()
    {
        return $this->project ? $this->project->nama : 'Tidak terkait proyek';
    }

    // Accessor untuk pengecekan apakah tugas ditugaskan ke user saat ini
    public function getIsAssignedToMeAttribute()
    {
        if (!auth()->check()) {
            return false;
        }
        
        $userId = auth()->id();
        
        if ($this->target_type === 'divisi' && 
            auth()->user()->divisi_id == $this->target_divisi_id) {
            return true;
        }
        
        if ($this->assigned_to == $userId) {
            return true;
        }
        
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

    public function scopeForDivisi($query, $divisiId)
    {
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
        return $query->where(function($q) use ($managerId) {
            $q->where('created_by', $managerId)
              ->orWhere('assigned_by_manager', $managerId)
              ->orWhere('target_manager_id', $managerId);
        });
    }

    public function scopeForManagerDivisi($query, $managerId, $divisiId = null, $divisiName = null)
    {
        return $query->where(function($q) use ($managerId, $divisiId, $divisiName) {
            // Tugas yang dibuat oleh manager
            $q->where('created_by', $managerId);
            
            // Tugas yang target divisi-nya adalah divisi manager
            if ($divisiId) {
                $q->orWhere('target_divisi_id', $divisiId);
            } else if ($divisiName) {
                $q->orWhereHas('targetDivisi', function($subQ) use ($divisiName) {
                    $subQ->where('divisi', $divisiName);
                });
            }
            
            // Tugas yang ditugaskan ke karyawan di divisi manager
            $q->orWhereHas('assignee', function($subQ) use ($divisiId, $divisiName) {
                if ($divisiId) {
                    $subQ->where('divisi_id', $divisiId);
                } else if ($divisiName) {
                    $subQ->where('divisi', $divisiName);
                }
            });
            
            // Tugas yang target manager-nya adalah manager ini
            $q->orWhere('target_manager_id', $managerId);
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

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('nama_tugas', 'like', "%{$search}%") // TAMBAHKAN INI
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

    public function scopeOrderByDeadline($query, $direction = 'asc')
    {
        return $query->orderBy('deadline', $direction);
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

    // ========== METHOD BARU: FOR API RESPONSE ==========
    public function getApiData()
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'nama_tugas' => $this->nama_tugas, // TAMBAHKAN INI
            'deskripsi' => $this->deskripsi,
            'deadline' => $this->deadline,
            'status' => $this->status,
            'priority' => $this->priority,
            'assigned_to' => $this->assigned_to,
            'created_by' => $this->created_by,
            'target_type' => $this->target_type,
            'target_divisi_id' => $this->target_divisi_id,
            'target_divisi' => $this->targetDivisi ? $this->targetDivisi->divisi : null,
            'project_id' => $this->project_id,
            'project_name' => $this->project ? $this->project->nama : null,
            'assignee_name' => $this->assignee ? $this->assignee->name : null,
            'assigned_to_name' => $this->assignee ? $this->assignee->name : null,
            'creator_name' => $this->creator ? $this->creator->name : null,
            'is_overdue' => $this->is_overdue,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'catatan' => $this->catatan,
            'kategori' => $this->kategori,
            'progress_percentage' => $this->progress_percentage,
            'is_broadcast' => $this->is_broadcast,
            'target_manager_id' => $this->target_manager_id,
            'formatted_deadline' => $this->formatted_deadline,
            'status_label' => $this->status_label,
            'priority_label' => $this->priority_label,
            'status_color' => $this->status_color,
            'priority_color' => $this->priority_color,
            'progress_label' => $this->progress_label,
            'can_edit' => $this->can_edit,
            'can_delete' => $this->can_delete,
            'is_assigned_to_me' => $this->is_assigned_to_me,
            'assignee_detail' => $this->assignee_detail, // TAMBAHKAN COMMA DI SINI
            'full_task_name' => $this->full_task_name, // TAMBAHKAN INI
        ];
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
            if ($task->project_id) {
                $project = Project::find($task->project_id);
                if ($project) {
                    $averageProgress = Task::where('project_id', $task->project_id)
                        ->avg('progress_percentage');
                    $project->update(['progres' => round($averageProgress)]);
                }
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
        'target_divisi',
        'full_task_name', // TAMBAHKAN INI
    ];
}