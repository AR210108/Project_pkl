<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use App\Models\TaskFile;
use App\Models\User;
use App\Models\Project;
use App\Models\Layanan;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
            $userDivisiId = $user->divisi_id;
            
            Log::info('Loading tasks for manager divisi', [
                'user_id' => $user->id,
                'divisi_id' => $userDivisiId
            ]);
            
            $tasks = Task::with(['assignee', 'creator', 'targetManager', 'comments.user', 'files.uploader', 'project', 'targetDivisi'])
                ->where(function($query) use ($user, $userDivisiId) {
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
        $projects = Project::where('status', '!=', 'selesai')
                          ->orderBy('nama', 'asc')
                          ->get(['id', 'nama', 'layanan_id', 'deadline']);
        
        $layanans = Layanan::orderBy('nama', 'asc')->get(['id', 'nama']);
        
        $divisis = Divisi::orderBy('divisi', 'asc')->get(['id', 'divisi']);
        
        return view('admin.tasks.create', compact('projects', 'layanans', 'divisis'));
    }
    
    /**
     * Edit task page - DIPERBAIKI dengan data projek
     */
    public function edit($id)
    {
        $task = Task::with(['assignee', 'creator', 'targetManager', 'project', 'targetDivisi'])->findOrFail($id);
        
        $projects = Project::where('status', '!=', 'selesai')
                          ->orderBy('nama', 'asc')
                          ->get(['id', 'nama', 'layanan_id', 'deadline']);
        
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
            $userDivisiId = Auth::user()->divisi_id;
            
            Log::info('=== GENERAL MANAGER API GET TASKS ===', [
                'user_id' => $userId,
                'user_divisi_id' => $userDivisiId,
            ]);
            
            $tasks = Task::with(['assignee', 'creator', 'targetManager', 'comments.user', 'files.uploader', 'project', 'targetDivisi'])
                ->orderBy('deadline', 'asc')
                ->get();
            
            $transformedTasks = $tasks->map(function($task) use ($userId, $userDivisiId) {
                $assigneeText = '-';
                $assigneeDivisi = '-';
                
                if ($task->target_type === 'karyawan' && $task->assignee) {
                    $assigneeText = $task->assignee->name;
                    $assigneeDivisi = $task->assignee->divisi ?? '-';
                } elseif ($task->target_type === 'divisi') {
                    $divisiName = $task->targetDivisi ? $task->targetDivisi->divisi : '-';
                    $assigneeText = 'Divisi ' . $divisiName;
                    $assigneeDivisi = $divisiName;
                } elseif ($task->target_type === 'manager' && $task->targetManager) {
                    $assigneeText = 'Manager: ' . $task->targetManager->name;
                    $assigneeDivisi = $task->targetManager->divisi ?? '-';
                }
                
                $isOverdue = $task->deadline && 
                             now()->gt($task->deadline) && 
                             !in_array($task->status, ['selesai', 'dibatalkan']);
                
                $isForMe = false;
                if ($task->target_type === 'divisi') {
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
                    'target_divisi_id' => $task->target_divisi_id,
                    'is_broadcast' => $task->is_broadcast,
                    'catatan' => $task->catatan,
                    'catatan_update' => $task->catatan_update,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $task->updated_at->format('Y-m-d H:i:s'),
                    
                    'project_id' => $task->project_id,
                    'project_name' => $task->project ? $task->project->nama : null,
                    'project_deadline' => $task->project ? ($task->project->deadline ? $task->project->deadline->format('Y-m-d') : null) : null,
                    'project_status' => $task->project ? $task->project->status : null,
                    'project_progress' => $task->project ? $task->project->progres : null,
                    
                    'assignee_text' => $assigneeText,
                    'assignee_divisi' => $assigneeDivisi,
                    'creator_name' => $task->creator->name ?? '-',
                    'is_overdue' => $isOverdue,
                    'is_for_me' => $isForMe,
                    'has_submission' => !is_null($task->submission_file),
                    'submission_url' => $task->submission_file ? Storage::url($task->submission_file) : null,
                    'comments_count' => $task->comments->count(),
                    'files_count' => $task->files->count(),
                    
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
                    'target_divisi' => $task->targetDivisi ? [
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
     * Get tasks for manager via API - FIXED VERSION
     */
    public function apiGetManagerTasks()
    {
        try {
            $user = Auth::user();
            $userDivisiId = $user->divisi_id;
            
            Log::info('=== MANAGER DIVISI API GET TASKS (FIXED) ===', [
                'user_id' => $user->id,
                'user_divisi_id' => $userDivisiId,
            ]);
            
            $tasks = Task::with(['assignee', 'creator', 'comments.user', 'files.uploader', 'project', 'targetDivisi'])
                ->where(function($query) use ($user, $userDivisiId) {
                    $query->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $user->id)
                          ->orWhere('assigned_to', $user->id)
                          ->orWhere('assigned_by_manager', $user->id);
                })
                ->whereNull('deleted_at')
                ->orderBy('deadline', 'asc')
                ->get();
            
            $transformedTasks = $tasks->map(function($task) use ($user) {
                $assigneeText = '-';
                $assigneeDivisi = '-';
                
                if ($task->target_type === 'karyawan' && $task->assignee) {
                    $assigneeText = $task->assignee->name;
                    $assigneeDivisi = $task->assignee->divisi ?? '-';
                } elseif ($task->target_type === 'divisi') {
                    $divisiName = $task->targetDivisi ? $task->targetDivisi->divisi : '-';
                    $assigneeText = 'Divisi ' . $divisiName;
                    $assigneeDivisi = $divisiName;
                } elseif ($task->target_type === 'manager' && $task->targetManager) {
                    $assigneeText = 'Manager: ' . $task->targetManager->name;
                    $assigneeDivisi = $task->targetManager->divisi ?? '-';
                }
                
                $isOverdue = $task->deadline && 
                             now()->gt($task->deadline) && 
                             !in_array($task->status, ['selesai', 'dibatalkan']);
                
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
                    'target_divisi_id' => $task->target_divisi_id,
                    'is_broadcast' => $task->is_broadcast,
                    'catatan' => $task->catatan,
                    'catatan_update' => $task->catatan_update,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $task->updated_at->format('Y-m-d H:i:s'),
                    
                    'project_id' => $task->project_id,
                    'project_name' => $task->project ? $task->project->nama : null,
                    'project_progress' => $task->project ? $task->project->progres : null,
                    
                    'assignee_text' => $assigneeText,
                    'assignee_divisi' => $assigneeDivisi,
                    'creator_name' => $task->creator->name ?? '-',
                    'is_overdue' => $isOverdue,
                    'is_for_me' => $isForMe,
                    'has_submission' => !is_null($task->submission_file),
                    'submission_url' => $task->submission_file ? Storage::url($task->submission_file) : null,
                    'comments_count' => $task->comments->count(),
                    'files_count' => $task->files->count(),
                    
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
                    'target_divisi' => $task->targetDivisi ? [
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
     * Store a new task - FIXED VERSION (dengan validasi manual untuk divisi)
     */
    public function store(Request $request)
    {
        try {
            Log::info('=== STORE TASK REQUEST ===', $request->all());
            
            // Validasi manual tanpa exists rule untuk divisi (untuk menghindari error divisis)
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'priority' => 'required|in:low,medium,high,urgent',
                'target_type' => 'required|in:karyawan,divisi,manager',
                'assigned_to' => 'nullable|exists:users,id',
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
                'project_id' => 'nullable|exists:projects,id',
                'kategori' => 'nullable|string|max:100',
                'nama_tugas' => 'nullable|string|max:255', // Field tambahan
            ]);
            
            // Validasi manual untuk target_divisi_id
            if ($request->has('target_divisi_id') && $request->target_divisi_id) {
                if (!Divisi::where('id', $request->target_divisi_id)->exists()) {
                    $validator->errors()->add('target_divisi_id', 'Divisi yang dipilih tidak valid.');
                }
            }
            
            // Jika target_type adalah divisi, target_divisi_id wajib diisi
            if ($request->target_type === 'divisi' && !$request->target_divisi_id) {
                $validator->errors()->add('target_divisi_id', 'Divisi harus dipilih untuk tugas jenis divisi.');
            }
            
            if ($validator->fails()) {
                Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
            $validated['created_by'] = Auth::id();
            
            // Tambahkan nama_tugas jika ada
            if (isset($validated['nama_tugas']) && empty($validated['nama_tugas'])) {
                $validated['nama_tugas'] = $validated['judul'];
            }
            
            // Logic untuk tugas divisi
            if ($validated['target_type'] === 'divisi') {
                $validated['is_broadcast'] = true;
                
                // Auto assign ke manager divisi jika ada
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
            
            // Buat task
            $task = Task::create($validated);
            
            Log::info('Task created successfully', [
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'target_divisi_id' => $task->target_divisi_id,
                'target_type' => $task->target_type
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dibuat',
                'task' => $task->load(['project', 'targetDivisi', 'assignee', 'creator'])
            ]);
            
        } catch (ValidationException $e) {
            Log::error('Validation failed in store task', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat task: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update task - FIXED VERSION (dengan validasi manual untuk divisi)
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
            
            Log::info('=== UPDATE TASK REQUEST ===', [
                'task_id' => $id,
                'data' => $request->all()
            ]);
            
            // Validasi manual
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'priority' => 'required|in:low,medium,high,urgent',
                'assigned_to' => 'nullable|exists:users,id',
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
                'project_id' => 'nullable|exists:projects,id',
                'kategori' => 'nullable|string|max:100',
            ]);
            
            // Validasi manual untuk target_divisi_id
            if ($request->has('target_divisi_id') && $request->target_divisi_id) {
                if (!Divisi::where('id', $request->target_divisi_id)->exists()) {
                    $validator->errors()->add('target_divisi_id', 'Divisi yang dipilih tidak valid.');
                }
            }
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
            
            // Update is_broadcast berdasarkan target_divisi_id
            if (isset($validated['target_divisi_id']) && !empty($validated['target_divisi_id'])) {
                $validated['is_broadcast'] = true;
                $validated['target_type'] = 'divisi';
                
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
            
            // Set completed_at jika status berubah ke selesai
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
                'message' => 'Task berhasil diperbarui',
                'task' => $task->fresh(['project', 'targetDivisi', 'assignee', 'creator'])
            ]);
            
        } catch (ValidationException $e) {
            Log::error('Validation failed in update task', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui task: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store task khusus untuk manager divisi - NEW METHOD
     */
    public function storeForManager(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya manager divisi yang dapat membuat tugas'
                ], 403);
            }
            
            Log::info('=== STORE TASK FOR MANAGER ===', [
                'user_id' => $user->id,
                'divisi_id' => $user->divisi_id,
                'request' => $request->all()
            ]);
            
            // Validasi manual
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|exists:projects,id',
                'judul' => 'required|string|max:255',
                'nama_tugas' => 'nullable|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'assigned_to' => 'required|exists:users,id',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'priority' => 'required|in:low,medium,high',
                'target_divisi_id' => 'required|integer',
                'catatan' => 'nullable|string',
            ]);
            
            // Validasi manual untuk divisi
            if ($request->has('target_divisi_id') && $request->target_divisi_id) {
                if (!Divisi::where('id', $request->target_divisi_id)->exists()) {
                    $validator->errors()->add('target_divisi_id', 'Divisi yang dipilih tidak valid.');
                }
            }
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
            
            // Tambahkan field yang diperlukan
            $validated['created_by'] = $user->id;
            $validated['assigned_by_manager'] = $user->id;
            $validated['target_type'] = 'karyawan';
            $validated['is_broadcast'] = false;
            
            // Jika nama_tugas kosong, gunakan judul
            if (empty($validated['nama_tugas'])) {
                $validated['nama_tugas'] = $validated['judul'];
            }
            
            // Buat task
            $task = Task::create($validated);
            
            Log::info('Task created by manager', [
                'task_id' => $task->id,
                'manager_id' => $user->id,
                'assigned_to' => $task->assigned_to,
                'divisi_id' => $task->target_divisi_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dibuat',
                'task' => $task->load(['project', 'assignee', 'creator', 'targetDivisi'])
            ]);
            
        } catch (ValidationException $e) {
            Log::error('Validation failed in storeForManager', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error in storeForManager: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat tugas: ' . $e->getMessage()
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
     * Get statistics via API - DIPERBAIKI
     */
    public function apiGetStatistics()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            if ($user->role === 'admin' || $user->role === 'general_manager') {
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
                $userDivisiId = $user->divisi_id;
                
                $stats = [
                    'total' => Task::where(function($q) use ($userDivisiId, $userId) {
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
            // Validasi manual
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|exists:projects,id',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'priority' => 'required|in:low,medium,high,urgent',
                'target_type' => 'required|in:karyawan,divisi,manager',
                'assigned_to' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
            ]);
            
            // Validasi manual untuk divisi
            if ($request->has('target_divisi_id') && $request->target_divisi_id) {
                if (!Divisi::where('id', $request->target_divisi_id)->exists()) {
                    $validator->errors()->add('target_divisi_id', 'Divisi yang dipilih tidak valid.');
                }
            }
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
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
     * Test method untuk debugging - NEW METHOD
     */
    public function testCreateTask(Request $request)
    {
        try {
            Log::info('=== TEST CREATE TASK ENDPOINT ===', $request->all());
            
            // Simpan data sederhana tanpa validasi kompleks
            $taskData = [
                'judul' => $request->judul ?? 'Test Task',
                'deskripsi' => $request->deskripsi ?? 'Test Description',
                'deadline' => $request->deadline ?? now()->addDays(7),
                'status' => 'pending',
                'priority' => 'medium',
                'created_by' => Auth::id(),
                'target_type' => 'karyawan',
                'assigned_to' => Auth::id(),
                'target_divisi_id' => 1, // Hardcode untuk testing
                'is_broadcast' => false,
            ];
            
            $task = Task::create($taskData);
            
            Log::info('Test task created', ['task_id' => $task->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test task created successfully',
                'task' => $task
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in testCreateTask: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage()
            ], 500);
        }
    }
}