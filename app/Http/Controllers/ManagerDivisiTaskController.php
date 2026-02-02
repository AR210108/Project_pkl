<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            // Pastikan nama relasi di Model Task sesuai: assignee(), project(), creator()
            $query = Task::with([
                'assignee:id,name,email,divisi_id', 
                'project:id,nama,deskripsi,deadline', 
                'creator:id,name', 
                'targetDivisi:id,divisi'
            ]);

            // 2. Filter Hak Akses (Diperbaiki logika OR agar data lebih longgar/sesuai kebutuhan)
            // Kita cari tugas yang BERHUBUNGAN dengan manager ini:
            // a. Dibuat oleh Manager ini
            // b. Tugas untuk divisi Manager ini
            // c. Tugas yang dikerjakan oleh anak buah (divisi sama)
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id);

                // Cek berdasarkan Divisi ID Manager
                if (!empty($user->divisi_id)) {
                    // Tugas target ke divisi ini
                    $q->orWhere('target_divisi_id', $user->divisi_id);
                    
                    // Tugas yang ditugaskan ke karyawan di divisi ini
                    $q->orWhereHas('assignee', function($subQ) use ($user) {
                        $subQ->where('divisi_id', $user->divisi_id);
                    });
                    
                    // Tambahan: Jika ada field division_id di tabel tasks (sesuaikan jika ada)
                    // $q->orWhere('division_id', $user->divisi_id);
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

            // Ambil data (Ditambahkan Log untuk Debugging)
            $tasks = $query->orderBy('created_at', 'desc')->get();
            
            Log::info('Query Results', ['count' => $tasks->count()]);

            // 5. Transformasi Data (Sesuaikan mapping agar frontend bisa membacanya)
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
                    'deskripsi'         => $task->deskripsi,
                    'deadline'          => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status'            => $task->status,
                    
                    // Project Data
                    'project_id'        => $task->project_id,
                    // Pastikan ini nama field yang benar di DB frontend & controller
                    'project_name'      => $task->project ? $task->project->nama : 'Tidak ada Project',
                    'project_description' => $task->project ? $task->project->deskripsi : null,
                    'project_deadline'   => $task->project ? $task->project->deadline : null,

                    // Assignee Data
                    'assigned_to'       => $task->assigned_to,
                    // Support dua kemungkinan nama field assignee
                    'assignee_name'     => $task->assignee ? $task->assignee->name : ($task->assigned_to_name ?? 'Belum ditugaskan'),
                    
                    // Divisi Data
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
/**
 * API: Mendapatkan daftar Projects untuk Dropdown & Auto-fill
 */
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
        
        // Pastikan mengambil semua field yang dibutuhkan
        $projects = Project::whereNull('deleted_at')
            ->where(function($query) use ($user) {
                if (!empty($user->divisi_id)) {
                    $query->where('divisi_id', $user->divisi_id);
                }
                $query->orWhere('created_by', $user->id);
                $query->orWhere('penanggung_jawab_id', $user->id);
            })
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
        
        // Mapping yang lebih lengkap untuk semua field yang mungkin dibutuhkan frontend
        $mappedProjects = $projects->map(function($project) {
            return [
                'id' => $project->id,
                'nama' => $project->nama,
                'name' => $project->nama, // alternatif
                'nama_project' => $project->nama, // alternatif lain
                'deskripsi' => $project->deskripsi ?? '', // Pastikan tidak null
                'description' => $project->deskripsi ?? '', // alternatif
                'deskripsi_project' => $project->deskripsi ?? '', // alternatif lain
                'deadline' => $project->deadline ? $project->deadline->format('Y-m-d H:i:s') : null,
                'tanggal_selesai' => $project->deadline ? $project->deadline->format('Y-m-d H:i:s') : null, // alternatif
                'harga' => $project->harga,
                'budget' => $project->harga, // alternatif
                'progres' => $project->progres,
                'progress' => $project->progres, // alternatif
                'status' => $project->status,
                'divisi_id' => $project->divisi_id,
                'division_id' => $project->divisi_id, // alternatif
                'created_by' => $project->created_by,
                'user_id' => $project->created_by, // alternatif
                'created_by_id' => $project->created_by // alternatif lain
            ];
        });
        
        Log::info('Projects found', [
            'count' => $mappedProjects->count(),
            'sample' => $mappedProjects->first() // Log sample data
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
            
            // Cek apakah user punya divisi
            if (empty($user->divisi_id)) {
                Log::warning('User has no divisi_id', ['user_id' => $user->id]);
                return response()->json([
                    'success' => true, 
                    'data' => [], 
                    'message' => 'User tidak terhubung ke divisi manapun'
                ]);
            }
            
            // Ambil karyawan role karyawan di divisi yang sama
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
     * Store: Membuat Tugas Baru
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Validasi input
            // Pastikan 'project' (nama tabel) benar. Jika tabelnya 'projects', ganti menjadi 'exists:projects,id'
            $validated = $request->validate([
                'judul'             => 'required|string|max:255',
                'deskripsi'         => 'required|string',
                'deadline'          => 'required|date',
                'status'            => 'sometimes|in:pending,proses,selesai,dibatalkan',
                'assigned_to'       => 'nullable|exists:users,id',
                'project_id'        => 'nullable|exists:project,id', // <-- Cek nama tabel project Anda disini
                'catatan'           => 'nullable|string',
                'target_divisi_id'  => 'sometimes|exists:divisis,id' // Cek nama tabel divisi
            ]);
            
            // Set default values
            $validated['created_by'] = $user->id;
            $validated['assigned_by_manager'] = $user->id;
            
            // Jika tidak ada target divisi, gunakan divisi manager saat ini
            if (!isset($validated['target_divisi_id']) && !empty($user->divisi_id)) {
                $validated['target_divisi_id'] = $user->divisi_id;
            }
            
            if (!isset($validated['status'])) {
                $validated['status'] = 'pending';
            }
            
            $task = Task::create($validated);
            
            // Load ulang dengan relasi untuk response yang lengkap
            $task->load('assignee', 'project', 'creator');
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dibuat',
                'data' => $task
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error store task: ' . $e->getMessage(), [
                'request' => $request->all()
            ]);
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
                'project_id' => 'nullable|exists:project,id', // Cek nama tabel
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
     * API: Statistik Tugas (Opsional jika dibutuhkan)
     */
    public function getStatistics()
    {
        try {
            $user = Auth::user();
            
            // Gunakan scope yang sama dengan getTasksApi agar statistik akurat
            $query = Task::where(function($q) use ($user) {
                $q->where('created_by', $user->id);
                
                if (!empty($user->divisi_id)) {
                    $q->orWhere('target_divisi_id', $user->divisi_id);
                    $q->orWhereHas('assignee', function($subQ) use ($user) {
                        $subQ->where('divisi_id', $user->divisi_id);
                    });
                }
            });

            $total = (clone $query)->count();
            $completed = (clone $query)->where('status', 'selesai')->count();
            $inProgress = (clone $query)->whereIn('status', ['pending', 'proses'])->count(); // Gabung pending & proses
            
            $overdue = (clone $query)
                ->whereNotNull('deadline')
                ->where('deadline', '<', now())
                ->whereNotIn('status', ['selesai', 'dibatalkan'])
                ->count();

            return response()->json([
                'success' => true,
                'data' => compact('total', 'completed', 'inProgress', 'overdue')
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