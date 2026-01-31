<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use App\Models\TaskFile;
use App\Models\User;
use App\Models\Project;
use App\Models\Layanan;
use App\Models\Divisi; // Tambahkan model Divisi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display list of tasks for admin (dashboard)
     */
    public function index()
    {
        try {
            $tasks = Task::with(['assignee', 'creator', 'targetManager', 'comments.user', 'files.uploader', 'project', 'targetDivisi'])
                ->orderBy('deadline', 'asc')
                ->get();
            
            Log::info('Loading all tasks for admin', ['count' => $tasks->count()]);
            
            return view('admin.tasks.index', compact('tasks'));
            
        } catch (\Exception $e) {
            Log::error('Error loading tasks: ' . $e->getMessage());
            return view('admin.tasks.index', ['tasks' => collect([])]);
        }
    }
    
    /**
     * Display tasks for manager divisi - DIPERBAIKI
     */
    public function managerTasks()
    {
        try {
            $user = Auth::user();
            $userDivisiId = $user->divisi_id; // Gunakan divisi_id
            
            Log::info('Loading tasks for manager divisi', [
                'user_id' => $user->id,
                'divisi_id' => $userDivisiId
            ]);
            
            // PERBAIKAN: Gunakan target_divisi_id, bukan target_divisi
            $tasks = Task::with(['assignee', 'creator', 'targetManager', 'comments.user', 'files.uploader', 'project', 'targetDivisi'])
                ->where(function($query) use ($user, $userDivisiId) {
                    // PERBAIKAN: Gunakan target_divisi_id
                    $query->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $user->id)
                          ->orWhere('assigned_to', $user->id)
                          ->orWhere('assigned_by_manager', $user->id);
                })
                ->orderBy('deadline', 'asc')
                ->get();
            
            return view('manager_divisi.pengelola_tugas', compact('tasks'));
            
        } catch (\Exception $e) {
            Log::error('Error loading manager tasks: ' . $e->getMessage());
            return view('manager_divisi.pengelola_tugas', ['tasks' => collect([])]);
        }
    }
    
    /**
     * Create task page - DIPERBAIKI dengan data projek
     */
    public function create()
    {
        // Ambil data projek untuk dropdown
        $projects = Project::where('status', '!=', 'selesai')
                          ->orderBy('nama', 'asc')
                          ->get(['id', 'nama', 'layanan_id', 'deadline']);
        
        // Ambil data layanan untuk referensi
        $layanans = Layanan::orderBy('nama', 'asc')->get(['id', 'nama']);
        
        // Ambil data divisi untuk dropdown
        $divisis = Divisi::orderBy('divisi', 'asc')->get(['id', 'divisi']);
        
        return view('admin.tasks.create', compact('projects', 'layanans', 'divisis'));
    }
    
    /**
     * Edit task page - DIPERBAIKI dengan data projek
     */
    public function edit($id)
    {
        $task = Task::with(['assignee', 'creator', 'targetManager', 'project', 'targetDivisi'])->findOrFail($id);
        
        // Ambil data projek untuk dropdown
        $projects = Project::where('status', '!=', 'selesai')
                          ->orderBy('nama', 'asc')
                          ->get(['id', 'nama', 'layanan_id', 'deadline']);
        
        // Ambil data divisi untuk dropdown
        $divisis = Divisi::orderBy('divisi', 'asc')->get(['id', 'divisi']);
        
        return view('admin.tasks.edit', compact('task', 'projects', 'divisis'));
    }
    
    /**
     * Get tasks via API (untuk General Manager) - DIPERBAIKI
     */
    public function apiGetTasks()
    {
        try {
            $userId = Auth::id();
            $userDivisiId = Auth::user()->divisi_id; // Gunakan divisi_id
            
            Log::info('=== GENERAL MANAGER API GET TASKS ===', [
                'user_id' => $userId,
                'user_divisi_id' => $userDivisiId,
            ]);
            
            $tasks = Task::with(['assignee', 'creator', 'targetManager', 'comments.user', 'files.uploader', 'project', 'targetDivisi'])
                ->orderBy('deadline', 'asc')
                ->get();
            
            $transformedTasks = $tasks->map(function($task) use ($userId, $userDivisiId) {
                // Tentukan assignee text
                $assigneeText = '-';
                $assigneeDivisi = '-';
                
                if ($task->target_type === 'karyawan' && $task->assignee) {
                    $assigneeText = $task->assignee->name;
                    $assigneeDivisi = $task->assignee->divisi ?? '-';
                } elseif ($task->target_type === 'divisi') {
                    // PERBAIKAN: Gunakan relasi targetDivisi
                    $divisiName = $task->targetDivisi ? $task->targetDivisi->divisi : '-';
                    $assigneeText = 'Divisi ' . $divisiName;
                    $assigneeDivisi = $divisiName;
                } elseif ($task->target_type === 'manager' && $task->targetManager) {
                    $assigneeText = 'Manager: ' . $task->targetManager->name;
                    $assigneeDivisi = $task->targetManager->divisi ?? '-';
                }
                
                // Check if overdue
                $isOverdue = $task->deadline && 
                             now()->gt($task->deadline) && 
                             !in_array($task->status, ['selesai', 'dibatalkan']);
                
                // Tentukan apakah task ini untuk user yang login
                $isForMe = false;
                if ($task->target_type === 'divisi') {
                    // PERBAIKAN: Gunakan divisi_id
                    $isForMe = $task->target_divisi_id == $userDivisiId;
                } elseif ($task->target_type === 'manager' && $task->target_manager_id === $userId) {
                    $isForMe = true;
                }
                
                return [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'submission_file' => $task->submission_file,
                    'submission_notes' => $task->submission_notes,
                    'submitted_at' => $task->submitted_at ? $task->submitted_at->format('Y-m-d H:i:s') : null,
                    'target_type' => $task->target_type,
                    'assigned_to' => $task->assigned_to,
                    'created_by' => $task->created_by,
                    'target_manager_id' => $task->target_manager_id,
                    'target_divisi_id' => $task->target_divisi_id, // PERBAIKAN: Gunakan ID
                    'is_broadcast' => $task->is_broadcast,
                    'catatan' => $task->catatan,
                    'catatan_update' => $task->catatan_update,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $task->updated_at->format('Y-m-d H:i:s'),
                    
                    // Data projek terkait
                    'project_id' => $task->project_id,
                    'project_name' => $task->project ? $task->project->nama : null,
                    'project_deadline' => $task->project ? ($task->project->deadline ? $task->project->deadline->format('Y-m-d') : null) : null,
                    'project_status' => $task->project ? $task->project->status : null,
                    'project_progress' => $task->project ? $task->project->progres : null,
                    
                    // Computed fields untuk frontend
                    'assignee_text' => $assigneeText,
                    'assignee_divisi' => $assigneeDivisi,
                    'creator_name' => $task->creator->name ?? '-',
                    'is_overdue' => $isOverdue,
                    'is_for_me' => $isForMe,
                    'has_submission' => !is_null($task->submission_file),
                    'submission_url' => $task->submission_file ? Storage::url($task->submission_file) : null,
                    'comments_count' => $task->comments->count(),
                    'files_count' => $task->files->count(),
                    
                    // Relasi objects
                    'assignee' => $task->assignee ? [
                        'id' => $task->assignee->id,
                        'name' => $task->assignee->name,
                        'divisi' => $task->assignee->divisi ?? '-'
                    ] : null,
                    'creator' => $task->creator ? [
                        'id' => $task->creator->id,
                        'name' => $task->creator->name
                    ] : null,
                    'target_manager' => $task->targetManager ? [
                        'id' => $task->targetManager->id,
                        'name' => $task->targetManager->name,
                        'divisi' => $task->targetManager->divisi ?? '-'
                    ] : null,
                    'target_divisi' => $task->targetDivisi ? [ // Tambahkan target_divisi object
                        'id' => $task->targetDivisi->id,
                        'divisi' => $task->targetDivisi->divisi,
                        'nama' => $task->targetDivisi->divisi
                    ] : null,
                    'project' => $task->project ? [
                        'id' => $task->project->id,
                        'nama' => $task->project->nama,
                        'layanan' => $task->project->layanan ? $task->project->layanan->nama : null,
                        'deadline' => $task->project->deadline ? $task->project->deadline->format('Y-m-d') : null,
                        'status' => $task->project->status,
                        'progres' => $task->project->progres
                    ] : null,
                ];
            });
            
            Log::info('Tasks transformed', [
                'total' => $transformedTasks->count(),
                'for_me' => $transformedTasks->where('is_for_me', true)->count(),
            ]);
            
            return response()->json($transformedTasks);
            
        } catch (\Exception $e) {
            Log::error('Error in apiGetTasks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load tasks: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get tasks for manager via API - FIXED VERSION dengan target_divisi_id
     */
    public function apiGetManagerTasks()
    {
        try {
            $user = Auth::user();
            $userDivisiId = $user->divisi_id; // PERBAIKAN: Gunakan divisi_id
            
            Log::info('=== MANAGER DIVISI API GET TASKS (FIXED) ===', [
                'user_id' => $user->id,
                'user_divisi_id' => $userDivisiId,
            ]);
            
            // PERBAIKAN: Gunakan target_divisi_id, bukan target_divisi
            $tasks = Task::with(['assignee', 'creator', 'comments.user', 'files.uploader', 'project', 'targetDivisi'])
                ->where(function($query) use ($user, $userDivisiId) {
                    // PERBAIKAN: Gunakan target_divisi_id
                    $query->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $user->id)
                          ->orWhere('assigned_to', $user->id)
                          ->orWhere('assigned_by_manager', $user->id);
                })
                ->whereNull('deleted_at')
                ->orderBy('deadline', 'asc')
                ->get();
            
            $transformedTasks = $tasks->map(function($task) use ($user) {
                // Tentukan assignee text
                $assigneeText = '-';
                $assigneeDivisi = '-';
                
                if ($task->target_type === 'karyawan' && $task->assignee) {
                    $assigneeText = $task->assignee->name;
                    $assigneeDivisi = $task->assignee->divisi ?? '-';
                } elseif ($task->target_type === 'divisi') {
                    // PERBAIKAN: Gunakan relasi targetDivisi
                    $divisiName = $task->targetDivisi ? $task->targetDivisi->divisi : '-';
                    $assigneeText = 'Divisi ' . $divisiName;
                    $assigneeDivisi = $divisiName;
                } elseif ($task->target_type === 'manager' && $task->targetManager) {
                    $assigneeText = 'Manager: ' . $task->targetManager->name;
                    $assigneeDivisi = $task->targetManager->divisi ?? '-';
                }
                
                // Check if overdue
                $isOverdue = $task->deadline && 
                             now()->gt($task->deadline) && 
                             !in_array($task->status, ['selesai', 'dibatalkan']);
                
                // Tentukan apakah task ini untuk user yang login
                $isForMe = false;
                if ($task->assigned_to === $user->id) {
                    $isForMe = true;
                } elseif ($task->target_type === 'divisi' && $task->target_divisi_id == $user->divisi_id) {
                    $isForMe = true;
                }
                
                return [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'submission_file' => $task->submission_file,
                    'submission_notes' => $task->submission_notes,
                    'submitted_at' => $task->submitted_at ? $task->submitted_at->format('Y-m-d H:i:s') : null,
                    'target_type' => $task->target_type,
                    'assigned_to' => $task->assigned_to,
                    'created_by' => $task->created_by,
                    'target_manager_id' => $task->target_manager_id,
                    'target_divisi_id' => $task->target_divisi_id, // PERBAIKAN: Gunakan ID
                    'is_broadcast' => $task->is_broadcast,
                    'catatan' => $task->catatan,
                    'catatan_update' => $task->catatan_update,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $task->updated_at->format('Y-m-d H:i:s'),
                    
                    // Data projek terkait
                    'project_id' => $task->project_id,
                    'project_name' => $task->project ? $task->project->nama : null,
                    'project_progress' => $task->project ? $task->project->progres : null,
                    
                    // Computed fields untuk frontend
                    'assignee_text' => $assigneeText,
                    'assignee_divisi' => $assigneeDivisi,
                    'creator_name' => $task->creator->name ?? '-',
                    'is_overdue' => $isOverdue,
                    'is_for_me' => $isForMe,
                    'has_submission' => !is_null($task->submission_file),
                    'submission_url' => $task->submission_file ? Storage::url($task->submission_file) : null,
                    'comments_count' => $task->comments->count(),
                    'files_count' => $task->files->count(),
                    
                    // Relasi objects
                    'assignee' => $task->assignee ? [
                        'id' => $task->assignee->id,
                        'name' => $task->assignee->name,
                        'divisi' => $task->assignee->divisi ?? '-'
                    ] : null,
                    'creator' => $task->creator ? [
                        'id' => $task->creator->id,
                        'name' => $task->creator->name
                    ] : null,
                    'target_manager' => $task->targetManager ? [
                        'id' => $task->targetManager->id,
                        'name' => $task->targetManager->name,
                        'divisi' => $task->targetManager->divisi ?? '-'
                    ] : null,
                    'target_divisi' => $task->targetDivisi ? [ // Tambahkan object divisi
                        'id' => $task->targetDivisi->id,
                        'divisi' => $task->targetDivisi->divisi
                    ] : null,
                    'project' => $task->project ? [
                        'id' => $task->project->id,
                        'nama' => $task->project->nama,
                        'progres' => $task->project->progres,
                        'status' => $task->project->status
                    ] : null,
                ];
            });
            
            Log::info('Manager tasks loaded successfully', [
                'total_tasks' => $transformedTasks->count(),
                'user_id' => $user->id,
                'divisi_id' => $userDivisiId
            ]);
            
            return response()->json($transformedTasks);
            
        } catch (\Exception $e) {
            Log::error('Error in apiGetManagerTasks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load manager tasks: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store a new task - DIPERBAIKI dengan target_divisi_id
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'priority' => 'required|in:low,medium,high,urgent',
                'target_type' => 'required|in:karyawan,divisi,manager',
                'assigned_to' => 'nullable|exists:users,id',
                'target_divisi_id' => 'nullable|exists:divisi,id', // PERBAIKAN: Gunakan divisi_id
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
                'project_id' => 'nullable|exists:projects,id', // PERBAIKAN: tabel 'projects' bukan 'project'
                'kategori' => 'nullable|string|max:100',
            ]);
            
            $validated['created_by'] = Auth::id();
            
            // PERBAIKAN: Gunakan target_divisi_id untuk tugas divisi
            if ($validated['target_type'] === 'divisi') {
                $validated['is_broadcast'] = true;
                
                // Auto assign ke manager divisi jika ada
                if (!empty($validated['target_divisi_id'])) {
                    $manager = User::where('role', 'manager_divisi')
                                  ->where('divisi_id', $validated['target_divisi_id']) // PERBAIKAN: divisi_id
                                  ->first();
                    
                    if ($manager) {
                        $validated['target_manager_id'] = $manager->id;
                        $validated['assigned_to'] = $manager->id;
                    }
                }
            } else {
                $validated['is_broadcast'] = false;
                
                // Untuk target manager, assign ke manager tersebut
                if ($validated['target_type'] === 'manager' && $validated['target_manager_id']) {
                    $validated['assigned_to'] = $validated['target_manager_id'];
                }
            }
            
            // Auto-set kategori berdasarkan project jika tidak diisi
            if (empty($validated['kategori']) && !empty($validated['project_id'])) {
                $project = Project::find($validated['project_id']);
                if ($project && $project->layanan) {
                    $validated['kategori'] = $project->layanan->nama ?? 'proyek';
                }
            }
            
            $task = Task::create($validated);
            
            Log::info('Task created successfully', [
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'target_divisi_id' => $task->target_divisi_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task->load(['project', 'targetDivisi'])
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update task - DIPERBAIKI dengan target_divisi_id
     */
    public function update(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            
            // Check permission
            if ($task->created_by !== Auth::id() && Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'priority' => 'required|in:low,medium,high,urgent',
                'assigned_to' => 'nullable|exists:users,id',
                'target_divisi_id' => 'nullable|exists:divisi,id', // PERBAIKAN: divisi_id
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
                'project_id' => 'nullable|exists:projects,id', // PERBAIKAN: 'projects'
                'kategori' => 'nullable|string|max:100',
            ]);
            
            // PERBAIKAN: Update is_broadcast dengan target_divisi_id
            if (isset($validated['target_divisi_id']) && !empty($validated['target_divisi_id'])) {
                $validated['is_broadcast'] = true;
                
                // Auto assign ke manager divisi jika belum diassign
                if (!isset($validated['assigned_to']) || empty($validated['assigned_to'])) {
                    $manager = User::where('role', 'manager_divisi')
                                  ->where('divisi_id', $validated['target_divisi_id'])
                                  ->first();
                    
                    if ($manager) {
                        $validated['target_manager_id'] = $manager->id;
                        $validated['assigned_to'] = $manager->id;
                    }
                }
            } else {
                $validated['is_broadcast'] = false;
            }
            
            // Set completed_at if status changed to selesai
            if ($validated['status'] === 'selesai' && $task->status !== 'selesai') {
                $validated['completed_at'] = now();
            }
            
            // Auto-set kategori berdasarkan project jika tidak diisi
            if (empty($validated['kategori']) && !empty($validated['project_id'])) {
                $project = Project::find($validated['project_id']);
                if ($project && $project->layanan) {
                    $validated['kategori'] = $project->layanan->nama ?? 'proyek';
                }
            }
            
            $task->update($validated);
            
            Log::info('Task updated successfully', [
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'target_divisi_id' => $task->target_divisi_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $task->fresh(['project', 'targetDivisi'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get active projects for dropdown (API)
     */
    public function getActiveProjectsApi()
    {
        try {
            $projects = Project::with('layanan')
                ->where('status', '!=', 'selesai')
                ->orderBy('nama', 'asc')
                ->get(['id', 'nama', 'layanan_id', 'deadline', 'progres', 'status', 'penanggung_jawab_id']);
            
            // Format data untuk dropdown
            $formattedProjects = $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'nama' => $project->nama,
                    'layanan' => $project->layanan ? $project->layanan->nama : '-',
                    'deadline' => $project->deadline ? $project->deadline->format('Y-m-d') : null,
                    'progres' => $project->progres ?? 0,
                    'status' => $project->status,
                    'penanggung_jawab' => $project->penanggungJawab ? $project->penanggungJawab->name : null,
                ];
            });
            
            return response()->json([
                'success' => true,
                'projects' => $formattedProjects
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting active projects: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load projects'
            ], 500);
        }
    }
    
    /**
     * Get statistics via API - DIPERBAIKI dengan target_divisi_id
     */
    public function apiGetStatistics()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            if ($user->role === 'admin' || $user->role === 'general_manager') {
                // Admin dan GM bisa lihat semua statistik
                $stats = [
                    'total' => Task::count(),
                    'pending' => Task::where('status', 'pending')->count(),
                    'in_progress' => Task::where('status', 'proses')->count(),
                    'completed' => Task::where('status', 'selesai')->count(),
                    'cancelled' => Task::where('status', 'dibatalkan')->count(),
                    'with_submission' => Task::whereNotNull('submission_file')->count(),
                    'overdue' => Task::where('deadline', '<', now())
                        ->whereNotIn('status', ['selesai', 'dibatalkan'])
                        ->count(),
                    'with_project' => Task::whereNotNull('project_id')->count(),
                ];
            } elseif ($user->role === 'manager_divisi') {
                // Manager hanya lihat statistik divisinya
                $userDivisiId = $user->divisi_id;
                
                $stats = [
                    'total' => Task::where(function($q) use ($userDivisiId, $userId) {
                        // PERBAIKAN: Gunakan target_divisi_id
                        $q->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $userId)
                          ->orWhere('assigned_to', $userId)
                          ->orWhere('assigned_by_manager', $userId);
                    })->count(),
                    'pending' => Task::where(function($q) use ($userDivisiId, $userId) {
                        $q->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $userId)
                          ->orWhere('assigned_to', $userId)
                          ->orWhere('assigned_by_manager', $userId);
                    })->where('status', 'pending')->count(),
                    'in_progress' => Task::where(function($q) use ($userDivisiId, $userId) {
                        $q->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $userId)
                          ->orWhere('assigned_to', $userId)
                          ->orWhere('assigned_by_manager', $userId);
                    })->where('status', 'proses')->count(),
                    'completed' => Task::where(function($q) use ($userDivisiId, $userId) {
                        $q->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $userId)
                          ->orWhere('assigned_to', $userId)
                          ->orWhere('assigned_by_manager', $userId);
                    })->where('status', 'selesai')->count(),
                    'cancelled' => Task::where(function($q) use ($userDivisiId, $userId) {
                        $q->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $userId)
                          ->orWhere('assigned_to', $userId)
                          ->orWhere('assigned_by_manager', $userId);
                    })->where('status', 'dibatalkan')->count(),
                    'with_submission' => Task::where(function($q) use ($userDivisiId, $userId) {
                        $q->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $userId)
                          ->orWhere('assigned_to', $userId)
                          ->orWhere('assigned_by_manager', $userId);
                    })->whereNotNull('submission_file')->count(),
                    'overdue' => Task::where('deadline', '<', now())
                        ->whereNotIn('status', ['selesai', 'dibatalkan'])
                        ->where(function($q) use ($userDivisiId, $userId) {
                            $q->where('target_divisi_id', $userDivisiId)
                              ->orWhere('created_by', $userId)
                              ->orWhere('assigned_to', $userId)
                              ->orWhere('assigned_by_manager', $userId);
                        })
                        ->count(),
                    'with_project' => Task::where(function($q) use ($userDivisiId, $userId) {
                        $q->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $userId)
                          ->orWhere('assigned_to', $userId)
                          ->orWhere('assigned_by_manager', $userId);
                    })->whereNotNull('project_id')->count(),
                ];
            } else {
                // Untuk karyawan
                $stats = [
                    'total' => Task::where('assigned_to', $userId)->count(),
                    'pending' => Task::where('assigned_to', $userId)->where('status', 'pending')->count(),
                    'in_progress' => Task::where('assigned_to', $userId)->where('status', 'proses')->count(),
                    'completed' => Task::where('assigned_to', $userId)->where('status', 'selesai')->count(),
                    'cancelled' => Task::where('assigned_to', $userId)->where('status', 'dibatalkan')->count(),
                    'with_submission' => Task::where('assigned_to', $userId)->whereNotNull('submission_file')->count(),
                    'overdue' => Task::where('assigned_to', $userId)
                        ->where('deadline', '<', now())
                        ->whereNotIn('status', ['selesai', 'dibatalkan'])
                        ->count(),
                    'with_project' => Task::where('assigned_to', $userId)->whereNotNull('project_id')->count(),
                ];
            }
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            Log::error('Error getting statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }
    
    /**
     * Create task from project (API) - DIPERBAIKI
     */
    public function createTaskFromProject(Request $request)
    {
        try {
            $validated = $request->validate([
                'project_id' => 'required|exists:projects,id', // PERBAIKAN: 'projects'
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'priority' => 'required|in:low,medium,high,urgent',
                'target_type' => 'required|in:karyawan,divisi,manager',
                'assigned_to' => 'nullable|exists:users,id',
                'target_divisi_id' => 'nullable|exists:divisi,id', // PERBAIKAN: divisi_id
                'catatan' => 'nullable|string',
            ]);
            
            $project = Project::findOrFail($validated['project_id']);
            
            $validated['created_by'] = Auth::id();
            $validated['status'] = 'pending';
            
            // Set kategori berdasarkan layanan projek
            if ($project->layanan) {
                $validated['kategori'] = $project->layanan->nama;
            }
            
            // Auto-assign logic
            if ($validated['target_type'] === 'divisi') {
                $validated['is_broadcast'] = true;
                
                if (!empty($validated['target_divisi_id'])) {
                    $manager = User::where('role', 'manager_divisi')
                                  ->where('divisi_id', $validated['target_divisi_id'])
                                  ->first();
                    
                    if ($manager) {
                        $validated['target_manager_id'] = $manager->id;
                        $validated['assigned_to'] = $manager->id;
                    }
                }
            } else {
                $validated['is_broadcast'] = false;
            }
            
            $task = Task::create($validated);
            
            Log::info('Task created from project', [
                'task_id' => $task->id,
                'project_id' => $project->id,
                'project_name' => $project->nama
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dibuat dari proyek',
                'task' => $task->load(['project', 'assignee', 'creator', 'targetDivisi'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error creating task from project: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Karyawan tasks - DIPERBAIKI
     */
    public function karyawanTasks(Request $request)
    {
        try {
            $userId = Auth::id();
            
            $filters = [
                'status' => $request->get('status', 'all'),
                'search' => $request->get('search', ''),
            ];
            
            $tasks = Task::with(['creator', 'assigner', 'comments', 'files', 'project', 'targetDivisi'])
                ->where('assigned_to', $userId)
                ->when($filters['status'] !== 'all', function($query) use ($filters) {
                    $query->where('status', $filters['status']);
                })
                ->when($filters['search'], function($query) use ($filters) {
                    $query->where(function($q) use ($filters) {
                        $q->where('judul', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('deskripsi', 'like', '%' . $filters['search'] . '%')
                          ->orWhereHas('project', function($q) use ($filters) {
                              $q->where('nama', 'like', '%' . $filters['search'] . '%');
                          });
                    });
                })
                ->orderBy('deadline', 'asc')
                ->get();

            Log::info('Karyawan tasks loaded', [
                'user_id' => $userId,
                'count' => $tasks->count(),
                'filters' => $filters
            ]);

            return view('karyawan.tugas', compact('tasks'));
            
        } catch (\Exception $e) {
            Log::error('Error in karyawanTasks: ' . $e->getMessage());
            return view('karyawan.tugas', [
                'tasks' => collect([]),
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    // ... (method lainnya tetap sama, cukup perbaiki bagian yang menggunakan target_divisi)
    
    /**
     * Get task detail via API - DIPERBAIKI
     */
    public function getTaskDetailApi($taskId)
    {
        try {
            $task = Task::with(['creator', 'assigner', 'comments.user', 'files.uploader', 'project', 'targetDivisi'])
                    ->where('assigned_to', Auth::id())
                    ->find($taskId);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found or not assigned to you'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'task' => [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'deskripsi' => $task->deskripsi ?? 'Tidak ada deskripsi',
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'deadline_formatted' => $task->deadline ? 
                        $task->deadline->translatedFormat('l, d F Y H:i') . ' WIB' : 
                        'Tidak ada deadline',
                    'assigned_by' => $task->creator->name ?? 'Unknown',
                    'is_overdue' => $task->deadline && now()->gt($task->deadline) && !in_array($task->status, ['selesai', 'dibatalkan']),
                    'submission_file' => $task->submission_file,
                    'submission_notes' => $task->submission_notes,
                    'submitted_at' => $task->submitted_at ? $task->submitted_at->format('Y-m-d H:i:s') : null,
                    'submitted_at_formatted' => $task->submitted_at ? 
                        $task->submitted_at->translatedFormat('d F Y H:i') : null,
                    'submission_url' => $task->submission_file ? Storage::url($task->submission_file) : null,
                    'has_submission' => !is_null($task->submission_file),
                    'target_divisi' => $task->targetDivisi ? [
                        'id' => $task->targetDivisi->id,
                        'divisi' => $task->targetDivisi->divisi
                    ] : null,
                    'project' => $task->project ? [
                        'id' => $task->project->id,
                        'nama' => $task->project->nama,
                        'layanan' => $task->project->layanan ? $task->project->layanan->nama : '-'
                    ] : null,
                    'comments' => $task->comments->map(function($comment) {
                        return [
                            'id' => $comment->id,
                            'content' => $comment->content,
                            'created_at' => $comment->created_at->format('d M Y H:i'),
                            'time_ago' => $comment->created_at->diffForHumans(),
                            'user' => [
                                'id' => $comment->user->id,
                                'name' => $comment->user->name,
                                'role' => $comment->user->role ?? 'Karyawan',
                            ]
                        ];
                    }),
                    'files' => $task->files->map(function($file) {
                        return [
                            'id' => $file->id,
                            'filename' => $file->filename,
                            'original_name' => $file->original_name,
                            'path' => $file->path,
                            'size' => $this->formatBytes($file->size),
                            'mime_type' => $file->mime_type,
                            'uploaded_at' => $file->created_at->format('d M Y H:i'),
                            'uploaded_by' => $file->uploader->name ?? 'Unknown',
                            'download_url' => route('api.tasks.files.download', ['file' => $file->id]),
                        ];
                    })
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting task detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }
    }
    
    /**
     * Helper method untuk format bytes
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    /**
 * Get projects managed by current manager divisi (API)
 */
public function getManagedProjects()
{
    try {
        $user = Auth::user();
        
        if ($user->role !== 'manager_divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya manager divisi yang dapat mengakses'
            ], 403);
        }
        
        // Ambil proyek yang ditanggung jawabkan ke manager ini
        $projects = Project::where('penanggung_jawab_id', $user->id)
            ->orWhereHas('tasks', function($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })
            ->with(['layanan', 'penanggungJawab'])
            ->orderBy('nama', 'asc')
            ->get(['id', 'nama', 'layanan_id', 'deadline', 'progres', 'status']);
        
        $formattedProjects = $projects->map(function($project) {
            return [
                'id' => $project->id,
                'nama' => $project->nama,
                'layanan' => $project->layanan ? $project->layanan->nama : '-',
                'deadline' => $project->deadline ? $project->deadline->format('Y-m-d') : null,
                'progres' => $project->progres ?? 0,
                'status' => $project->status,
                'penanggung_jawab' => $project->penanggungJawab ? $project->penanggungJawab->name : null,
            ];
        });
        
        return response()->json([
            'success' => true,
            'projects' => $formattedProjects
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error getting managed projects: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load projects'
        ], 500);
    }
}

/**
 * Get karyawan by divisi (API)
 */
public function getKaryawanByDivisi($divisiId = null)
{
    try {
        $user = Auth::user();
        $divisiId = $divisiId ?? $user->divisi_id;
        
        if (!$divisiId) {
            return response()->json([
                'success' => false,
                'message' => 'Divisi tidak ditemukan'
            ], 404);
        }
        
        $karyawanList = User::where('role', 'karyawan')
            ->where('divisi_id', $divisiId)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'divisi_id']);
        
        return response()->json([
            'success' => true,
            'karyawan' => $karyawanList
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error getting karyawan by divisi: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load karyawan'
        ], 500);
    }
}
}