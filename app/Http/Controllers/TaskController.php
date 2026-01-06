<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display list of tasks for the logged-in user (Admin)
     */
    public function index()
    {
        try {
            // Untuk admin, ambil semua tasks
            $tasks = Task::with('user:id,name')->orderBy('deadline', 'asc')->get();
            
            Log::info('Loading all tasks for admin');
            Log::info('Tasks found: ' . $tasks->count());
            
            return view('admin.tasks.index', compact('tasks'));
            
        } catch (\Exception $e) {
            Log::error('Error loading tasks: ' . $e->getMessage());
            return view('admin.tasks.index', ['tasks' => collect([])]);
        }
    }
    
    /**
     * Display list of tasks for karyawan
     */
  public function karyawanIndex()
{
    try {
        $userId = Auth::id();
        
        $tasks = Task::where('user_id', $userId)
                    ->orderBy('deadline', 'asc')
                    ->get();
        
        // GUNAKAN VIEW YANG BENAR
        return view('karyawan.list', compact('tasks'));
        
    } catch (\Exception $e) {
        Log::error('Error in karyawanIndex: ' . $e->getMessage());
        
        return view('karyawan.list', [
            'tasks' => collect([]),
            'error' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}
    
    /**
     * Show specific task for karyawan
     */
    public function karyawanShow($id)
    {
        try {
            $task = Task::where('id', $id)
                       ->where('user_id', Auth::id())
                       ->firstOrFail();
            
            return view('karyawan.tugas.show', compact('task'));
            
        } catch (\Exception $e) {
            Log::error('Error loading task detail: ' . $e->getMessage());
            return redirect()->route('karyawan.tugas.index')
                           ->with('error', 'Task tidak ditemukan');
        }
    }
    
    /**
     * Get comments for a specific task
     */
    public function getComments($taskId)
    {
        try {
            $task = Task::findOrFail($taskId);
            
            // Check if user has permission to view this task
            if ($task->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            $comments = Comment::where('task_id', $taskId)
                            ->with('user:id,name')
                            ->latest()
                            ->get();
            
            return response()->json([
                'success' => true,
                'comments' => $comments->map(function($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name
                        ],
                        'time_ago' => $comment->created_at->diffForHumans(),
                        'created_at' => $comment->created_at->format('Y-m-d H:i:s')
                    ];
                })
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
     * Store a new comment for a task
     */
    public function storeComment(Request $request, $taskId)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000'
            ]);
            
            $task = Task::findOrFail($taskId);
            
            // Check if user has permission to comment on this task
            if ($task->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            $comment = Comment::create([
                'content' => $request->content,
                'user_id' => Auth::id(),
                'task_id' => $taskId
            ]);
            
            // Load comment with user relationship
            $comment->load('user:id,name');
            
            Log::info('Comment created by user ' . Auth::id() . ' for task ' . $taskId);
            
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name
                    ],
                    'time_ago' => 'Baru saja',
                    'created_at' => $comment->created_at->format('Y-m-d H:i:s')
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error storing comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save comment'
            ], 500);
        }
    }
    
    /**
     * Upload file for a task
     */
    public function uploadFile(Request $request, $taskId)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240|mimes:pdf,doc,docx,zip,rar,jpg,jpeg,png,gif',
                'notes' => 'nullable|string|max:500'
            ]);
            
            $task = Task::findOrFail($taskId);
            
            // Check if user has permission to upload file for this task
            if ($task->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            DB::beginTransaction();
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // Generate unique filename
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Store file
                $path = $file->storeAs('task_files', $filename, 'public');
                
                // Delete old file if exists
                if ($task->file_path) {
                    Storage::disk('public')->delete($task->file_path);
                }
                
                // Update task
                $task->update([
                    'file_path' => $path,
                    'file_notes' => $request->notes,
                    'status' => 'completed',
                    'completed_at' => now()
                ]);
                
                DB::commit();
                
                Log::info('File uploaded for task ' . $taskId . ' by user ' . Auth::id());
                
                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'file_path' => $path,
                    'task' => $task->fresh()
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error uploading file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get task statistics for dashboard
     */
    public function getStatistics()
    {
        try {
            $userId = Auth::id();
            
            $stats = [
                'total' => Task::where('user_id', $userId)->count(),
                'pending' => Task::where('user_id', $userId)->where('status', 'pending')->count(),
                'in_progress' => Task::where('user_id', $userId)->where('status', 'in_progress')->count(),
                'completed' => Task::where('user_id', $userId)->where('status', 'completed')->count(),
                'overdue' => Task::where('user_id', $userId)
                              ->where('status', '!=', 'completed')
                              ->where('deadline', '<', now())
                              ->count()
            ];
            
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }
    
    /**
     * Update task status
     */
    public function updateStatus(Request $request, $taskId)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,in_progress,completed'
            ]);
            
            $task = Task::findOrFail($taskId);
            
            // Check permission
            if ($task->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            $task->update([
                'status' => $request->status,
                'completed_at' => $request->status === 'completed' ? now() : null
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'task' => $task->fresh()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating task status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task status: ' . $e->getMessage()
            ], 500);
        }
    }
}