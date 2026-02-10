<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment; 
use App\Models\TaskFile;
use App\Models\User;
use App\Models\Project;
use App\Models\Layanan;
use App\Models\Divisi;
use App\Models\TugasKaryawanToManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
     * Display tasks for manager divisi
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
     * Create task page
     */
    public function create()
    {
        $projects = Project::where('status_pengerjaan', '!=', 'selesai')
                          ->orderBy('nama', 'asc')
                          ->get(['id', 'nama', 'layanan_id', 'deadline']);
        
        $layanans = Layanan::orderBy('nama', 'asc')->get(['id', 'nama']);
        
        $divisis = Divisi::orderBy('divisi', 'asc')->get(['id', 'divisi']);
        
        return view('admin.tasks.create', compact('projects', 'layanans', 'divisis'));
    }
    
    /**
     * Edit task page
     */
    public function edit($id)
    {
        $task = Task::with(['assignee', 'creator', 'targetManager', 'project', 'targetDivisi'])->findOrFail($id);
        
        $projects = Project::where('status_pengerjaan', '!=', 'selesai')
                          ->orderBy('nama', 'asc')
                          ->get(['id', 'nama', 'layanan_id', 'deadline']);
        
        $divisis = Divisi::orderBy('divisi', 'asc')->get(['id', 'divisi']);
        
        return view('admin.tasks.edit', compact('task', 'projects', 'divisis'));
    }
    
    /**
     * Get tasks via API (untuk General Manager)
     */
    public function apiGetTasks()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user(); 
            $userDivisiId = $user->divisi_id;
            
            Log::info('=== GENERAL MANAGER API GET TASKS ===', [
                'user_id' => $userId,
                'user_divisi_id' => $userDivisiId,
            ]);
            
            // Menggunakan 'assignee' BUKAN 'assignedUser'
            $tasks = Task::with(['assignee', 'creator', 'targetManager', 'comments.user', 'files.uploader', 'project', 'targetDivisi'])
                ->orderBy('deadline', 'asc')
                ->get();
            
            $transformedTasks = $tasks->map(function($task) use ($userId, $userDivisiId) {
                $assigneeText = '-';
                $assigneeDivisi = '-';
                
                // Gunakan null coalescing operator untuk keamanan
                $targetType = $task->target_type ?? 'karyawan';

                if ($targetType === 'karyawan' && $task->assignee) {
                    $assigneeText = $task->assignee->name;
                    $assigneeDivisi = $task->assignee->divisi ?? '-';
                } elseif ($targetType === 'divisi') {
                    $divisiName = $task->targetDivisi ? $task->targetDivisi->divisi : '-';
                    $assigneeText = 'Divisi ' . $divisiName;
                    $assigneeDivisi = $divisiName;
                } elseif ($targetType === 'manager' && $task->targetManager) {
                    $assigneeText = 'Manager: ' . $task->targetManager->name;
                    $assigneeDivisi = $task->targetManager->divisi ?? '-';
                }
                
                $isOverdue = $task->deadline && 
                             now()->gt($task->deadline) && 
                             !in_array($task->status, ['selesai', 'dibatalkan']);
                
                $isForMe = false;
                if ($targetType === 'divisi') {
                    $isForMe = $task->target_divisi_id == $userDivisiId;
                } elseif ($targetType === 'manager' && $task->target_manager_id === $userId) {
                    $isForMe = true;
                }
                
                return [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status' => $task->status,
                    'priority' => $task->priority ?? '-',
                    'submission_file' => $task->submission_file,
                    'submission_notes' => $task->submission_notes,
                    'submitted_at' => $task->submitted_at ? $task->submitted_at->format('Y-m-d H:i:s') : null,
                    'target_type' => $targetType,
                    'assigned_to' => $task->assigned_to,
                    'created_by' => $task->created_by,
                    'target_manager_id' => $task->target_manager_id,
                    'target_divisi_id' => $task->target_divisi_id,
                    'catatan' => $task->catatan,
                    'catatan_update' => $task->catatan_update,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $task->updated_at->format('Y-m-d H:i:s'),
                    
                    'project_id' => $task->project_id,
                    'project_name' => $task->project ? $task->project->nama : null,
                    'project_deadline' => $task->project ? ($task->project->deadline ? $task->project->deadline->format('Y-m-d') : null) : null,
                    'project_status' => $task->project ? $task->project->status_pengerjaan : null,
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
                        'status' => $task->project->status_pengerjaan,
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
     * Get tasks for manager via API - FIXED RELATIONSHIP NAME
     */
    public function apiGetManagerTasks()
    {
        try {
            $user = Auth::user();
            $userId = $user->id;
            $userDivisiId = $user->divisi_id;
            
            Log::info('=== MANAGER DIVISI API GET TASKS ===', [
                'user_id' => $userId,
                'user_divisi_id' => $userDivisiId,
            ]);
            
            // PERBAIKAN: Menggunakan 'assignee' bukan 'assignedUser'
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
            
            // BARU: Ambil juga task dari karyawan (TugasKaryawanToManager)
            $tasksFromKaryawan = TugasKaryawanToManager::with(['karyawan:id,name,email', 'project:id,nama,progres,status_pengerjaan'])
                ->where('manager_divisi_id', $user->id)
                ->whereNull('deleted_at')
                ->orderBy('deadline', 'asc')
                ->get();
            
            $transformedTasks = $tasks->map(function($task) use ($user, $userId) {
                $assigneeText = '-';
                $assigneeDivisi = '-';
                
                // Gunakan null coalescing operator
                $targetType = $task->target_type ?? 'karyawan';

                if ($targetType === 'karyawan' && $task->assignee) {
                    $assigneeText = $task->assignee->name;
                    $assigneeDivisi = $task->assignee->divisi ?? '-';
                } elseif ($targetType === 'divisi') {
                    $divisiName = $task->targetDivisi ? $task->targetDivisi->divisi : '-';
                    $assigneeText = 'Divisi ' . $divisiName;
                    $assigneeDivisi = $divisiName;
                } elseif ($targetType === 'manager' && $task->targetManager) {
                    $assigneeText = 'Manager: ' . $task->targetManager->name;
                    $assigneeDivisi = $task->targetManager->divisi ?? '-';
                }
                
                $isOverdue = $task->deadline && 
                             now()->gt($task->deadline) && 
                             !in_array($task->status, ['selesai', 'dibatalkan']);
                
                $isForMe = false;
                if ($task->assigned_to === $userId) {
                    $isForMe = true;
                } elseif ($targetType === 'divisi' && $task->target_divisi_id == $user->divisi_id) {
                    $isForMe = true;
                }
                
                return [
                    'id' => $task->id,
                    'type' => 'task', // Tanda bahwa ini task biasa
                    'judul' => $task->judul,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status' => $task->status,
                    'priority' => $task->priority ?? '-',
                    'submission_file' => $task->submission_file,
                    'submission_notes' => $task->submission_notes,
                    'submitted_at' => $task->submitted_at ? $task->submitted_at->format('Y-m-d H:i:s') : null,
                    'target_type' => $targetType,
                    'assigned_to' => $task->assigned_to,
                    'created_by' => $task->created_by,
                    'target_manager_id' => $task->target_manager_id,
                    'target_divisi_id' => $task->target_divisi_id,
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
            
            // Transform tasks dari karyawan
            $transformedTasksFromKaryawan = $tasksFromKaryawan->map(function($karyawanTask) {
                $isOverdue = $karyawanTask->deadline && 
                             now()->gt($karyawanTask->deadline) && 
                             !in_array($karyawanTask->status, ['selesai', 'dibatalkan']);
                
                return [
                    'id' => $karyawanTask->id,
                    'type' => 'task_from_karyawan', // Tanda bahwa ini dari karyawan
                    'judul' => $karyawanTask->judul,
                    'deskripsi' => $karyawanTask->deskripsi,
                    'deadline' => $karyawanTask->deadline ? $karyawanTask->deadline->format('Y-m-d H:i:s') : null,
                    'status' => $karyawanTask->status,
                    'priority' => $karyawanTask->priority ?? '-',
                    'submission_file' => $karyawanTask->lampiran,
                    'submission_notes' => $karyawanTask->catatan,
                    'submitted_at' => $karyawanTask->created_at ? $karyawanTask->created_at->format('Y-m-d H:i:s') : null,
                    'target_type' => 'karyawan',
                    'assigned_to' => $karyawanTask->karyawan_id,
                    'created_by' => null,
                    'target_manager_id' => null,
                    'target_divisi_id' => null,
                    'catatan' => $karyawanTask->catatan,
                    'catatan_update' => null,
                    'completed_at' => null,
                    'created_at' => $karyawanTask->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $karyawanTask->updated_at->format('Y-m-d H:i:s'),
                    
                    'project_id' => $karyawanTask->project_id,
                    'project_name' => $karyawanTask->project ? $karyawanTask->project->nama : null,
                    'project_progress' => $karyawanTask->project ? $karyawanTask->project->progres : null,
                    
                    'assignee_text' => $karyawanTask->karyawan ? $karyawanTask->karyawan->name : 'Unknown',
                    'assignee_divisi' => '-',
                    'creator_name' => $karyawanTask->karyawan ? $karyawanTask->karyawan->name : 'Unknown',
                    'is_overdue' => $isOverdue,
                    'is_for_me' => false,
                    'has_submission' => !is_null($karyawanTask->lampiran),
                    'submission_url' => $karyawanTask->lampiran ? Storage::url($karyawanTask->lampiran) : null,
                    'comments_count' => 0,
                    'files_count' => !is_null($karyawanTask->lampiran) ? 1 : 0,
                    
                    'assignee' => $karyawanTask->karyawan ? [
                        'id' => $karyawanTask->karyawan->id,
                        'name' => $karyawanTask->karyawan->name,
                        'divisi' => '-'
                    ] : null,
                    'creator' => $karyawanTask->karyawan ? [
                        'id' => $karyawanTask->karyawan->id,
                        'name' => $karyawanTask->karyawan->name
                    ] : null,
                    'target_manager' => null,
                    'target_divisi' => null,
                    'project' => $karyawanTask->project ? [
                        'id' => $karyawanTask->project->id,
                        'nama' => $karyawanTask->project->nama,
                        'progres' => $karyawanTask->project->progres,
                        'status' => $karyawanTask->project->status_pengerjaan
                    ] : null,
                ];
            });
            
            // Gabungkan kedua collection dan sort by deadline
            $allTasks = $transformedTasks->merge($transformedTasksFromKaryawan)
                ->sortBy('deadline')
                ->values();
            
            Log::info('Manager tasks loaded successfully', [
                'total_tasks' => $allTasks->count(),
                'regular_tasks' => $transformedTasks->count(),
                'karyawan_tasks' => $transformedTasksFromKaryawan->count(),
                'user_id' => $userId,
                'divisi_id' => $userDivisiId
            ]);
            
            return response()->json($allTasks);
            
        } catch (\Exception $e) {
            Log::error('Error in apiGetManagerTasks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load manager tasks: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store a new task (ADMIN)
     */
    public function store(Request $request)
    {
        try {
            Log::info('=== STORE TASK REQUEST ===', $request->all());
            
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'target_type' => 'required|in:karyawan,divisi,manager',
                'assigned_to' => 'nullable|exists:users,id',
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
                'project_id' => 'nullable|exists:project,id',
                // 'kategori' tidak divalidasi karena mungkin tidak ada di DB
                'nama_tugas' => 'nullable|string|max:255',
                'target_divisi_id' => 'nullable|integer|exists:divisi,id',
            ]);
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
            $validated['created_by'] = Auth::id();
            
            $validated['status'] = $validated['status'] ?? 'pending';
            $validated['nama_tugas'] = $validated['nama_tugas'] ?? $validated['judul'];
            
            // Logic divisi
            if ($validated['target_type'] === 'divisi' && !empty($validated['target_divisi_id'])) {
                // Cari manager divisi untuk auto-assign
                $manager = User::where('role', 'manager_divisi')
                              ->where('divisi_id', $validated['target_divisi_id'])
                              ->first();
                
                if ($manager) {
                    $validated['target_manager_id'] = $manager->id;
                    $validated['assigned_to'] = $manager->id;
                }
            }
            
            // Hapus kategori dari input agar tidak error jika kolom tidak ada
            unset($validated['kategori']);
            
            Log::info('Creating task with data:', $validated);
            
            $task = Task::create($validated);
            
            Log::info('Task created successfully', [
                'task_id' => $task->id,
                'created_by' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dibuat',
                'task' => $task->load(['project', 'assignee', 'creator', 'targetDivisi'])
            ]);
            
        } catch (ValidationException $e) {
            Log::error('Validation failed in store', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error in store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat tugas: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update task
     */
    public function update(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'assigned_to' => 'nullable|exists:users,id',
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
                'project_id' => 'nullable|exists:project,id',
                'kategori' => 'nullable|string|max:100',
            ]);
            
            if ($request->has('target_divisi_id') && $request->target_divisi_id) {
                if (!Divisi::where('id', $request->target_divisi_id)->exists()) {
                    $validator->errors()->add('target_divisi_id', 'Divisi yang dipilih tidak valid.');
                }
            }
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
            
            // Hapus kategori jika tidak ada di DB
            unset($validated['kategori']);

            if ($task->status !== $validated['status']) {
                $validated['catatan_update'] = "Status diubah dari {$task->status} menjadi {$validated['status']} oleh " . Auth::user()->name;
                
                if ($validated['status'] === 'selesai' && !$task->completed_at) {
                    $validated['completed_at'] = now();
                }
            }
            
            Log::info('Updating task', [
                'task_id' => $id,
                'old_status' => $task->status,
                'new_status' => $validated['status']
            ]);
            
            $task->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diperbarui',
                'task' => $task->fresh(['project', 'assignee', 'creator', 'targetDivisi'])
            ]);
            
        } catch (ValidationException $e) {
            Log::error('Validation failed in update', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error in update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui tugas: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store task khusus untuk manager divisi - FIXED CLEAN VERSION
     */
    public function storeForManager(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            
            Log::info('=== STORE FOR MANAGER START ===', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'request_data' => $request->all()
            ]);
            
            if ($user->role !== 'manager_divisi') {
                Log::warning('User is not manager_divisi', ['user_role' => $user->role]);
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya manager divisi yang dapat membuat tugas'
                ], 403);
            }
            
            // Validasi
            $validator = Validator::make($request->all(), [
                'project_id'        => 'required|exists:project,id',
                'judul'             => 'required|string|max:255',
                'nama_tugas'        => 'nullable|string|max:255',
                'deskripsi'         => 'required|string',
                'deadline'          => 'required|date',
                'assigned_to'       => 'required|exists:users,id',
                'status'            => 'required|in:pending,proses,selesai,dibatalkan',
                'target_divisi_id'  => 'required|integer',
                'catatan'           => 'nullable|string',
            ]);
            
            // Validasi manual untuk divisi
            if ($request->has('target_divisi_id') && $request->target_divisi_id) {
                if (!Divisi::where('id', $request->target_divisi_id)->exists()) {
                    $validator->errors()->add('target_divisi_id', 'Divisi yang dipilih tidak valid.');
                }
            }
            
            if ($validator->fails()) {
                Log::error('Validation failed', ['errors' => $validator->errors()->all()]);
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
            Log::info('Validation passed', ['validated_data' => $validated]);
            
            // Data yang akan disimpan
            $taskData = [
                'project_id'          => $validated['project_id'],
                'judul'               => $validated['judul'],
                'nama_tugas'          => $validated['nama_tugas'] ?? $validated['judul'],
                'deskripsi'           => $validated['deskripsi'],
                'deadline'            => $validated['deadline'],
                'assigned_to'         => $validated['assigned_to'],
                'status'              => $validated['status'],
                'target_divisi_id'    => $validated['target_divisi_id'],
                'catatan'             => $validated['catatan'] ?? null,
                'created_by'          => $user->id,
                'assigned_by_manager' => $user->id,
                
                // Kolom ini dipertahankan
                'target_type'         => 'karyawan',
                
                // is_broadcast SUDAH DIHAPUS
            ];
            
            Log::info('Task data prepared', ['task_data' => $taskData]);
            
            // Buat task
            $task = Task::create($taskData);
            
            Log::info('Task created successfully', [
                'task_id' => $task->id,
                'manager_id' => $user->id
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dibuat',
                'task' => $task->load(['project', 'assignee', 'creator', 'targetDivisi'])
            ]);
            
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation failed in storeForManager', [
                'errors' => $e->errors(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in storeForManager: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
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
     * Get statistics via API
     */
    public function apiGetStatistics()
    {
        try {
            $userId = Auth::id(); 
            $user = Auth::user();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }

            $scopeQuery = function($query) use ($user, $userId) {
                if ($user->role === 'admin' || $user->role === 'general_manager') {
                    return;
                } elseif ($user->role === 'manager_divisi') {
                    $userDivisiId = $user->divisi_id;
                    $query->where(function($q) use ($userDivisiId, $userId) {
                        $q->where('target_divisi_id', $userDivisiId)
                          ->orWhere('created_by', $userId)
                          ->orWhere('assigned_to', $userId)
                          ->orWhere('assigned_by_manager', $userId);
                    });
                } else {
                    $query->where('assigned_to', $userId);
                }
            };

            $getCount = function($condition) use ($scopeQuery) {
                $query = Task::where($scopeQuery);
                if (is_callable($condition)) {
                    $condition($query);
                }
                try {
                    return $query->count();
                } catch (\Exception $e) {
                    Log::warning("Stat query failed: " . $e->getMessage());
                    return 0;
                }
            };

            $stats = [
                'total' => $getCount(function(){}),
                'pending' => $getCount(function($q){ $q->where('status', 'pending'); }),
                'in_progress' => $getCount(function($q){ $q->where('status', 'proses'); }),
                'completed' => $getCount(function($q){ $q->where('status', 'selesai'); }),
                'cancelled' => $getCount(function($q){ $q->where('status', 'dibatalkan'); }),
                'with_submission' => $getCount(function($q){ 
                    try { $q->whereNotNull('submission_file'); } catch(\Exception $e) {}
                }),
                'overdue' => $getCount(function($q){ 
                    $q->where('deadline', '<', now())
                      ->whereNotIn('status', ['selesai', 'dibatalkan']); 
                }),
                'with_project' => $getCount(function($q){ $q->whereNotNull('project_id'); }),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Critical Error in apiGetStatistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create task from project (API)
     */
    public function createTaskFromProject(Request $request)
    {
        try {
            Log::info('=== CREATE TASK FROM PROJECT ===', $request->all());
            
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|exists:project,id',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'target_type' => 'required|in:karyawan,divisi,manager',
                'assigned_to' => 'nullable|exists:users,id',
                'target_divisi_id' => 'nullable|integer',
                'catatan' => 'nullable|string',
            ]);
            
            if ($validator->fails()) {
                Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
            $project = Project::findOrFail($validated['project_id']);
            
            $validated['created_by'] = Auth::id();
            $validated['status'] = 'pending';
            
            // Logika Divisi tanpa is_broadcast
            if ($validated['target_type'] === 'divisi') {
                if (!empty($validated['target_divisi_id'])) {
                    $manager = User::where('role', 'manager_divisi')
                                  ->where('divisi_id', $validated['target_divisi_id'])
                                  ->first();
                    if ($manager) {
                        $validated['target_manager_id'] = $manager->id;
                        $validated['assigned_to'] = $manager->id;
                    }
                }
            }
            
            $task = Task::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dibuat dari proyek',
                'task' => $task->load(['project', 'assignee'])
            ]);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error creating task from project: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat tugas: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Karyawan tasks
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
     * Get task detail via API
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
                    'priority' => $task->priority ?? '-',
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
     * Test method untuk debugging
     */
    public function testCreateTask(Request $request)
    {
        try {
            Log::info('=== TEST CREATE TASK ENDPOINT ===', $request->all());
            
            $taskData = [
                'judul' => $request->judul ?? 'Test Task',
                'deskripsi' => $request->deskripsi ?? 'Test Description',
                'deadline' => $request->deadline ?? now()->addDays(7),
                'status' => 'pending',
                'created_by' => Auth::id(),
                'target_type' => 'karyawan',
                'assigned_to' => Auth::id(),
                'target_divisi_id' => 1,
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

    /**
     * Upload file tugas dari karyawan ke manager divisi
     * POST /api/tasks/{id}/upload
     */
    public function uploadTaskFile(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            // Validate user is karyawan
            if ($user->role !== 'karyawan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya karyawan yang dapat mengupload tugas'
                ], 403);
            }

            // Validasi input
            $validated = $request->validate([
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip,rar|max:10240',
                'notes' => 'nullable|string|max:1000'
            ]);

            // Get task detail
            $task = Task::find($id);
            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }

            // Check if task is assigned to this user
            if ($task->assigned_to !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengupload tugas ini'
                ], 403);
            }

            // Get manager divisi for this task (from target_divisi_id or assigned_by_manager)
            $manager = null;
            if ($task->assigned_by_manager) {
                $manager = User::find($task->assigned_by_manager);
            } elseif ($task->target_divisi_id) {
                $manager = User::where('divisi_id', $task->target_divisi_id)
                    ->where('role', 'manager_divisi')
                    ->first();
            }

            if (!$manager) {
                return response()->json([
                    'success' => false,
                    'message' => 'Manager divisi tidak ditemukan untuk tugas ini'
                ], 404);
            }

            // Handle file upload
            $file = $request->file('file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('tugas_karyawan', $fileName, 'public');

            // Create or update TugasKaryawanToManager record
            $tugasKaryawan = TugasKaryawanToManager::updateOrCreate(
                [
                    'karyawan_id' => $user->id,
                    'manager_divisi_id' => $manager->id,
                    'project_id' => $task->project_id,
                    'judul' => $task->judul
                ],
                [
                    'nama_tugas' => $task->nama_tugas,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => $task->deadline,
                    'status' => 'submitted',
                    'catatan' => $request->input('notes'),
                    'lampiran' => $filePath
                ]
            );

            Log::info('Task uploaded by karyawan', [
                'karyawan_id' => $user->id,
                'task_id' => $id,
                'manager_id' => $manager->id,
                'file_path' => $filePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diupload',
                'data' => [
                    'id' => $tugasKaryawan->id,
                    'lampiran' => $tugasKaryawan->lampiran,
                    'status' => $tugasKaryawan->status
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error uploading task file: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'task_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all files from a task
     */
    public function getTaskFiles($id)
    {
        try {
            $task = Task::findOrFail($id);
            
            // Check authorization
            $user = Auth::user();
            if (!in_array($user->role, ['admin', 'general_manager', 'manager_divisi'])) {
                if ($task->assigned_to !== $user->id && $task->created_by !== $user->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized'
                    ], 403);
                }
            }
            
            $files = $task->files()
                ->with('uploader:id,name,email')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($file) {
                    return [
                        'id' => $file->id,
                        'filename' => $file->filename,
                        'name' => $file->filename,
                        'path' => Storage::url($file->path),
                        'url' => Storage::url($file->path),
                        'size' => $file->size,
                        'mime_type' => $file->mime_type,
                        'uploaded_by' => $file->uploader ? $file->uploader->name : 'Unknown',
                        'uploaded_at' => $file->created_at->format('Y-m-d H:i:s')
                    ];
                });
            
            return response()->json([
                'success' => true,
                'files' => $files
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting task files: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat file'
            ], 500);
        }
    }
}