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
        
        // FIX: Konversi prioritas (bahasa Indonesia) ke priority (database field)
        $prioritasMapping = [
            'tinggi' => 'high',
            'normal' => 'medium',
            'rendah' => 'low'
        ];
        $validated['priority'] = $prioritasMapping[$validated['prioritas']] ?? 'medium';
        unset($validated['prioritas']); // Hapus field prioritas
        
        // Set default status if not provided
        $validated['status'] = $validated['status'] ?? 'pending';
        
        // FIX: Validasi divisi untuk manager
        if ($request->target_type === 'divisi' && $request->filled('target_divisi')) {
            // Manager hanya bisa assign ke divisinya sendiri
            if ($request->target_divisi !== $user->divisi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda hanya dapat menugaskan ke divisi Anda sendiri'
                ], 422);
            }
            $validated['target_divisi'] = $request->target_divisi;
            $validated['target_type'] = 'divisi';
            $validated['is_broadcast'] = true;
            
            // TAMBAHKAN: Auto assign ke manager sendiri
            $validated['assigned_to'] = $user->id;
            $validated['target_manager_id'] = $user->id;
        } 
        // FIX: Untuk karyawan, pastikan di divisi yang sama
        elseif ($request->target_type === 'karyawan' && $request->filled('assigned_to')) {
            $karyawan = User::find($request->assigned_to);
            if (!$karyawan || $karyawan->divisi !== $user->divisi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan tidak berada di divisi Anda'
                ], 422);
            }
            $validated['assigned_to'] = $request->assigned_to;
            $validated['target_type'] = 'karyawan';
            $validated['is_broadcast'] = false;
            $validated['target_divisi'] = $user->divisi; // TAMBAHKAN: Simpan divisi
            $validated['target_manager_id'] = $user->id; // TAMBAHKAN: Simpan manager
        } 
        // FIX: Untuk manager lain, tetap bisa (tidak perlu validasi divisi)
        elseif ($request->target_type === 'manager' && $request->filled('target_manager_id')) {
            $validated['target_manager_id'] = $request->target_manager_id;
            $validated['target_type'] = 'manager';
            $validated['is_broadcast'] = false;
            $validated['assigned_to'] = $request->target_manager_id; // TAMBAHKAN: Assign ke manager target
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
        
        // FIX: Tambahkan validasi untuk manager - hanya bisa update tugas di divisinya
        $canUpdate = false;
        
        if ($task->created_by == $user->id) {
            // Dia yang membuat tugas
            $canUpdate = true;
        } elseif ($task->assigned_to == $user->id) {
            // Dia yang ditugaskan
            $canUpdate = true;
        } elseif ($task->target_divisi == $user->divisi && $user->role === 'manager_divisi') {
            // Tugas untuk divisinya
            $canUpdate = true;
        } elseif ($task->target_manager_id == $user->id) {
            // Dia adalah target manager
            $canUpdate = true;
        }
        
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
        if ($request->status === 'selesai' && $task->status !== 'selesai') {
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
        // FIX: Manager hanya bisa assign tugas dari divisinya sendiri
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
            'target_type' => 'karyawan', // Ubah target type
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
            // FIX: Tasks for manager's division (divisi yang sama)
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
                        ->orWhere(function($query) use ($user) {
                            // Tasks created by user
                            $query->where('created_by', $user->id);
                        })
                        ->orWhereHas('assignedUser', function($q) use ($user) {
                            // Tasks assigned to karyawan in same division
                            $q->where('divisi', $user->divisi);
                        })
                        ->with(['assignedUser', 'creator', 'targetManager'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            // FIX: All tasks for user's division
            $tasks = Task::where('target_divisi', $user->divisi)
                        ->orWhereHas('assignedUser', function($query) use ($user) {
                            $query->where('divisi', $user->divisi);
                        })
                        ->orWhereHas('targetManager', function($query) use ($user) {
                            $query->where('divisi', $user->divisi);
                        })
                        ->orWhere('created_by', $user->id)
                        ->with(['assignedUser', 'creator', 'targetManager'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        }
        
        // Transform data for frontend
        $tasks->transform(function($task) use ($user) {
            // Determine assignee text based on target_type
            if ($task->target_type === 'karyawan' && $task->assignedUser) {
                $task->assignee_text = $task->assignedUser->name;
                $task->assignee_divisi = $task->assignedUser->divisi ?? '-';
            } elseif ($task->target_type === 'divisi') {
                $task->assignee_text = 'Divisi ' . ($task->target_divisi ?? '-');
                $task->assignee_divisi = $task->target_divisi ?? '-';
            } elseif ($task->target_type === 'manager' && $task->targetManager) {
                $task->assignee_text = 'Manager: ' . $task->targetManager->name;
                $task->assignee_divisi = $task->targetManager->divisi ?? '-';
            } else {
                $task->assignee_text = '-';
                $task->assignee_divisi = '-';
            }
            
            $task->creator_name = $task->creator ? $task->creator->name : '-';
            $task->is_overdue = $task->deadline && now()->gt($task->deadline) && $task->status !== 'selesai';
            $task->formatted_deadline = $task->deadline ? $task->deadline->format('d M Y H:i') : '-';
            
            // TAMBAHKAN: Hitung progres
            $task->progress_percentage = $task->progress_percentage ?? 0;
            $task->status_color = $task->status_color ?? 'warning';
            $task->priority_color = $task->priority_color ?? 'secondary';
            
            return $task;
        });
        
        return response()->json($tasks);
    }
    
    public function getStatistics()
    {
        $user = Auth::user();
        
        // FIX: Statistics for manager's division (semua tugas di divisinya)
        $total = Task::where(function($q) use ($user) {
                        $q->where('target_divisi', $user->divisi)
                          ->orWhereHas('assignedUser', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhereHas('targetManager', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhere('created_by', $user->id);
                    })
                    ->count();
        
        $completed = Task::where(function($q) use ($user) {
                        $q->where('target_divisi', $user->divisi)
                          ->orWhereHas('assignedUser', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhereHas('targetManager', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhere('created_by', $user->id);
                    })
                    ->where('status', 'selesai')
                    ->count();
        
        $inProgress = Task::where(function($q) use ($user) {
                        $q->where('target_divisi', $user->divisi)
                          ->orWhereHas('assignedUser', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhereHas('targetManager', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhere('created_by', $user->id);
                    })
                    ->where('status', 'proses')
                    ->count();
        
        $pending = Task::where(function($q) use ($user) {
                        $q->where('target_divisi', $user->divisi)
                          ->orWhereHas('assignedUser', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhereHas('targetManager', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhere('created_by', $user->id);
                    })
                    ->where('status', 'pending')
                    ->count();
        
        $overdue = Task::where(function($q) use ($user) {
                        $q->where('target_divisi', $user->divisi)
                          ->orWhereHas('assignedUser', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhereHas('targetManager', function($q) use ($user) {
                              $q->where('divisi', $user->divisi);
                          })
                          ->orWhere('created_by', $user->id);
                    })
                    ->where('deadline', '<', now())
                    ->whereNotIn('status', ['selesai', 'dibatalkan'])
                    ->count();
        
        return response()->json([
            'total' => $total,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'pending' => $pending,
            'overdue' => $overdue
        ]);
    }
    
    // Get task detail
    public function show($id)
    {
        $task = Task::with(['assignedUser', 'creator', 'targetManager', 'assignedByManager'])
                   ->findOrFail($id);
        
        $user = Auth::user();
        
        // FIX: Authorization khusus untuk manager divisi
        $canView = false;
        
        if ($task->created_by == $user->id) {
            $canView = true;
        } elseif ($task->assigned_to == $user->id) {
            $canView = true;
        } elseif ($task->target_divisi == $user->divisi && $user->role === 'manager_divisi') {
            $canView = true;
        } elseif ($task->target_manager_id == $user->id) {
            $canView = true;
        } elseif ($user->role === 'general_manager') {
            $canView = true;
        }
        
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
        $user = Auth::user();
        
        // FIX: Manager hanya bisa melihat karyawan di divisinya sendiri
        if ($divisi !== $user->divisi && $user->role === 'manager_divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat melihat karyawan di divisi Anda'
            ], 403);
        }
        
        $karyawan = User::where('divisi', $divisi)
                       ->where('role', 'karyawan')
                       ->get(['id', 'name', 'email', 'divisi']);
        
        return response()->json($karyawan);
    }
    
    // TAMBAHKAN: Method untuk mendapatkan tugas dari General Manager
    public function getTasksFromGeneralManager()
    {
        $user = Auth::user();
        
        // Ambil tugas dari GM yang ditugaskan ke divisi manager ini
        $tasks = Task::where('target_divisi', $user->divisi)
                    ->where('target_type', 'divisi')
                    ->where('is_broadcast', true)
                    ->where('created_by', '!=', $user->id) // Bukan dibuat oleh manager ini
                    ->with(['creator', 'assignedUser', 'targetManager'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return response()->json([
            'success' => true,
            'tasks' => $tasks,
            'count' => $tasks->count()
        ]);
    }
    
    // TAMBAHKAN: Method untuk membuat tugas turunan dari tugas GM
    public function createSubtaskFromGmTask(Request $request, $parentTaskId)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'karyawan_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
            'catatan' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        
        // Cek parent task
        $parentTask = Task::findOrFail($parentTaskId);
        
        // Validasi: parent task harus dari GM ke divisi manager
        if ($parentTask->target_type !== 'divisi' || 
            $parentTask->target_divisi !== $user->divisi ||
            !$parentTask->is_broadcast) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas induk tidak valid'
            ], 422);
        }
        
        // Cek karyawan
        $karyawan = User::find($request->karyawan_id);
        if ($karyawan->divisi !== $user->divisi) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak berada di divisi Anda'
            ], 422);
        }
        
        // Buat subtask
        $subtask = Task::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi . "\n\n---\n*Ditugaskan dari tugas GM: " . $parentTask->judul . "*",
            'deadline' => $request->deadline,
            'status' => 'pending',
            'priority' => $parentTask->priority, // Warisi prioritas dari parent
            'assigned_to' => $request->karyawan_id,
            'created_by' => $user->id,
            'assigned_by_manager' => $parentTask->created_by, // GM asli
            'target_manager_id' => $user->id,
            'target_type' => 'karyawan',
            'target_divisi' => $user->divisi,
            'is_broadcast' => false,
            'catatan' => $request->catatan,
            'assigned_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Subtask berhasil dibuat',
            'subtask' => $subtask,
            'parent_task' => $parentTask
        ]);
    }
}