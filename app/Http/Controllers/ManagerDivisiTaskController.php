<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAcceptance;
use App\Models\User;
use App\Models\Project;
use App\Models\Divisi;
use App\Models\TaskFile;
use App\Models\TugasKaryawanToManager;
use App\Models\TugasApprovalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

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
     * Menampilkan halaman tugas dari karyawan
     */
    public function tugasDariKaryawan()
    {
        $user = Auth::user();
        return view('manager_divisi.tugas-dari-karyawan', [
            'user' => $user
        ]);
    }

    /**
     * ==============================================
     * API UNTUK TUGAS DARI KARYAWAN KE MANAGER
     * ==============================================
     */

    /**
     * API: Mendapatkan daftar tugas dari karyawan untuk Manager Divisi
     */
    public function getTasksFromKaryawan(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Validasi hanya manager divisi
            if ($user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya manager divisi yang dapat mengakses'
                ], 403);
            }

            Log::info('API: Get tasks from karyawan for manager', [
                'user_id' => $user->id,
                'divisi_id' => $user->divisi_id
            ]);

            $query = TugasKaryawanToManager::with(['karyawan:id,name,email', 'project:id,nama'])
                ->where('manager_divisi_id', $user->id)
                ->orderBy('created_at', 'desc');

            // Filter berdasarkan status
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan project
            if ($request->has('project_id') && $request->project_id !== 'all') {
                $query->where('project_id', $request->project_id);
            }

            // Pencarian
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('nama_tugas', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhereHas('karyawan', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('project', function($q) use ($search) {
                          $q->where('nama', 'like', "%{$search}%");
                      });
                });
            }

            $tasks = $query->get()->map(function($task) {
                $isOverdue = false;
                if ($task->deadline && !in_array($task->status, ['selesai', 'dibatalkan'])) {
                    try {
                        $isOverdue = \Carbon\Carbon::parse($task->deadline)->isPast();
                    } catch (\Exception $e) {
                        $isOverdue = false;
                    }
                }

                return [
                    'id' => $task->id,
                    'karyawan_id' => $task->karyawan_id,
                    'karyawan_name' => $task->karyawan->name ?? 'Unknown',
                    'project_id' => $task->project_id,
                    'project_name' => $task->project->nama ?? 'Tidak ada Project',
                    'judul' => $task->judul,
                    'nama_tugas' => $task->nama_tugas,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status' => $task->status,
                    'catatan' => $task->catatan,
                    'lampiran' => $task->lampiran,
                    'lampiran_url' => $task->lampiran ? Storage::url($task->lampiran) : null,
                    'is_overdue' => $isOverdue,
                    'created_at' => $task->created_at ? $task->created_at->format('Y-m-d H:i:s') : null,
                    'updated_at' => $task->updated_at ? $task->updated_at->format('Y-m-d H:i:s') : null,
                ];
            });

            Log::info('Tasks from karyawan loaded', ['count' => $tasks->count()]);

            return response()->json([
                'success' => true,
                'data' => $tasks,
                'count' => $tasks->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getTasksFromKaryawan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tugas dari karyawan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Statistik tugas dari karyawan
     */
    public function getTasksFromKaryawanStatistics(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $total = TugasKaryawanToManager::where('manager_divisi_id', $user->id)->count();
            $pending = TugasKaryawanToManager::where('manager_divisi_id', $user->id)
                ->where('status', 'pending')
                ->count();
            $proses = TugasKaryawanToManager::where('manager_divisi_id', $user->id)
                ->where('status', 'proses')
                ->count();
            $selesai = TugasKaryawanToManager::where('manager_divisi_id', $user->id)
                ->where('status', 'selesai')
                ->count();
            $dibatalkan = TugasKaryawanToManager::where('manager_divisi_id', $user->id)
                ->where('status', 'dibatalkan')
                ->count();
            
            // Hitung overdue (deadline sudah lewat dan status bukan selesai/dibatalkan)
            $overdue = TugasKaryawanToManager::where('manager_divisi_id', $user->id)
                ->where('status', '!=', 'selesai')
                ->where('status', '!=', 'dibatalkan')
                ->whereDate('deadline', '<', now())
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'pending' => $pending,
                    'proses' => $proses,
                    'selesai' => $selesai,
                    'dibatalkan' => $dibatalkan,
                    'overdue' => $overdue,
                    'in_progress' => $proses
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getTasksFromKaryawanStatistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Approve tugas dari karyawan
     */
    public function approveTaskFromKaryawan(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya manager divisi yang dapat menyetujui tugas'
                ], 403);
            }

            $task = TugasKaryawanToManager::findOrFail($id);
            
            // Validasi hanya manager yang berhak
            if ($task->manager_divisi_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak berhak mengakses tugas ini'
                ], 403);
            }

            $validated = $request->validate([
                'action' => 'required|in:approved,rejected,returned',
                'notes' => 'nullable|string',
                'status' => 'required_if:action,approved|in:proses,selesai,dibatalkan'
            ]);

            // Update status tugas
            if ($validated['action'] === 'approved') {
                $task->status = $validated['status'];
                $task->save();
                // Attempt to update corresponding Task (original task assigned to karyawan)
                try {
                    $relatedTask = \App\Models\Task::where('project_id', $task->project_id)
                        ->where('judul', $task->judul)
                        ->where(function($q) use ($task) {
                            $q->where('assigned_to', $task->karyawan_id)
                              ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$task->karyawan_id]);
                        })
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($relatedTask) {
                        $relatedTask->status = $task->status === 'proses' ? 'proses' : ($task->status === 'selesai' ? 'selesai' : $relatedTask->status);
                        // If manager approved as selesai, mark task completed and copy submission
                        if ($task->status === 'selesai') {
                            $relatedTask->completed_at = now();
                            // Copy submission file/notes from TugasKaryawanToManager to Task if available
                            if (!empty($task->lampiran)) {
                                $relatedTask->submission_file = $task->lampiran;
                            }
                            if (!empty($task->catatan)) {
                                $relatedTask->submission_notes = $task->catatan;
                            }
                            $relatedTask->submitted_at = now();
                        }
                        $relatedTask->save();
                        Log::info('Related Task updated from TugasKaryawan approval', ['tugas_id' => $task->id, 'task_id' => $relatedTask->id, 'new_status' => $relatedTask->status]);
                    } else {
                        Log::info('No related Task found to update for TugasKaryawan', ['tugas_id' => $task->id]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error updating related Task after approval: ' . $e->getMessage(), ['tugas_id' => $task->id]);
                }
            } elseif ($validated['action'] === 'rejected') {
                $task->status = 'dibatalkan';
                $task->save();
                // If rejected, optionally update related Task to 'dibatalkan'
                try {
                    $relatedTask = \App\Models\Task::where('project_id', $task->project_id)
                        ->where('judul', $task->judul)
                        ->where(function($q) use ($task) {
                            $q->where('assigned_to', $task->karyawan_id)
                              ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$task->karyawan_id]);
                        })
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($relatedTask) {
                        $relatedTask->status = 'dibatalkan';
                        $relatedTask->save();
                        Log::info('Related Task marked dibatalkan after TugasKaryawan rejection', ['tugas_id' => $task->id, 'task_id' => $relatedTask->id]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error updating related Task after rejection: ' . $e->getMessage(), ['tugas_id' => $task->id]);
                }
            }
            // Jika returned, status tetap pending

            // Simpan riwayat approval
            TugasApprovalHistory::create([
                'tugas_id' => $task->id,
                'approved_by' => $user->id,
                'action' => $validated['action'],
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Task approved/rejected', [
                'task_id' => $task->id,
                'action' => $validated['action'],
                'manager_id' => $user->id,
                'status' => $task->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diproses',
                'data' => $task->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error approveTaskFromKaryawan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Detail tugas dari karyawan
     */
    public function getTaskFromKaryawanDetail($id)
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $task = TugasKaryawanToManager::with(['karyawan:id,name,email', 'project:id,nama,deskripsi', 'approvalHistory.approver:id,name'])
                ->where('manager_divisi_id', $user->id)
                ->findOrFail($id);

            $isOverdue = false;
            if ($task->deadline && !in_array($task->status, ['selesai', 'dibatalkan'])) {
                try {
                    $isOverdue = \Carbon\Carbon::parse($task->deadline)->isPast();
                } catch (\Exception $e) {
                    $isOverdue = false;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $task->id,
                    'karyawan' => $task->karyawan,
                    'project' => $task->project,
                    'judul' => $task->judul,
                    'nama_tugas' => $task->nama_tugas,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => isset($task->deadline) ? (string)$task->deadline : null,
                    'status' => $task->status,
                    'catatan' => $task->catatan,
                    'lampiran' => $task->lampiran,
                    'lampiran_url' => $task->lampiran ? Storage::url($task->lampiran) : null,
                    'is_overdue' => $isOverdue,
                    'approval_history' => $task->approvalHistory,
                    'created_at' => $task->created_at ? $task->created_at->format('Y-m-d H:i:s') : null,
                    'updated_at' => $task->updated_at ? $task->updated_at->format('Y-m-d H:i:s') : null,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getTaskFromKaryawanDetail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Daftar karyawan dalam divisi
     */
    public function getKaryawanInDivisi()
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $karyawan = User::where('role', 'karyawan')
                ->where('divisi_id', $user->divisi_id)
                ->select('id', 'name', 'email', 'divisi')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $karyawan
            ]);

        } catch (\Exception $e) {
            Log::error('Error getKaryawanInDivisi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ==============================================
     * API UNTUK TUGAS BIASA (Manager ke Karyawan)
     * ==============================================
     */

    /**
     * API: Mendapatkan daftar tugas untuk Manager Divisi (termasuk tugas dari karyawan)
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

            // 1. Base Query dengan Relasi untuk Task biasa
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
                    $q->where('nama_tugas', 'LIKE', "%{$search}%")
                      ->orWhere('judul', 'LIKE', "%{$search}%")
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
            // Only show pending or in-process tasks in regular manager list
            $query->whereIn('status', ['pending', 'proses']);

            // 4. Status Filter (allow explicit override if needed)
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Ambil data Task biasa
            $tasks = $query->orderBy('created_at', 'desc')->get();
            
            // Juga ambil task dari karyawan (TugasKaryawanToManager)
            $tasksFromKaryawan = TugasKaryawanToManager::with(['karyawan:id,name,email', 'project:id,nama'])
                ->where('manager_divisi_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            Log::info('Query Results', ['tasks' => $tasks->count(), 'tasks_from_karyawan' => $tasksFromKaryawan->count()]);

            // 5. Transformasi Data Task Biasa
            $transformedTasks = $tasks->map(function($task) {
                try {
                    // Hitung overdue
                    $isOverdue = false;
                    if ($task->deadline && !in_array($task->status, ['selesai', 'dibatalkan'])) {
                        try {
                            $isOverdue = \Carbon\Carbon::parse($task->deadline)->isPast();
                        } catch (\Exception $e) {
                            $isOverdue = false;
                        }
                    }
                    
                    // Ensure both judul and nama_tugas are populated
                    $namaTugas = !empty($task->nama_tugas) ? $task->nama_tugas : $task->judul;
                    $judul = !empty($task->judul) ? $task->judul : $task->nama_tugas;
                    
                    // Get assigned names helper (inline instead of calling method in closure)
                    $assignedIds = $task->assigned_to_ids;
                    $assignedNames = null;
                    
                    \Log::info('Computing assigned_names for task ' . $task->id, [
                        'assigned_to_ids' => $assignedIds,
                        'type' => gettype($assignedIds)
                    ]);
                    
                    if ($assignedIds) {
                        if (is_string($assignedIds)) {
                            $assignedIds = json_decode($assignedIds, true) ?? [];
                            \Log::info('Parsed from string, now array:', ['ids' => $assignedIds]);
                        }
                        
                        if (is_array($assignedIds) && !empty($assignedIds)) {
                            \Log::info('Finding users for IDs:', ['ids' => $assignedIds]);
                            $users = \DB::table('users')
                                ->whereIn('id', $assignedIds)
                                ->select('id', 'name')
                                ->get();
                            
                            \Log::info('Found users count: ' . $users->count());
                            
                            if ($users->isNotEmpty()) {
                                $names = array_map(function($id) use ($users) {
                                    $user = $users->firstWhere('id', $id);
                                    return $user ? $user->name : null;
                                }, $assignedIds);
                                $assignedNames = implode(', ', array_filter($names));
                                \Log::info('Assigned names computed:', ['result' => $assignedNames]);
                            }
                        }
                    }

                    return [
                        'id'                => $task->id,
                        'type'              => 'task', // Tanda bahwa ini task biasa
                        'judul'             => $judul ?? 'Tanpa Judul',
                        'nama_tugas'        => $namaTugas ?? 'Tanpa Nama',
                        'deskripsi'         => $task->deskripsi ?? '',
                        'deadline'          => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                        'status'            => $task->status ?? 'pending',
                        'priority'          => $task->priority ?? 'medium',
                        
                        // Project Data
                        'project_id'        => $task->project_id,
                        'project_name'      => $task->project ? ($task->project->nama ?? 'Project') : 'Tidak ada Project',
                        'project_description' => $task->project ? ($task->project->deskripsi ?? null) : null,
                        'project_deadline'   => $task->project ? ($task->project->deadline ?? null) : null,

                        // Assignee Data
                        'assigned_to'       => $task->assigned_to,
                        'assigned_to_ids'   => $task->assigned_to_ids ?? [$task->assigned_to],
                        'assignee_name'     => $task->assignee ? ($task->assignee->name ?? 'Unknown') : 'Belum ditugaskan',
                        'assigned_names'    => $assignedNames,
                        
                        // Divisi Data
                        'target_divisi_id'  => $task->target_divisi_id,
                        'target_divisi'     => $task->targetDivisi ? ($task->targetDivisi->divisi ?? '-') : '-',
                        
                        // Meta
                        'is_overdue'        => $isOverdue,
                        'catatan'           => $task->catatan ?? null,
                        'created_at'        => $task->created_at ? $task->created_at->format('Y-m-d H:i:s') : null,
                        'updated_at'        => $task->updated_at ? $task->updated_at->format('Y-m-d H:i:s') : null,
                    ];
                } catch (\Exception $e) {
                    Log::error('Error transforming task: ' . $e->getMessage(), [
                        'task_id' => $task->id ?? 'unknown',
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            });

            // 6. Transformasi Data Task Dari Karyawan
            $transformedTasksFromKaryawan = $tasksFromKaryawan->map(function($task) {
                try {
                    $isOverdue = false;
                    if ($task->deadline && !in_array($task->status, ['selesai', 'dibatalkan'])) {
                        try {
                            $isOverdue = \Carbon\Carbon::parse($task->deadline)->isPast();
                        } catch (\Exception $e) {
                            $isOverdue = false;
                        }
                    }
                    
                    // Ensure both judul and nama_tugas are populated
                    $namaTugas = !empty($task->nama_tugas) ? $task->nama_tugas : $task->judul;
                    $judul = !empty($task->judul) ? $task->judul : $task->nama_tugas;

                    return [
                        'id'                => $task->id,
                        'type'              => 'task_from_karyawan', // Tanda bahwa ini dari karyawan
                        'karyawan_id'       => $task->karyawan_id,
                        'created_by'        => $task->karyawan_id,
                        'created_by_name'   => $task->karyawan && isset($task->karyawan->name) ? $task->karyawan->name : 'Unknown',
                        'assignee_name'     => $task->karyawan && isset($task->karyawan->name) ? $task->karyawan->name : 'Unknown',
                        'judul'             => $judul ?? 'Tanpa Judul',
                        'nama_tugas'        => $namaTugas ?? 'Tanpa Nama',
                        'deskripsi'         => $task->deskripsi ?? '',
                        'deadline'          => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                        'status'            => $task->status ?? 'pending',
                        'project_id'        => $task->project_id,
                        'project_name'      => $task->project && isset($task->project->nama) ? $task->project->nama : 'Tidak ada Project',
                        'catatan'           => $task->catatan ?? null,
                        'lampiran'          => $task->lampiran ?? null,
                        'is_overdue'        => $isOverdue,
                        'created_at'        => $task->created_at ? $task->created_at->format('Y-m-d H:i:s') : null,
                        'updated_at'        => $task->updated_at ? $task->updated_at->format('Y-m-d H:i:s') : null,
                    ];
                } catch (\Exception $e) {
                    Log::error('Error transforming karyawan task: ' . $e->getMessage(), [
                        'task_id' => $task->id ?? 'unknown',
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            });

            // 7. Gabungkan kedua collection
            // Convert both collections to arrays, then merge and re-collect
            $allTasksArray = array_merge($transformedTasks->toArray(), $transformedTasksFromKaryawan->toArray());
            
            // Sort by created_at descending
            usort($allTasksArray, function($a, $b) {
                $timeA = strtotime($a['created_at'] ?? '1970-01-01');
                $timeB = strtotime($b['created_at'] ?? '1970-01-01');
                return $timeB - $timeA; // descending
            });

            return response()->json([
                'success' => true,
                'data' => $allTasksArray,
                'total' => count($allTasksArray)
            ]);

        } catch (\Exception $e) {
            Log::error('Error getTasksApi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengambil data tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Dropdown Projects
     */
    public function getProjectsDropdown(Request $request)
    {
        try {
            $user = Auth::user();
            
            Log::info('Fetching projects dropdown', [
                'user_id' => $user->id,
                'divisi_id' => $user->divisi_id
            ]);
            
            // Ambil project yang menjadi tanggung jawab manager divisi ini
            $projects = Project::where('penanggung_jawab_id', $user->id)
                ->whereNull('deleted_at')
                ->select([
                    'id', 
                    'nama', 
                    'deskripsi', 
                    'tanggal_selesai_pengerjaan', 
                    'status_pengerjaan',
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
                    'deadline' => $project->tanggal_selesai_pengerjaan ? $project->tanggal_selesai_pengerjaan->format('Y-m-d H:i:s') : null,
                    'tanggal_selesai' => $project->tanggal_selesai_pengerjaan ? $project->tanggal_selesai_pengerjaan->format('Y-m-d H:i:s') : null,
                    'harga' => $project->harga,
                    'budget' => $project->harga,
                    'progres' => $project->progres,
                    'progress' => $project->progres,
                    'status' => $project->status_pengerjaan,
                    'status_pengerjaan' => $project->status_pengerjaan
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
            // Log the request
            Log::info('=== GET KARYAWAN DROPDOWN CALLED ===', [
                'user_id' => Auth::id(),
                'user' => Auth::user() ? Auth::user()->toArray() : null
            ]);
            
            $user = Auth::user();
            
            if (!$user) {
                Log::error('No authenticated user');
                return response()->json([
                    'success' => false,
                    'message' => 'Not authenticated',
                    'data' => []
                ], 401);
            }
            
            Log::info('User authenticated', [
                'id' => $user->id,
                'role' => $user->role,
                'divisi_id' => $user->divisi_id
            ]);
            
            if (empty($user->divisi_id)) {
                Log::warning('User divisi_id is empty');
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'User tidak terhubung ke divisi'
                ]);
            }
            
            $divisiId = (int)$user->divisi_id;
            Log::info('Querying karyawan', ['divisi_id' => $divisiId, 'role' => 'karyawan']);
            
            $karyawan = User::where('role', 'karyawan')
                            ->where('divisi_id', $divisiId)
                            ->orderBy('name')
                            ->select('id', 'name', 'email', 'divisi_id')
                            ->get();
            
            Log::info('Karyawan query result', [
                'count' => $karyawan->count(),
                'data' => $karyawan->toArray()
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $karyawan,
                'debug' => [
                    'user_id' => $user->id,
                    'divisi_id' => $divisiId,
                    'count' => $karyawan->count()
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('ERROR in getKaryawanDropdown', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'debug' => [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
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
            
            Log::info('=== MANAGER STORE TASK REQUEST ===', $request->all());
            
            // Validasi yang sesuai dengan form
            $validator = Validator::make($request->all(), [
                'project_id'        => 'required|exists:project,id',
                'judul'             => 'nullable|string|max:255',
                'nama_tugas'        => 'required|string|max:255',
                'deskripsi'         => 'required|string',
                'deadline'          => 'required|date',
                'assigned_to'       => 'required|exists:users,id',
                'status'            => 'nullable|in:pending,proses,selesai,dibatalkan',
                'target_divisi_id'  => 'required|exists:divisi,id',
                'catatan'           => 'nullable|string',
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
            
            // Jika judul kosong, default ke nama_tugas
            if (empty($validated['judul'])) {
                $validated['judul'] = $validated['nama_tugas'];
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
     * Store khusus untuk pengelola_tugas.blade.php
     */
    public function createTask(Request $request)
    {
        try {
            $user = Auth::user();
            
            $requestAll = $request->all();
            
            Log::info('=== CREATE TASK FROM FORM ===', $requestAll);
            
            // Get assigned_to values - handle FormData array (assigned_to[]) , array, or JSON-encoded strings
            $assignedToInput = null;
            
            // Check for assigned_to[] (array from FormData) or assigned_to (array or string)
            if (isset($requestAll['assigned_to'])) {
                $assignedToInput = $requestAll['assigned_to'];
                Log::info('Found assigned_to in requestAll', [
                    'value' => $assignedToInput,
                    'is_array' => is_array($assignedToInput),
                    'type' => gettype($assignedToInput),
                ]);
            } else {
                $assignedToInput = $request->input('assigned_to');
                Log::info('Using request->input for assigned_to', [
                    'value' => $assignedToInput,
                    'is_array' => is_array($assignedToInput),
                    'type' => gettype($assignedToInput),
                ]);
            }
            
            Log::info('Assigned To Debug', [
                'raw_input' => $assignedToInput,
                'type' => gettype($assignedToInput),
                'is_array' => is_array($assignedToInput),
                'count' => is_array($assignedToInput) ? count($assignedToInput) : 0,
                'values_if_array' => is_array($assignedToInput) ? implode(',', $assignedToInput) : 'N/A',
            ]);
            
            // Convert to array
            $assignedToValues = [];
            if (is_array($assignedToInput)) {
                $assignedToValues = $assignedToInput;
                Log::info('Converted: already an array', ['count' => count($assignedToValues), 'values' => $assignedToValues]);
            } elseif (is_string($assignedToInput) && !empty($assignedToInput)) {
                // Check if it's a JSON-encoded array
                $decoded = json_decode($assignedToInput, true);
                if (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) {
                    $assignedToValues = $decoded;
                    Log::info('Converted: JSON string decoded', ['count' => count($assignedToValues), 'values' => $assignedToValues]);
                } else {
                    // It's a plain string ID
                    $assignedToValues = [$assignedToInput];
                    Log::info('Converted: plain string ID', ['value' => $assignedToInput]);
                }
            } else {
                Log::warning('Could not convert assigned_to input', ['input' => $assignedToInput, 'type' => gettype($assignedToInput)]);
            }
            
            Log::info('FINAL Assigned To Values', [
                'count' => count($assignedToValues),
                'values' => $assignedToValues,
                'json_encoded' => json_encode($assignedToValues),
            ]);
            
            // Validasi minimal
            $validator = Validator::make($request->all(), [
                'project_id'        => 'required|exists:project,id',
                'judul'             => 'nullable|string|max:255',
                'nama_tugas'        => 'required|string|max:255',
                'deskripsi'         => 'required|string',
                'deadline'          => 'required|date',
                'target_divisi_id'  => 'required|exists:divisi,id',
                'status'            => 'nullable|in:pending,proses,selesai,dibatalkan',
                'priority'          => 'nullable|in:low,medium,high,urgent',
                'catatan'           => 'nullable|string',
                'attachment'        => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,ppt,pptx|max:102400',
            ]);
            
            // Custom validation for assigned_to
            if (empty($assignedToValues)) {
                throw new \Exception('Pilih minimal satu karyawan untuk ditugaskan');
            }
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            
            $validated = $validator->validated();
            
            // Validate each assigned karyawan exists and convert to int
            $validatedAssignedIds = [];
            foreach ($assignedToValues as $karyawanId) {
                $id = (int) $karyawanId;
                if (!\DB::table('users')->where('id', $id)->exists()) {
                    throw new \Exception("Karyawan dengan ID {$id} tidak ditemukan");
                }
                $validatedAssignedIds[] = $id;
            }
            
            // Create SEPARATE task for EACH assigned karyawan
            // Jika 2 karyawan dipilih, akan create 2 task identik dengan assigned_to berbeda
            $createdTasks = [];
            $taskCount = count($validatedAssignedIds);
            
            foreach ($validatedAssignedIds as $karyawanId) {
                $taskData = [
                    'project_id'        => $validated['project_id'],
                    'judul'             => $validated['judul'] ?? $validated['nama_tugas'],
                    'nama_tugas'        => $validated['nama_tugas'],
                    'deskripsi'         => $validated['deskripsi'],
                    'deadline'          => $validated['deadline'],
                    'assigned_to'       => $karyawanId,  // Set to individual karyawan
                    'assigned_to_ids'   => null,  // Not using multi-assign anymore
                    'target_divisi_id'  => $validated['target_divisi_id'],
                    'status'            => $validated['status'] ?? 'pending',
                    'priority'          => $validated['priority'] ?? 'medium',
                    'catatan'           => $validated['catatan'] ?? null,
                    'created_by'        => $user->id,
                    'assigned_by_manager' => $user->id,
                    'target_type'       => 'karyawan',
                    'is_broadcast'      => false,  // Each task is individual
                ];
                
                Log::info('About to create task for karyawan:', [
                    'karyawan_id' => $karyawanId,
                    'project_id' => $validated['project_id'],
                ]);
                
                $task = Task::create($taskData);
                $createdTasks[] = $task;
            }
            
            Log::info('Task created, checking database values:', [
                'task_count' => count($createdTasks),
                'karyawan_count' => count($validatedAssignedIds),
            ]);
            
            // Handle file attachment if provided - attach to ALL created tasks
            if ($request->hasFile('attachment')) {
                try {
                    $file = $request->file('attachment');
                    
                    // Validate file
                    if (!$file->isValid()) {
                        Log::warning('Invalid attachment file', [
                            'error' => $file->getErrorMessage()
                        ]);
                    } else {
                        $filename = $file->getClientOriginalName();
                        $mimeType = $file->getMimeType();
                        $fileSize = $file->getSize();
                        
                        // Store file - will be saved in storage/app/tasks/
                        $path = $file->store('tasks', 'public');
                        
                        // Create TaskFile record untuk SETIAP task
                        foreach ($createdTasks as $createdTask) {
                            TaskFile::create([
                                'task_id' => $createdTask->id,
                                'user_id' => $user->id,
                                'filename' => $filename,
                                'original_name' => $filename,
                                'path' => $path,
                                'size' => $fileSize,
                                'mime_type' => $mimeType,
                            ]);
                            
                            Log::info('Task file attached', [
                                'task_id' => $createdTask->id,
                                'filename' => $filename,
                                'path' => $path,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error saving task file: ' . $e->getMessage());
                    // Don't fail the task creation, just log the error
                }
            }
            
            // Log all created tasks
            foreach ($createdTasks as $createdTask) {
                Log::info('Created task data full:', $createdTask->toArray());
            }
            
            Log::info('Tasks created successfully', [
                'task_count' => count($createdTasks),
                'karyawan_assigned_count' => count($validatedAssignedIds),
                'task_ids' => array_map(fn($t) => $t->id, $createdTasks)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => count($validatedAssignedIds) > 1 
                    ? "Tugas berhasil dibuat untuk " . count($validatedAssignedIds) . " karyawan (masing-masing mendapat task terpisah)" 
                    : 'Tugas berhasil dibuat',
                'tasks' => $createdTasks,
                'task_count' => count($createdTasks)
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
            
            // Handle assigned_to as either single value or array
            $assignedToInput = $request->input('assigned_to');
            
            // Convert to array
            $assignedToArray = [];
            if (is_array($assignedToInput)) {
                $assignedToArray = $assignedToInput;
            } elseif (!empty($assignedToInput)) {
                $assignedToArray = [$assignedToInput];
            }
            
            $validated = $request->validate([
                'judul' => 'nullable|string|max:255',
                'nama_tugas' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,proses,selesai,dibatalkan',
                'priority' => 'nullable|in:low,medium,high,urgent',
                'assigned_to' => 'nullable',
                'project_id' => 'nullable|exists:project,id',
                'catatan' => 'nullable|string'
            ]);
            
            // Default judul to nama_tugas if not provided
            if (!isset($validated['judul']) || empty($validated['judul'])) {
                $validated['judul'] = $validated['nama_tugas'];
            }
            
            // Validate each assigned karyawan exists
            foreach ($assignedToArray as $karyawanId) {
                if (!\DB::table('users')->where('id', $karyawanId)->exists()) {
                    throw new \Exception("Karyawan dengan ID {$karyawanId} tidak ditemukan");
                }
            }
            
            // Update SINGLE task with all assigned karyawan
            $primaryAssignee = !empty($assignedToArray) ? $assignedToArray[0] : $task->assigned_to;
            $updateData = array_merge($validated, [
                'assigned_to' => $primaryAssignee,
                'assigned_to_ids' => !empty($assignedToArray) ? $assignedToArray : [$task->assigned_to],
            ]);
            
            $task->update($updateData);
            
            // Reload relasi
            $task->load('assignee', 'project');
            
            return response()->json([
                'success' => true,
                'message' => count($assignedToArray) > 1 
                    ? "Tugas berhasil diupdate untuk " . count($assignedToArray) . " karyawan"
                    : 'Tugas berhasil diupdate',
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
            // Try to find task including soft-deleted ones
            $task = Task::withTrashed()->find($id);
            
            if (!$task) {
                throw new \Exception("Task dengan ID {$id} tidak ditemukan");
            }
            
            // Force delete if already soft-deleted, otherwise soft-delete
            if ($task->trashed()) {
                $task->forceDelete();
                $message = 'Tugas berhasil dihapus permanen';
            } else {
                $task->delete();
                $message = 'Tugas berhasil dihapus';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error destroy task: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menghapus tugas: ' . $e->getMessage()
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
                'project:id,nama,deskripsi', 
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
     * API: Statistik Tugas (Manager ke Karyawan)
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
    
    
    /**
     * Helper: Get assigned names from assigned_to_ids
     * NOTE: This logic is now inlined in getTasksApi() to avoid closure binding issues
     */
    private function getAssignedNamesForTask($task)
    {
        // This method is kept for backward compatibility but logic is in getTasksApi()
        try {
            $assignedIds = $task->assigned_to_ids;
            
            if (!$assignedIds) {
                return null;
            }
            
            if (is_string($assignedIds)) {
                $assignedIds = json_decode($assignedIds, true) ?? [];
            }
            
            if (!is_array($assignedIds) || empty($assignedIds)) {
                return null;
            }
            
            $users = \DB::table('users')
                ->whereIn('id', $assignedIds)
                ->select('id', 'name')
                ->get();
            
            if ($users->isEmpty()) {
                return null;
            }
            
            $names = array_map(function($id) use ($users) {
                $user = $users->firstWhere('id', $id);
                return $user ? $user->name : null;
            }, $assignedIds);
            
            return implode(', ', array_filter($names));
        } catch (\Exception $e) {
            Log::error('Error getAssignedNamesForTask: ' . $e->getMessage());
            return null;
        }
    }

    // Helper method untuk inisialisasi task acceptances
    private function initializeTaskAcceptances($task)
    {
        // Get list of assignees
        $assignees = [];
        
        if ($task->assigned_to) {
            $assignees[] = $task->assigned_to;
        }
        
        if ($task->assigned_to_ids && is_array($task->assigned_to_ids)) {
            $assignees = array_merge($assignees, $task->assigned_to_ids);
        }

        // Remove duplicates
        $assignees = array_unique($assignees);

        // Create acceptance records untuk setiap assignee
        foreach ($assignees as $userId) {
            TaskAcceptance::firstOrCreate(
                [
                    'task_id' => $task->id,
                    'user_id' => $userId
                ],
                [
                    'status' => 'pending',
                    'accepted_at' => null
                ]
            );
        }
    }
}