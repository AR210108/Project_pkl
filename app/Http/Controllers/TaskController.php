<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use App\Models\TaskFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display list of tasks for the logged-in user (Admin)
     */
    public function index()
    {
        try {
            $tasks = Task::with(['assignedUser', 'creator', 'targetManager', 'comments.user', 'files.uploader'])
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
     * Create task page
     */
    public function create()
    {
        return view('admin.tasks.create');
    }
    
    /**
     * Edit task page
     */
    public function edit($id)
    {
        $task = Task::with(['assignedUser', 'creator', 'targetManager'])->findOrFail($id);
        return view('admin.tasks.edit', compact('task'));
    }
    
    /**
     * Get tasks via API (untuk General Manager)
     */
    public function apiGetTasks()
    {
        try {
            $userId = Auth::id();
            $userDivisi = Auth::user()->divisi;
            
            Log::info('=== GENERAL MANAGER API GET TASKS ===', [
                'user_id' => $userId,
                'user_divisi' => $userDivisi,
            ]);
            
            $tasks = Task::with(['assignedUser', 'creator', 'targetManager', 'comments.user', 'files.uploader'])
                ->orderBy('deadline', 'asc')
                ->get();
            
            $transformedTasks = $tasks->map(function($task) use ($userId, $userDivisi) {
                // Tentukan assignee text
                $assigneeText = '-';
                $assigneeDivisi = '-';
                
                if ($task->target_type === 'karyawan' && $task->assignedUser) {
                    $assigneeText = $task->assignedUser->name;
                    $assigneeDivisi = $task->assignedUser->divisi ?? '-';
                } elseif ($task->target_type === 'divisi') {
                    $assigneeText = 'Divisi ' . $task->target_divisi;
                    $assigneeDivisi = $task->target_divisi ?? '-';
                } elseif ($task->target_type === 'manager' && $task->targetManager) {
                    $assigneeText = 'Manajer: ' . $task->targetManager->name;
                    $assigneeDivisi = $task->targetManager->divisi ?? '-';
                }
                
                // Check if overdue
                $isOverdue = $task->deadline && 
                             now()->gt($task->deadline) && 
                             !in_array($task->status, ['selesai', 'dibatalkan']);
                
                // Tentukan apakah task ini untuk user yang login
                $isForMe = false;
                if ($task->target_type === 'divisi') {
                    $isForMe = strtolower($task->target_divisi) === strtolower($userDivisi);
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
                    'target_divisi' => $task->target_divisi,
                    'is_broadcast' => $task->is_broadcast,
                    'catatan' => $task->catatan,
                    'catatan_update' => $task->catatan_update,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $task->updated_at->format('Y-m-d H:i:s'),
                    
                    // Computed fields untuk frontend
                    'assignee_text' => $assigneeText,
                    'assignee_divisi' => $assigneeDivisi,
                    'creator_name' => $task->creator->name ?? '-',
                    'is_overdue' => $isOverdue,
                    'is_for_me' => $isForMe,
                    'has_submission' => !is_null($task->submission_file),
                    'submission_url' => $task->submission_file ? Storage::url($task->submission_file) : null,
                    'comments_count' => $task->comments_count,
                    'files_count' => $task->files_count,
                    
                    // Relasi objects
                    'assignee' => $task->assignedUser ? [
                        'id' => $task->assignedUser->id,
                        'name' => $task->assignedUser->name,
                        'divisi' => $task->assignedUser->divisi ?? '-'
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
     * Get task statistics via API
     */
    public function apiGetStatistics()
    {
        try {
            $userId = Auth::id();
            
            $stats = [
                'total' => Task::count(),
                'pending' => Task::where('status', 'pending')->count(),
                'in_progress' => Task::where('status', 'proses')->count(),
                'completed' => Task::where('status', 'selesai')->count(),
                'cancelled' => Task::where('status', 'dibatalkan')->count(),
                'with_submission' => Task::whereNotNull('submission_file')->count(),
            ];
            
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
     * Store a new task
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'priority' => 'required|in:low,medium,high',
                'target_type' => 'required|in:karyawan,divisi,manager',
                'assigned_to' => 'nullable|exists:users,id',
                'target_divisi' => 'nullable|string',
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
            ]);
            
            $validated['created_by'] = Auth::id();
            
            // PERBAIKAN: Tambahkan auto-assignment untuk tugas divisi
            if ($validated['target_type'] === 'divisi') {
                $validated['is_broadcast'] = true;
                
                // Auto assign ke manager divisi jika ada
                if ($validated['target_divisi']) {
                    $manager = User::where('role', 'manager_divisi')
                                  ->where('divisi', $validated['target_divisi'])
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
            
            $task = Task::create($validated);
            
            Log::info('Task created successfully', ['task_id' => $task->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task
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
     * Get task detail - PERBAIKAN: Hapus toArrayWithRelations()
     */
    public function show($id)
    {
        try {
            $task = Task::with(['assignedUser', 'creator', 'targetManager', 'comments.user', 'files.uploader'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'task' => $task // PERBAIKAN: Hapus ->toArrayWithRelations()
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
     * Update task
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
                'priority' => 'required|in:low,medium,high',
                'assigned_to' => 'nullable|exists:users,id',
                'target_divisi' => 'nullable|string',
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
            ]);
            
            // PERBAIKAN: Update is_broadcast dengan auto assign
            if (isset($validated['target_divisi']) && !empty($validated['target_divisi'])) {
                $validated['is_broadcast'] = true;
                
                // Auto assign ke manager divisi jika belum diassign
                if (!isset($validated['assigned_to']) || empty($validated['assigned_to'])) {
                    $manager = User::where('role', 'manager_divisi')
                                  ->where('divisi', $validated['target_divisi'])
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
            
            $task->update($validated);
            
            Log::info('Task updated successfully', ['task_id' => $task->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $task->fresh()
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
     * Delete task
     */
    public function destroy($id)
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
            
            $task->delete();
            
            Log::info('Task deleted successfully', ['task_id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========== KARYAWAN TASK MANAGEMENT ==========
     */

    /**
     * Display list of tasks for karyawan
     */
    public function karyawanTasks(Request $request)
    {
        try {
            $userId = Auth::id();
            
            $filters = [
                'status' => $request->get('status', 'all'),
                'search' => $request->get('search', ''),
            ];
            
            $tasks = Task::getTasksForKaryawan($userId, $filters);

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
     * Get task detail via API (untuk karyawan modal)
     */
    public function getTaskDetailApi($taskId)
    {
        try {
            $task = Task::with(['creator', 'assigner', 'comments.user', 'files.uploader'])
                    ->where('assigned_to', Auth::id())
                    ->orWhere('created_by', Auth::id())
                    ->findOrFail($taskId);

            return response()->json([
                'success' => true,
                'task' => [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'deskripsi' => $task->deskripsi ?? 'Tidak ada deskripsi',
                    'status' => $task->status,
                    'priority' => $task->priority ?? 'medium',
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'deadline_formatted' => $task->deadline ? 
                        $task->deadline->translatedFormat('l, d F Y H:i') . ' WIB' : 
                        'Tidak ada deadline',
                    'assigned_by' => $task->assigner_name,
                    'is_overdue' => $task->is_overdue,
                    'submission_file' => $task->submission_file,
                    'submission_notes' => $task->submission_notes,
                    'submitted_at' => $task->submitted_at ? $task->submitted_at->format('Y-m-d H:i:s') : null,
                    'submitted_at_formatted' => $task->submitted_at ? 
                        $task->submitted_at->translatedFormat('d F Y H:i') : null,
                    'submission_url' => $task->submission_file ? Storage::url($task->submission_file) : null,
                    'has_submission' => $task->has_submission,
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
                            'size' => $file->formatted_size,
                            'mime_type' => $file->mime_type,
                            'icon' => $file->file_icon,
                            'uploaded_at' => $file->uploaded_at->format('d M Y H:i'),
                            'uploaded_by' => $file->uploader->name ?? 'Unknown',
                            'is_image' => $file->is_image,
                            'is_document' => $file->is_document,
                            'download_url' => route('api.tasks.files.download', ['file' => $file->id]),
                            'preview_url' => $file->preview_url,
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
     * ========== COMMENT MANAGEMENT ==========
     */

    /**
     * Get comments for task
     */
    public function getComments($taskId)
    {
        try {
            $task = Task::where('assigned_to', Auth::id())
                    ->orWhere('created_by', Auth::id())
                    ->findOrFail($taskId);

            $comments = $task->comments()
                            ->with('user')
                            ->orderBy('created_at', 'asc')
                            ->get()
                            ->map(function($comment) {
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
                            });

            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting comments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load comments'
            ], 500);
        }
    }

    /**
     * Store comment for task
     */
    public function storeComment(Request $request, $taskId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|min:1|max:2000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $task = Task::where('assigned_to', Auth::id())
                    ->orWhere('created_by', Auth::id())
                    ->findOrFail($taskId);

            $comment = $task->addComment($request->content, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->format('d M Y H:i'),
                    'time_ago' => $comment->created_at->diffForHumans(),
                    'user' => [
                        'id' => Auth::id(),
                        'name' => Auth::user()->name,
                        'role' => Auth::user()->role ?? 'Karyawan',
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error storing comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========== FILE SUBMISSION MANAGEMENT ==========
     */

    /**
     * Upload file dan tandai task sebagai selesai (untuk karyawan)
     * Endpoint: POST /api/tasks/{taskId}/upload-file
     */
    public function uploadTaskFile(Request $request, $taskId)
    {
        \Log::info('=== START UPLOAD TASK FILE ===');
        \Log::info('Task ID:', ['id' => $taskId]);
        \Log::info('User ID:', ['id' => Auth::id()]);
        \Log::info('Request Data:', $request->all());
        
        try {
            $validator = Validator::make($request->all(), [
                'file' => [
                    'required',
                    'file',
                    'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,txt,zip,rar',
                    'max:10240' // 10MB
                ],
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                \Log::error('VALIDATION FAILED:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cari task
            $task = Task::where('assigned_to', Auth::id())
                    ->find($taskId);

            if (!$task) {
                \Log::error('Task not found or not assigned to user', [
                    'task_id' => $taskId,
                    'user_id' => Auth::id()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas tidak ditemukan atau Anda tidak diizinkan mengaksesnya'
                ], 404);
            }

            // Cek jika tugas sudah selesai
            if ($task->status === 'selesai') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas sudah selesai sebelumnya'
                ], 400);
            }

            // Upload file
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . str_replace(' ', '_', $originalName);
            
            // Simpan file ke storage
            $path = $file->storeAs('task_submissions', $filename, 'public');
            
            \Log::info('File stored successfully:', ['path' => $path]);

            // Simpan ke task_files
            $taskFile = TaskFile::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'filename' => $filename,
                'original_name' => $originalName,
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'description' => $request->notes,
                'uploaded_at' => now(),
            ]);

            \Log::info('TaskFile created:', ['id' => $taskFile->id]);

            // Update task dengan submission
            $task->update([
                'status' => Task::STATUS_SELESAI,
                'submission_file' => $path,
                'submission_notes' => $request->notes,
                'submitted_at' => now(),
                'completed_at' => now(),
            ]);

            // Tambahkan komentar otomatis
            $task->addComment(
                "✅ **Telah mengirimkan file hasil tugas**\n" .
                "📄 **File:** " . $originalName . 
                ($request->notes ? "\n📝 **Catatan:** " . $request->notes : '') .
                "\n⏰ **Waktu:** " . now()->translatedFormat('d F Y H:i'),
                Auth::id()
            );

            \Log::info('Task updated with submission:', [
                'task_id' => $task->id,
                'status' => $task->status,
                'submission_file' => $task->submission_file
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload dan tugas ditandai sebagai selesai',
                'task' => [
                    'id' => $task->id,
                    'status' => $task->status,
                    'status_label' => $task->status_label,
                    'submission_file' => $task->submission_file,
                    'submission_url' => Storage::url($task->submission_file),
                    'submitted_at' => $task->submitted_at ? $task->submitted_at->format('Y-m-d H:i:s') : null,
                    'has_submission' => $task->has_submission,
                ],
                'file' => [
                    'id' => $taskFile->id,
                    'filename' => $taskFile->original_name,
                    'size' => $this->formatBytes($taskFile->size),
                    'download_url' => route('api.tasks.files.download', ['file' => $taskFile->id]),
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Exception:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error uploading task file:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tandai task sebagai selesai tanpa file (untuk karyawan)
     */
    public function markAsComplete(Request $request, $taskId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $task = Task::where('assigned_to', Auth::id())
                    ->findOrFail($taskId);

            if ($task->status === 'selesai') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas sudah selesai sebelumnya'
                ], 400);
            }

            // Update task
            $task->submitWithoutFile($request->notes);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil ditandai sebagai selesai',
                'task' => [
                    'id' => $task->id,
                    'status' => $task->status,
                    'submitted_at' => $task->submitted_at ? $task->submitted_at->format('Y-m-d H:i:s') : null,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error marking task as complete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========== FILE MANAGEMENT ==========
     */

    /**
     * Download file dari task
     */
    public function downloadFile($taskId, $fileId)
    {
        try {
            $task = Task::where('assigned_to', Auth::id())
                    ->orWhere('created_by', Auth::id())
                    ->findOrFail($taskId);

            $file = TaskFile::where('task_id', $task->id)
                    ->where('id', $fileId)
                    ->firstOrFail();

            $path = storage_path('app/public/' . $file->path);
            
            if (!file_exists($path)) {
                abort(404, 'File tidak ditemukan');
            }

            return response()->download($path, $file->original_name);
            
        } catch (\Exception $e) {
            Log::error('Error downloading file: ' . $e->getMessage());
            abort(404, 'File tidak ditemukan');
        }
    }

    /**
     * Download single file by file ID (for API)
     */
    public function downloadFileById($fileId)
    {
        try {
            $file = TaskFile::findOrFail($fileId);
            
            // Cek permission - hanya user yang terhubung dengan task bisa download
            $task = $file->task;
            $userId = Auth::id();
            
            if ($task->assigned_to !== $userId && $task->created_by !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengakses file ini'
                ], 403);
            }

            $path = storage_path('app/public/' . $file->path);
            
            if (!file_exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan di server'
                ], 404);
            }

            return response()->download($path, $file->original_name);
            
        } catch (\Exception $e) {
            Log::error('Error downloading file by ID: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Download submission file utama
     */
    public function downloadSubmission($taskId)
    {
        try {
            $task = Task::where('assigned_to', Auth::id())
                    ->orWhere('created_by', Auth::id())
                    ->findOrFail($taskId);

            if (!$task->submission_file) {
                abort(404, 'File tidak ditemukan');
            }

            $path = storage_path('app/public/' . $task->submission_file);
            
            if (!file_exists($path)) {
                abort(404, 'File tidak ditemukan');
            }

            // Extract filename from path
            $filename = basename($task->submission_file);
            
            return response()->download($path, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error downloading submission: ' . $e->getMessage());
            abort(404, 'File tidak ditemukan');
        }
    }

    /**
     * Get all files for a task
     */
    public function getTaskFiles($taskId)
    {
        try {
            $task = Task::where('assigned_to', Auth::id())
                    ->orWhere('created_by', Auth::id())
                    ->findOrFail($taskId);

            $files = TaskFile::where('task_id', $taskId)
                ->with('uploader')
                ->orderBy('uploaded_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'files' => $files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'filename' => $file->filename,
                        'original_name' => $file->original_name,
                        'size' => $file->formatted_size,
                        'mime_type' => $file->mime_type,
                        'uploaded_by' => $file->uploader->name ?? 'Unknown',
                        'uploaded_at' => $file->uploaded_at->format('d M Y H:i'),
                        'download_url' => route('api.tasks.files.download', ['file' => $file->id]),
                        'is_image' => $file->is_image,
                        'is_document' => $file->is_document,
                        'icon' => $file->file_icon,
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting task files: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar file'
            ], 500);
        }
    }

    /**
     * Delete file
     */
    public function deleteFile($fileId)
    {
        try {
            $file = TaskFile::findOrFail($fileId);
            
            // Cek permission
            $userId = Auth::id();
            if ($file->user_id !== $userId && $file->task->created_by !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus file ini'
                ], 403);
            }
            
            // Hapus file dari storage
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            
            // Hapus dari database
            $file->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file'
            ], 500);
        }
    }

    /**
     * ========== STATISTICS ==========
     */

    /**
     * Get statistics for karyawan
     */
    public function getKaryawanStatistics()
    {
        try {
            $userId = Auth::id();
            
            $stats = Task::getStatisticsForUser($userId);
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            Log::error('Error getting karyawan statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * Get statistics (global)
     */
    public function getStatistics()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            $stats = [
                'total' => Task::count(),
                'pending' => Task::where('status', 'pending')->count(),
                'in_progress' => Task::where('status', 'proses')->count(),
                'completed' => Task::where('status', 'selesai')->count(),
                'cancelled' => Task::where('status', 'dibatalkan')->count(),
                'with_submission' => Task::whereNotNull('submission_file')->count(),
            ];
            
            // Tambahkan stats khusus untuk karyawan
            if ($user->role === 'karyawan') {
                $stats['my_total'] = Task::where('assigned_to', $userId)->count();
                $stats['my_pending'] = Task::where('assigned_to', $userId)->where('status', 'pending')->count();
                $stats['my_in_progress'] = Task::where('assigned_to', $userId)->where('status', 'proses')->count();
                $stats['my_completed'] = Task::where('assigned_to', $userId)->where('status', 'selesai')->count();
                $stats['my_overdue'] = Task::where('assigned_to', $userId)
                    ->where('deadline', '<', now())
                    ->whereNotIn('status', ['selesai', 'dibatalkan'])
                    ->count();
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
     * Update task status (for karyawan)
     */
    public function updateTaskStatus(Request $request, $taskId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $task = Task::where('assigned_to', Auth::id())
                    ->findOrFail($taskId);

            $oldStatus = $task->status;
            
            $task->updateStatus($request->status);

            // Tambahkan komentar untuk perubahan status
            if ($oldStatus !== $request->status) {
                $statusLabels = [
                    'pending' => 'Pending',
                    'proses' => 'Dalam Proses',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ];
                
                $commentContent = "🔄 **Status tugas diubah menjadi: " . 
                                ($statusLabels[$request->status] ?? $request->status) . "**" .
                                ($request->notes ? "\n📝 **Catatan:** " . $request->notes : '');
                
                $task->addComment($commentContent, Auth::id());
            }

            return response()->json([
                'success' => true,
                'message' => 'Status tugas berhasil diperbarui',
                'task' => [
                    'id' => $task->id,
                    'status' => $task->status,
                    'status_label' => $task->status_label,
                    'completed_at' => $task->completed_at,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating task status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload file untuk task (Admin)
     */
    public function uploadFileAdmin(Request $request, $id)
    {
        try {
            \Log::info('Admin upload file for task:', [
                'task_id' => $id,
                'user_id' => Auth::id(),
                'has_file' => $request->hasFile('file')
            ]);
            
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:10240',
                'description' => 'nullable|string|max:500',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $task = Task::findOrFail($id);
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $filename = time() . '_' . str_replace(' ', '_', $originalName);
                
                $path = $file->storeAs('task_files', $filename, 'public');
                
                $taskFile = TaskFile::create([
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                    'filename' => $filename,
                    'original_name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'description' => $request->description,
                    'uploaded_at' => now(),
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'File berhasil diupload',
                    'file' => $taskFile
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan'
            ], 400);
            
        } catch (\Exception $e) {
            \Log::error('Admin upload error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show task for karyawan (view)
     */
    public function karyawanShow($id)
    {
        try {
            $task = Task::with(['creator', 'assigner', 'comments.user', 'files.uploader'])
                    ->where('assigned_to', Auth::id())
                    ->findOrFail($id);
            
            return view('karyawan.tugas_detail', compact('task'));
            
        } catch (\Exception $e) {
            Log::error('Error showing task for karyawan: ' . $e->getMessage());
            abort(404, 'Tugas tidak ditemukan');
        }
    }

    /**
     * Get task API (for any role)
     */
    public function getTaskApi($id)
    {
        try {
            $task = Task::with(['assignedUser', 'creator', 'targetManager', 'comments.user', 'files.uploader'])
                    ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'task' => $task
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting task API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }
    }

    /**
     * Get file detail
     */
    public function getFile($id)
    {
        try {
            $file = TaskFile::with(['task', 'uploader'])->findOrFail($id);
            
            // Cek permission
            $userId = Auth::id();
            $task = $file->task;
            
            if ($task->assigned_to !== $userId && $task->created_by !== $userId && Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'file' => $file
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'File not found'
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
     * ========== TAMBAHAN: Fix untuk method yang bermasalah ==========
     */
    
    /**
     * Method tambahan untuk menghindari error
     */
    public function getHierarchyTasks()
    {
        try {
            $user = Auth::user();
            $tasks = collect();
            
            if ($user->role === 'general_manager') {
                // GM bisa lihat semua tugas dengan struktur hierarki
                $tasks = Task::with(['assignedUser', 'creator', 'targetManager', 'comments.user', 'files.uploader'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy(function($task) {
                        // Group by hierarchy level
                        if ($task->target_type === 'divisi' || $task->target_type === 'manager') {
                            return 'level_1'; // GM → Manager/Divisi
                        } elseif ($task->target_type === 'karyawan') {
                            return 'level_2'; // Manager → Karyawan
                        }
                        return 'other';
                    });
            } elseif ($user->role === 'manager_divisi') {
                // Manager hanya lihat tugas di divisinya
                $tasks = Task::with(['assignedUser', 'creator', 'targetManager', 'comments.user', 'files.uploader'])
                    ->where('target_divisi', $user->divisi)
                    ->orWhereHas('assignedUser', function($q) use ($user) {
                        $q->where('divisi', $user->divisi);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy(function($task) use ($user) {
                        if ($task->created_by === $user->id && $task->target_type === 'karyawan') {
                            return 'assigned_by_me'; // Tugas yang saya assign ke karyawan
                        } elseif ($task->assigned_to === $user->id && $task->target_type === 'divisi') {
                            return 'assigned_to_me'; // Tugas dari GM ke saya
                        }
                        return 'other';
                    });
            }
            
            return response()->json([
                'success' => true,
                'tasks' => $tasks,
                'user_role' => $user->role,
                'user_divisi' => $user->divisi ?? null
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting hierarchy tasks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load hierarchy tasks'
            ], 500);
        }
    }
}