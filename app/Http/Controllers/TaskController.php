<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use App\Models\User;
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
            // Untuk admin, ambil semua tasks dengan relasi yang benar
            $tasks = Task::with(['assignedUser:id,name,divisi', 'creator:id,name', 'targetManager:id,name,divisi'])
                ->orderBy('deadline', 'asc')
                ->get();
            
            Log::info('Loading all tasks for admin');
            Log::info('Tasks found: ' . $tasks->count());
            
            return view('admin.tasks.index', compact('tasks'));
            
        } catch (\Exception $e) {
            Log::error('Error loading tasks: ' . $e->getMessage());
            return view('admin.tasks.index', ['tasks' => collect([])]);
        }
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
            
            // Ambil semua tasks dengan relasi
            $tasks = Task::with(['assignedUser:id,name,divisi', 'creator:id,name', 'targetManager:id,name,divisi'])
                ->orderBy('deadline', 'asc')
                ->get();
            
            // Transform data untuk frontend
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
                
                // TAMBAHAN: Tentukan apakah task ini untuk user yang login
                $isForMe = false;
                if ($task->target_type === 'divisi') {
                    // Case-insensitive comparison untuk divisi
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
                    'is_for_me' => $isForMe, // TAMBAHAN: Flag untuk filter di frontend
                    
                    // Relasi objects
                    'assigned_user' => $task->assignedUser ? [
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
            Log::error($e->getTraceAsString());
            
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
                'target_type' => 'required|in:karyawan,divisi,manager',
                'assigned_to' => 'nullable|exists:users,id',
                'target_divisi' => 'nullable|string',
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
            ]);
            
            $validated['created_by'] = Auth::id();
            
            // Set is_broadcast based on target_type
            if ($validated['target_type'] === 'divisi') {
                $validated['is_broadcast'] = true;
            }
            
            $task = Task::create($validated);
            
            Log::info('Task created successfully', ['task_id' => $task->id, 'user_id' => Auth::id()]);
            
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
     * Get task detail
     */
    public function show($id)
    {
        try {
            $task = Task::with(['assignedUser:id,name,divisi', 'creator:id,name', 'targetManager:id,name,divisi'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'task' => $task
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
                'assigned_to' => 'nullable|exists:users,id',
                'target_divisi' => 'nullable|string',
                'target_manager_id' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
            ]);
            
            // Set completed_at if status changed to selesai
            if ($validated['status'] === 'selesai' && $task->status !== 'selesai') {
                $validated['completed_at'] = now();
            }
            
            $task->update($validated);
            
            Log::info('Task updated successfully', ['task_id' => $task->id, 'user_id' => Auth::id()]);
            
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
            
            Log::info('Task deleted successfully', ['task_id' => $id, 'user_id' => Auth::id()]);
            
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
     * Display list of tasks for karyawan
     */
    public function karyawanIndex()
    {
        try {
            $userId = Auth::id();
            
            // PERBAIKAN: Gunakan 'assigned_to' bukan 'user_id'
            $tasks = Task::where('assigned_to', $userId)
                        ->with(['creator:id,name', 'assignedUser:id,name,divisi'])
                        ->orderBy('deadline', 'asc')
                        ->get();
            
            Log::info('Karyawan Tasks Query', [
                'user_id' => $userId,
                'tasks_found' => $tasks->count(),
                'query' => Task::where('assigned_to', $userId)->toSql()
            ]);
            
            return view('karyawan.list', compact('tasks'));
            
        } catch (\Exception $e) {
            Log::error('Error in karyawanIndex: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
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
            // PERBAIKAN: Gunakan 'assigned_to' bukan 'user_id'
            $task = Task::where('id', $id)
                       ->where('assigned_to', Auth::id())
                       ->with(['creator:id,name', 'assignedUser:id,name,divisi'])
                       ->firstOrFail();
            
            return view('karyawan.tugas.show', compact('task'));
            
        } catch (\Exception $e) {
            Log::error('Error loading task detail: ' . $e->getMessage());
            return redirect()->route('karyawan.tugas.index')
                           ->with('error', 'Task tidak ditemukan');
        }
    }
}