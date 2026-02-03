<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ManagerDivisiTaskController extends Controller
{
    /**
     * Menampilkan halaman kelola tugas (View HTML)
     */
    public function index()
    {
        $user = Auth::user();
        return view('manager_divisi.kelola_tugas', [
            'user' => $user
        ]);
    }

    /**
     * API: Mendapatkan daftar tugas untuk Manager Divisi
     */
    public function getTasksApi(Request $request)
    {
        try {
            $user = Auth::user();

            Log::info('API: Get tasks for manager divisi', [
                'user_id' => $user->id,
                'divisi_id' => $user->divisi_id,
                'role' => $user->role
            ]);

            // 1. Base Query dengan Relasi
            $query = Task::with([
                'assignee:id,name,email,divisi_id', 
                'project:id,nama,deskripsi,deadline', 
                'creator:id,name', 
                'targetDivisi:id,divisi'
            ]);

            // 2. Filter Hak Akses
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_by_manager', $user->id);

                // Cek berdasarkan Divisi ID Manager
                if (!empty($user->divisi_id)) {
                    // Tugas target ke divisi ini
                    $q->orWhere('target_divisi_id', $user->divisi_id);
                    
                    // Tugas yang ditugaskan ke karyawan di divisi ini
                    $q->orWhereHas('assignee', function($subQ) use ($user) {
                        $subQ->where('divisi_id', $user->divisi_id);
                    });
                }
            });

            // 3. Search Filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'LIKE', "%{$search}%")
                      ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                      ->orWhereHas('assignee', function($subQ) use ($search) {
                          $subQ->where('name', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('project', function($subQ) use ($search) {
                          $subQ->where('nama', 'LIKE', "%{$search}%");
                      });
                });
            }

            // 4. Status Filter
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Ambil data
            $tasks = $query->orderBy('created_at', 'desc')->get();
            
            Log::info('Query Results', ['count' => $tasks->count()]);

            // 5. Transformasi Data
            $transformedTasks = $tasks->map(function($task) {
                // Hitung overdue
                $isOverdue = false;
                if ($task->deadline && !in_array($task->status, ['selesai', 'dibatalkan'])) {
                    try {
                        $isOverdue = \Carbon\Carbon::parse($task->deadline)->isPast();
                    } catch (\Exception $e) {
                        $isOverdue = false;
                    }
                }

                return [
                    'id'                => $task->id,
                    'judul'             => $task->judul,
                    'nama_tugas'        => $task->nama_tugas ?? $task->judul,
                    'deskripsi'         => $task->deskripsi,
                    'deadline'          => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status'            => $task->status,
                    'priority'          => $task->priority ?? 'medium',
                    
                    // Project Data
                    'project_id'        => $task->project_id,
                    'project_name'      => $task->project ? $task->project->nama : 'Tidak ada Project',
                    'project_description' => $task->project ? $task->project->deskripsi : null,
                    'project_deadline'   => $task->project ? $task->project->deadline : null,

                    // Assignee Data
                    'assigned_to'       => $task->assigned_to,
                    'assignee_name'     => $task->assignee ? $task->assignee->name : 'Belum ditugaskan',
                    
                    // Divisi Data
                    'target_divisi_id'  => $task->target_divisi_id,
                    'target_divisi'     => $task->targetDivisi ? $task->targetDivisi->divisi : '-',
                    
                    // Meta
                    'is_overdue'        => $isOverdue,
                    'catatan'           => $task->catatan,
                    'created_at'        => $task->created_at ? $task->created_at->format('Y-m-d H:i:s') : null,
                    'updated_at'        => $task->updated_at ? $task->updated_at->format('Y-m-d H:i:s') : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedTasks,
                'total' => $transformedTasks->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getTasksApi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengambil data tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Mendapatkan daftar Projects untuk Dropdown & Auto-fill
     */
    public function getProjectsDropdown(Request $request)
    {
        try {
            $user = Auth::user();
            
            Log::info('Fetching projects dropdown', [
                'user_id' => $user->id,
                'divisi_id' => $user->divisi_id
            ]);
            
            // Ambil semua project yang aktif
            $projects = Project::whereNull('deleted_at')
                ->where('status', '!=', 'selesai')
                ->select([
                    'id', 
                    'nama', 
                    'deskripsi', 
                    'deadline', 
                    'divisi_id',
                    'created_by',
                    'status',
                    'harga',
                    'progres'
                ])
                ->orderBy('nama', 'asc')
                ->get();
            
            // Mapping yang lebih lengkap
            $mappedProjects = $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'nama' => $project->nama,
                    'name' => $project->nama,
                    'nama_project' => $project->nama,
                    'deskripsi' => $project->deskripsi ?? '',
                    'description' => $project->deskripsi ?? '',
                    'deskripsi_project' => $project->deskripsi ?? '',
                    'deadline' => $project->deadline ? $project->deadline->format('Y-m-d H:i:s') : null,
                    'tanggal_selesai' => $project->deadline ? $project->deadline->format('Y-m-d H:i:s') : null,
                    'harga' => $project->harga,
                    'budget' => $project->harga,
                    'progres' => $project->progres,
                    'progress' => $project->progres,
                    'status' => $project->status,
                    'divisi_id' => $project->divisi_id,
                    'division_id' => $project->divisi_id,
                    'created_by' => $project->created_by,
                    'user_id' => $project->created_by,
                    'created_by_id' => $project->created_by
                ];
            });
            
            Log::info('Projects found', [
                'count' => $mappedProjects->count(),
                'sample' => $mappedProjects->first()
            ]);

            return response()->json([
                'success' => true,
                'data' => $mappedProjects,
                'message' => 'Berhasil mengambil data project'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getProjectsDropdown: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengambil data project: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * API: Dropdown Karyawan
     */
    public function getKaryawanDropdown(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (empty($user->divisi_id)) {
                Log::warning('User has no divisi_id', ['user_id' => $user->id]);
                return response()->json([
                    'success' => true, 
                    'data' => [], 
                    'message' => 'User tidak terhubung ke divisi manapun'
                ]);
            }
            
            $karyawan = User::where('role', 'karyawan')
                            ->where('divisi_id', $user->divisi_id)
                            ->orderBy('name')
                            ->get(['id', 'name', 'email', 'divisi_id']);
            
            return response()->json([
                'success' => true,
                'data' => $karyawan
            ]);
        } catch (\Exception $e) {
            Log::error('Error getKaryawanDropdown: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengambil data karyawan'
            ], 500);
        }
    }

    /**
     * Store: Membuat Tugas Baru - DIPERBAIKI
     */
    /**
     * Store: Membuat Tugas Baru - FIXED (Tanpa Priority)
     */
/**
 * Store: Membuat Tugas Baru - DIPERBAIKI untuk Manager Divisi
 */
public function store(Request $request)
{
    try {
        $user = Auth::user();
        
        Log::info('=== MANAGER STORE TASK REQUEST ===', $request->all());
        
        // Validasi yang sesuai dengan form
        $validator = Validator::make($request->all(), [
            'project_id'        => 'required|exists:project,id',
            'judul'             => 'required|string|max:255',
            'nama_tugas'        => 'nullable|string|max:255',
            'deskripsi'         => 'required|string',
            'deadline'          => 'required|date',
            'assigned_to'       => 'required|exists:users,id',
            'status'            => 'nullable|in:pending,proses,selesai,dibatalkan',
            'target_divisi_id'  => 'required|exists:divisi,id',
            'catatan'           => 'nullable|string',
            // HAPUS 'priority' karena tidak ada di database
        ]);
        
        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
            throw new ValidationException($validator);
        }
        
        $validated = $validator->validated();
        
        // Tambahkan field default
        $validated['created_by'] = $user->id;
        $validated['assigned_by_manager'] = $user->id;
        $validated['target_type'] = 'karyawan';
        $validated['is_broadcast'] = false;
        $validated['status'] = $validated['status'] ?? 'pending';
        
        // Jika nama_tugas kosong, gunakan judul
        if (empty($validated['nama_tugas'])) {
            $validated['nama_tugas'] = $validated['judul'];
        }
        
        // Pastikan project_id valid
        if (!$this->userHasAccessToProject($user, $validated['project_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke project ini'
            ], 403);
        }
        
        // Pastikan karyawan berada di divisi yang sama
        $karyawan = User::find($validated['assigned_to']);
        if (!$karyawan || $karyawan->divisi_id != $validated['target_divisi_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak berada di divisi yang sama'
            ], 422);
        }
        
        // Buat task
        $task = Task::create($validated);
        
        Log::info('Task created successfully', [
            'task_id' => $task->id,
            'manager_id' => $user->id,
            'assigned_to' => $task->assigned_to,
            'divisi_id' => $task->target_divisi_id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat',
            'data' => $task->load(['project', 'assignee', 'creator', 'targetDivisi'])
        ]);
        
    } catch (ValidationException $e) {
        Log::error('Validation failed in store task', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        Log::error('Error store task: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);
        return response()->json([
            'success' => false, 
            'message' => 'Gagal membuat tugas: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Cek akses user ke project
 */
private function userHasAccessToProject($user, $projectId)
{
    if ($user->role === 'admin') {
        return true;
    }
    
    $project = Project::find($projectId);
    if (!$project) {
        return false;
    }
    
    // Manager divisi hanya bisa mengakses project di divisinya
    if ($user->role === 'manager_divisi') {
        return $project->divisi_id == $user->divisi_id;
    }
    
    return false;
}
    
    /**
     * Store khusus untuk pengelola_tugas.blade.php - FIXED (Tanpa Priority)
     */
    public function createTask(Request $request)
    {
        try {
            $user = Auth::user();
            
            Log::info('=== CREATE TASK FROM FORM ===', $request->all());
            
            // Validasi minimal
            $validator = Validator::make($request->all(), [
                'project_id'        => 'required|exists:project,id',
                'judul'             => 'required|string|max:255',
                'deskripsi'         => 'required|string',
                'deadline'          => 'required|date',
                'assigned_to'       => 'required|exists:users,id',
                'target_divisi_id'  => 'required|exists:divisi,id',
                'status'            => 'nullable|in:pending,proses,selesai,dibatalkan',
                'catatan'           => 'nullable|string',
            ]);
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
            
            // Buat task dengan data minimal
            $taskData = [
                'project_id'        => $validated['project_id'],
                'judul'             => $validated['judul'],
                'nama_tugas'        => $validated['judul'], // Default pakai judul
                'deskripsi'         => $validated['deskripsi'],
                'deadline'          => $validated['deadline'],
                'assigned_to'       => $validated['assigned_to'],
                'target_divisi_id'  => $validated['target_divisi_id'],
                'status'            => $validated['status'] ?? 'pending',
                'catatan'           => $validated['catatan'] ?? null,
                'created_by'        => $user->id,
                'assigned_by_manager' => $user->id,
                'target_type'       => 'karyawan',
                'is_broadcast'      => false,
                // Tidak ada priority, assigned_at, progress_percentage
            ];
            
            $task = Task::create($taskData);
            
            Log::info('Task created successfully', [
                'task_id' => $task->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dibuat',
                'task' => $task
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error createTask: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal membuat tugas: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update: Mengupdate Tugas
     */
    public function update(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'assigned_to' => 'nullable|exists:users,id',
                'project_id' => 'nullable|exists:project,id',
                'catatan' => 'nullable|string'
            ]);
            
            $task->update($validated);
            
            // Reload relasi
            $task->load('assignee', 'project');
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diupdate',
                'data' => $task
            ]);
        } catch (\Exception $e) {
            Log::error('Error update task: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengupdate tugas: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Destroy: Menghapus Tugas
     */
    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error destroy task: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menghapus tugas'
            ], 500);
        }
    }

    /**
     * Show: Detail Tugas (JSON)
     */
    public function show($id)
    {
        try {
            $task = Task::with([
                'assignee:id,name,email,divisi_id', 
                'project:id,nama,deskripsi,deadline', 
                'creator:id,name', 
                'targetDivisi:id,divisi'
            ])->find($id);
            
            if (!$task) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }
            
            // Cek akses
            $user = Auth::user();
            $hasAccess = $task->created_by == $user->id ||
                        $task->target_divisi_id == $user->divisi_id ||
                        ($task->assignee && $task->assignee->divisi_id == $user->divisi_id);
            
            if (!$hasAccess && $user->role !== 'admin') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Anda tidak memiliki akses ke tugas ini'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'data' => $task
            ]);
        } catch (\Exception $e) {
            Log::error('Error show task: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengambil detail tugas'
            ], 500);
        }
    }
    
    /**
     * API: Statistik Tugas
     */
    public function getStatistics()
    {
        try {
            $user = Auth::user();
            
            $query = Task::where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_by_manager', $user->id);
                
                if (!empty($user->divisi_id)) {
                    $q->orWhere('target_divisi_id', $user->divisi_id);
                    $q->orWhereHas('assignee', function($subQ) use ($user) {
                        $subQ->where('divisi_id', $user->divisi_id);
                    });
                }
            });

            $total = (clone $query)->count();
            $completed = (clone $query)->where('status', 'selesai')->count();
            $pending = (clone $query)->where('status', 'pending')->count();
            $proses = (clone $query)->where('status', 'proses')->count();
            $dibatalkan = (clone $query)->where('status', 'dibatalkan')->count();
            
            $overdue = (clone $query)
                ->whereNotNull('deadline')
                ->where('deadline', '<', now())
                ->whereNotIn('status', ['selesai', 'dibatalkan'])
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'completed' => $completed,
                    'pending' => $pending,
                    'proses' => $proses,
                    'dibatalkan' => $dibatalkan,
                    'overdue' => $overdue,
                    'in_progress' => $pending + $proses
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getStatistics: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }
}