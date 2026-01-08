<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManagerDivisiTaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get karyawan in same division
        $karyawan = User::where('divisi', $user->divisi)
                       ->where('role', 'karyawan')
                       ->get();
        
        // List of all divisions (hardcoded)
        $divisi = [
            'Programmer',
            'Desainer',
            'Digital Marketing'
        ];
        
        // Get other managers excluding current user
        $managers = User::where('role', 'manager_divisi')
                       ->where('id', '!=', $user->id)
                       ->get();
        
        return view('manager_divisi.kelola_tugas', compact(
            'karyawan', 
            'divisi', 
            'managers'
        ));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prioritas' => 'required|in:tinggi,normal,rendah',
            'deadline' => 'required|date',
            'status' => 'sometimes|in:pending,proses,selesai,dibatalkan',
            'target_type' => 'required|in:karyawan,divisi,manager',
            'kategori' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        $validated['created_by'] = $user->id;
        $validated['assigned_by_manager'] = $user->id;
        $validated['assigned_at'] = now();
        
        // Set default status if not provided
        $validated['status'] = $validated['status'] ?? 'pending';
        
        // Handle different target types
        if ($request->target_type === 'karyawan' && $request->filled('assigned_to')) {
            $validated['assigned_to'] = $request->assigned_to;
            $validated['target_type'] = 'karyawan';
        } elseif ($request->target_type === 'divisi' && $request->filled('target_divisi')) {
            $validated['target_divisi'] = $request->target_divisi;
            $validated['target_type'] = 'divisi';
            $validated['is_broadcast'] = true;
        } elseif ($request->target_type === 'manager' && $request->filled('target_manager_id')) {
            $validated['target_manager_id'] = $request->target_manager_id;
            $validated['target_type'] = 'manager';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Harap lengkapi data penerima tugas sesuai tipe yang dipilih'
            ], 422);
        }
        
        // For editing existing task
        if ($request->filled('id')) {
            $task = Task::findOrFail($request->id);
            
            // Check if user is authorized to edit
            if ($task->created_by != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengedit tugas ini'
                ], 403);
            }
            
            $task->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diperbarui'
            ]);
        }
        
        Task::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat'
        ]);
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai,dibatalkan',
            'catatan_update' => 'nullable|string'
        ]);
        
        $task = Task::findOrFail($id);
        $user = Auth::user();
        
        // Check authorization - user can update if:
        // 1. They created the task
        // 2. They are assigned to the task
        // 3. Task is assigned to their division
        // 4. They are the target manager
        $canUpdate = $task->created_by == $user->id ||
                    $task->assigned_to == $user->id ||
                    $task->target_divisi == $user->divisi ||
                    $task->target_manager_id == $user->id;
        
        if (!$canUpdate) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengupdate tugas ini'
            ], 403);
        }
        
        $updateData = [
            'status' => $request->status,
        ];
        
        // Add catatan_update if provided
        if ($request->filled('catatan_update')) {
            $updateData['catatan_update'] = $request->catatan_update;
        }
        
        // Set completed_at if status is 'selesai'
        if ($request->status === 'selesai') {
            $updateData['completed_at'] = now();
        }
        
        $task->update($updateData);
        
        return response()->json([
            'success' => true,
            'message' => 'Status tugas berhasil diupdate'
        ]);
    }
    
    public function assignToKaryawan(Request $request, $id)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:users,id'
        ]);
        
        $task = Task::findOrFail($id);
        $user = Auth::user();
        
        // Check if task is broadcast to division
        if (!$task->is_broadcast || $task->target_type !== 'divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Tugas ini bukan tugas broadcast ke divisi'
            ], 422);
        }
        
        // Check if user is authorized to assign (must be manager of the division)
        if ($task->target_divisi !== $user->divisi || $user->role !== 'manager_divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menugaskan tugas ini'
            ], 403);
        }
        
        // Check if karyawan is in the same division
        $karyawan = User::findOrFail($request->karyawan_id);
        if ($karyawan->divisi != $task->target_divisi) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak berada di divisi yang sama'
            ], 422);
        }
        
        $task->update([
            'assigned_to' => $request->karyawan_id,
            'assigned_by_manager' => $user->id,
            'assigned_at' => now(),
            'is_broadcast' => false, // No longer a broadcast task
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditugaskan ke karyawan'
        ]);
    }
    
    public function getTasksApi(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'my-tasks');
        
        if ($type === 'my-tasks') {
            // Tasks created by this manager
            $tasks = Task::where('created_by', $user->id)
                        ->with(['assignedUser', 'creator', 'targetManager'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        } elseif ($type === 'team-tasks') {
            // Tasks assigned to this manager or their division
            $tasks = Task::where(function($query) use ($user) {
                            // Tasks where user is the target manager
                            $query->where('target_manager_id', $user->id);
                        })
                        ->orWhere(function($query) use ($user) {
                            // Tasks broadcast to user's division
                            $query->where('target_divisi', $user->divisi)
                                  ->where('target_type', 'divisi');
                        })
                        ->orWhere(function($query) use ($user) {
                            // Tasks assigned directly to user
                            $query->where('assigned_to', $user->id);
                        })
                        ->with(['assignedUser', 'creator', 'targetManager'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            // All tasks for user's division
            $tasks = Task::where('target_divisi', $user->divisi)
                        ->orWhereHas('assignedUser', function($query) use ($user) {
                            $query->where('divisi', $user->divisi);
                        })
                        ->with(['assignedUser', 'creator', 'targetManager'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        }
        
        // Transform data for frontend
        $tasks->transform(function($task) use ($user) {
            // Determine assignee text based on target_type
            if ($task->target_type === 'karyawan' && $task->assignedUser) {
                $task->assignee_text = $task->assignedUser->name;
                $task->assignee_divisi = $task->assignedUser->divisi;
            } elseif ($task->target_type === 'divisi') {
                $task->assignee_text = $task->target_divisi;
                $task->assignee_divisi = $task->target_divisi;
            } elseif ($task->target_type === 'manager' && $task->targetManager) {
                $task->assignee_text = $task->targetManager->name;
                $task->assignee_divisi = $task->targetManager->divisi;
            } else {
                $task->assignee_text = '-';
                $task->assignee_divisi = '-';
            }
            
            $task->creator_name = $task->creator ? $task->creator->name : '-';
            $task->is_overdue = $task->deadline && now()->gt($task->deadline) && $task->status !== 'selesai';
            
            return $task;
        });
        
        return response()->json($tasks);
    }
    
    public function getStatistics()
    {
        $user = Auth::user();
        
        // Statistics for tasks created by this manager
        $total = Task::where('created_by', $user->id)->count();
        $completed = Task::where('created_by', $user->id)
                        ->where('status', 'selesai')
                        ->count();
        $inProgress = Task::where('created_by', $user->id)
                        ->where('status', 'proses')
                        ->count();
        $pending = Task::where('created_by', $user->id)
                        ->where('status', 'pending')
                        ->count();
        $cancelled = Task::where('created_by', $user->id)
                        ->where('status', 'dibatalkan')
                        ->count();
        
        return response()->json([
            'total' => $total,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'pending' => $pending,
            'cancelled' => $cancelled
        ]);
    }
    
    // Get task detail
    public function show($id)
    {
        $task = Task::with(['assignedUser', 'creator', 'targetManager', 'assignedByManager'])
                   ->findOrFail($id);
        
        $user = Auth::user();
        
        // Check authorization
        $canView = $task->created_by == $user->id ||
                  $task->assigned_to == $user->id ||
                  $task->target_divisi == $user->divisi ||
                  $task->target_manager_id == $user->id ||
                  $user->role === 'admin';
        
        if (!$canView) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk melihat tugas ini'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }
    
    // Get karyawan by divisi
    public function getKaryawanByDivisi($divisi)
    {
        $karyawan = User::where('divisi', $divisi)
                       ->where('role', 'karyawan')
                       ->get(['id', 'name', 'email', 'divisi']);
        
        return response()->json($karyawan);
    }
}