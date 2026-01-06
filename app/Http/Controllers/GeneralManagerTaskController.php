<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GeneralManagerTaskController extends Controller
{
    /**
     * Display a listing of tasks for General Manager
     */
    public function index()
    {
        try {
            // Ambil semua tugas dengan data user
            $tasks = Task::with('user:id,name,email')
                        ->orderBy('created_at', 'desc')
                        ->get();
            
            // Ambil daftar karyawan untuk dropdown
            $karyawan = User::where('role', 'karyawan')
                          ->select('id', 'name', 'email')
                          ->get();
            
            return view('general_manajer.kelola_tugas', compact('tasks', 'karyawan'));
            
        } catch (\Exception $e) {
            Log::error('Error loading tasks for general manager: ' . $e->getMessage());
            return view('general_manajer.kelola_tugas', [
                'tasks' => collect([]),
                'karyawan' => collect([]),
                'error' => 'Gagal memuat data tugas'
            ]);
        }
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'deadline' => 'required|date',
                'user_id' => 'required|exists:users,id',
                'priority' => 'required|in:low,medium,high',
                'category' => 'nullable|string|max:100',
                'assigner' => 'nullable|string|max:255'
            ]);

            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'full_description' => $validated['description'],
                'deadline' => $validated['deadline'],
                'user_id' => $validated['user_id'],
                'priority' => $validated['priority'],
                'category' => $validated['category'] ?? 'General',
                'assigner' => $validated['assigner'] ?? auth()->user()->name,
                'status' => 'pending'
            ]);

            Log::info('Task created by General Manager: ' . auth()->id() . ', Task ID: ' . $task->id);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dibuat',
                'task' => $task->load('user:id,name')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show task details
     */
    public function show($id)
    {
        try {
            $task = Task::with('user:id,name,email')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'task' => $task
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail tugas'
            ], 500);
        }
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'deadline' => 'required|date',
                'user_id' => 'required|exists:users,id',
                'priority' => 'required|in:low,medium,high',
                'status' => 'required|in:pending,in_progress,completed',
                'category' => 'nullable|string|max:100'
            ]);

            $task = Task::findOrFail($id);
            
            $task->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'full_description' => $validated['description'],
                'deadline' => $validated['deadline'],
                'user_id' => $validated['user_id'],
                'priority' => $validated['priority'],
                'status' => $validated['status'],
                'category' => $validated['category'] ?? $task->category,
                'completed_at' => $validated['status'] === 'completed' ? now() : null
            ]);

            Log::info('Task updated by General Manager: ' . auth()->id() . ', Task ID: ' . $task->id);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diperbarui',
                'task' => $task->load('user:id,name')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified task
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $task = Task::findOrFail($id);
            
            // Hapus file jika ada
            if ($task->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($task->file_path);
            }
            
            // Hapus komentar terkait
            $task->comments()->delete();
            
            // Hapus tugas
            $task->delete();
            
            DB::commit();
            
            Log::info('Task deleted by General Manager: ' . auth()->id() . ', Task ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get task statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total' => Task::count(),
                'pending' => Task::pending()->count(),
                'in_progress' => Task::inProgress()->count(),
                'completed' => Task::completed()->count(),
                'overdue' => Task::overdue()->count()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik'
            ], 500);
        }
    }
}