<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Task;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class KaryawanController extends Controller
{
    /**
     * Menampilkan data karyawan untuk general manager (dengan filter & pagination).
     */
    public function indexPegawai(Request $request)
    {
        $query = Karyawan::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($divisi = $request->query('divisi')) {
            $query->where('divisi', $divisi);
        }

        $karyawan = $query->orderBy('nama')->paginate(15)->withQueryString();

        return view('general_manajer.data_karyawan', compact('karyawan'));
    }

    /**
     * Store karyawan baru (untuk form tambah karyawan).
     */
    public function storePegawai(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:255',
        ]);

        Karyawan::create($validated);

        return redirect()->route('pegawai.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Edit karyawan (return JSON untuk modal).
     */
    public function editPegawai($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return response()->json($karyawan);
    }

    /**
     * Update karyawan.
     */
    public function updatePegawai(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:255',
        ]);

        $karyawan->update($validated);

        return redirect()->route('pegawai.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    /**
     * Delete karyawan.
     */
    public function destroyPegawai($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('pegawai.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    /**
     * Menampilkan halaman beranda karyawan.
     */
    public function home()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        // 1. Ambil status absensi hari ini
        $absenToday = Absensi::where('user_id', $userId)
                            ->where('tanggal', $today)
                            ->first();

        $attendanceStatus = 'Belum Absen';
        if ($absenToday) {
            $attendanceStatus = $absenToday->status;
        }

        // Hitung Jumlah Ketidakhadiran (Sakit, Izin, Cuti yang disetujui)
        $ketidakhadiranCount = Absensi::where('user_id', $userId)
                                ->where('approval_status', 'approved')
                                ->whereIn('status', ['Cuti', 'Sakit', 'Izin'])
                                ->count();

        // Hitung Jumlah Tugas dari tabel tasks
        $userDivisi = Auth::user()->divisi;
        $tugasCount = Task::where(function($query) use ($userId, $userDivisi) {
                        // Tugas yang ditugaskan langsung ke user
                        $query->where('assigned_to', $userId)
                              // ATAU tugas yang ditugaskan ke divisi user
                              ->orWhere(function($q) use ($userDivisi) {
                                  $q->where('target_type', 'divisi')
                                    ->where('target_divisi', $userDivisi);
                              });
                    })
                    ->whereNotIn('status', ['selesai', 'dibatalkan'])
                    ->count();

        return view('karyawan.home', [
            'attendance_status' => $attendanceStatus,
            'ketidakhadiran_count' => $ketidakhadiranCount,
            'tugas_count' => $tugasCount,
        ]);
    }

    /**
     * Menampilkan halaman absensi karyawan.
     */
    public function absensiPage()
    {
        return view('karyawan.absen');
    }

    /**
     * Menampilkan halaman daftar TUGAS karyawan.
     */
    public function listPage()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            $userDivisi = $user->divisi;
            $userName = $user->name;
            
            Log::info('=== KARYAWAN TUGAS LIST ===', [
                'controller' => 'KaryawanController@listPage',
                'user_id' => $userId,
                'user_name' => $userName,
                'user_divisi' => $userDivisi,
                'role' => $user->role,
                'timestamp' => now()->toDateTimeString(),
            ]);
            
            // LOG 1: Cek data user di database
            $dbUser = User::find($userId);
            Log::info('Database User Check:', [
                'db_user_id' => $dbUser->id,
                'db_user_name' => $dbUser->name,
                'db_user_divisi' => $dbUser->divisi,
                'db_user_role' => $dbUser->role,
                'match_with_auth' => ($dbUser->divisi === $userDivisi) ? 'YES' : 'NO',
            ]);
            
            // LOG 2: Cek total tugas di database
            $totalTasksInDB = Task::count();
            Log::info('Total Tasks in Database:', ['count' => $totalTasksInDB]);
            
            // LOG 3: Cek tugas untuk Digital Marketing secara spesifik
            $marketingTasks = Task::where('target_divisi', 'Digital Marketing')->get();
            Log::info('Marketing Tasks in Database:', [
                'count' => $marketingTasks->count(),
                'tasks' => $marketingTasks->map(function($task) {
                    return [
                        'id' => $task->id,
                        'judul' => $task->judul,
                        'target_type' => $task->target_type,
                        'status' => $task->status,
                    ];
                })->toArray()
            ]);
            
            // PERBAIKAN UTAMA: Query yang lebih komprehensif
            $tasks = Task::where(function($query) use ($userId, $userDivisi) {
                    // 1. Tugas untuk divisi (yang paling penting!)
                    $query->where('target_type', 'divisi')
                          ->where('target_divisi', $userDivisi);
                })
                ->orWhere(function($query) use ($userId) {
                    // 2. Tugas yang ditugaskan langsung ke user
                    $query->where('assigned_to', $userId)
                          ->where('target_type', 'karyawan');
                })
                ->orWhere(function($query) use ($userId) {
                    // 3. Tugas untuk manajer (jika user adalah manajer)
                    $query->where('target_manager_id', $userId)
                          ->where('target_type', 'manager');
                })
                ->with([
                    'creator:id,name', 
                    'assignedUser:id,name,divisi',
                    'targetManager:id,name,divisi'
                ])
                ->orderBy('deadline', 'asc')
                ->get();
            
            // LOG 4: Debug hasil query
            $queryResults = [
                'total_found' => $tasks->count(),
                'by_divisi' => $tasks->where('target_type', 'divisi')->where('target_divisi', $userDivisi)->count(),
                'by_assigned' => $tasks->where('target_type', 'karyawan')->where('assigned_to', $userId)->count(),
                'by_manager' => $tasks->where('target_type', 'manager')->where('target_manager_id', $userId)->count(),
            ];
            
            Log::info('Query Results:', $queryResults);
            
            // LOG 5: Detail setiap tugas yang ditemukan
            foreach ($tasks as $index => $task) {
                Log::info("Task #" . ($index + 1) . " Details:", [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'target_type' => $task->target_type,
                    'target_divisi' => $task->target_divisi,
                    'assigned_to' => $task->assigned_to,
                    'target_manager_id' => $task->target_manager_id,
                    'status' => $task->status,
                    'created_by' => $task->created_by,
                    'creator_name' => $task->creator->name ?? null,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d') : null,
                ]);
            }
            
            // LOG 6: Cek apakah ada masalah dengan nilai divisi
            $allDivisiValues = Task::distinct()->pluck('target_divisi')->filter();
            Log::info('All Divisi Values in Tasks Table:', ['values' => $allDivisiValues->toArray()]);
            
            // LOG 7: Cek tugas yang mungkin terlewat (case sensitivity)
            $caseInsensitiveTasks = Task::whereRaw('LOWER(target_divisi) = ?', [strtolower($userDivisi)])
                ->where('target_type', 'divisi')
                ->get();
                
            Log::info('Case-Insensitive Search Results:', [
                'search_term' => strtolower($userDivisi),
                'found' => $caseInsensitiveTasks->count(),
                'tasks' => $caseInsensitiveTasks->map(function($task) {
                    return [
                        'id' => $task->id,
                        'judul' => $task->judul,
                        'actual_divisi' => $task->target_divisi,
                    ];
                })->toArray()
            ]);
            
            // LOG 8: Jika tidak ada tugas, coba query manual
            if ($tasks->isEmpty()) {
                Log::warning('NO TASKS FOUND! Running diagnostic queries...');
                
                // Query 1: Cek semua tugas untuk melihat struktur
                $allTasksSample = Task::limit(5)->get();
                Log::warning('Sample of All Tasks:', [
                    'sample' => $allTasksSample->map(function($task) {
                        return [
                            'id' => $task->id,
                            'judul' => $task->judul,
                            'target_type' => $task->target_type,
                            'target_divisi' => $task->target_divisi,
                            'status' => $task->status,
                        ];
                    })->toArray()
                ]);
                
                // Query 2: Cek dengan raw SQL untuk menghindari Eloquent issues
                $rawSql = "SELECT * FROM tasks WHERE 
                          (target_type = 'divisi' AND target_divisi = ?) OR
                          (target_type = 'karyawan' AND assigned_to = ?) OR
                          (target_type = 'manager' AND target_manager_id = ?)
                          ORDER BY deadline ASC";
                
                $rawResults = DB::select($rawSql, [$userDivisi, $userId, $userId]);
                Log::warning('Raw SQL Query Results:', [
                    'sql' => $rawSql,
                    'params' => [$userDivisi, $userId, $userId],
                    'count' => count($rawResults),
                ]);
            }
            
            return view('karyawan.list', compact('tasks'));
            
        } catch (\Exception $e) {
            Log::error('CRITICAL ERROR in KaryawanController@listPage: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            
            return view('karyawan.list', [
                'tasks' => collect([]),
                'error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Menampilkan halaman daftar ABSENSI karyawan.
     */
    public function absensiListPage()
    {
        $userId = Auth::id();
        
        $absensis = Absensi::where('user_id', $userId)
                          ->orderBy('tanggal', 'desc')
                          ->paginate(15);

        return view('karyawan.absensi_list', compact('absensis'));
    }

    /**
     * Menampilkan halaman detail absensi karyawan.
     */
    public function detailPage($id)
    {
        $absensi = Absensi::findOrFail($id);
        
        if ($absensi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        return view('karyawan.detail', compact('absensi'));
    }

    /**
     * Test endpoint untuk debugging database.
     */
    public function testDatabase()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            
            Log::info('=== DATABASE TEST ENDPOINT CALLED ===', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_divisi' => $user->divisi,
            ]);
            
            $results = [];
            
            // 1. Test: Cek user di database
            $results['user_check'] = [
                'table' => 'users',
                'query' => "SELECT id, name, divisi, role FROM users WHERE id = {$user->id}",
                'data' => User::select('id', 'name', 'divisi', 'role')->find($user->id),
            ];
            
            // 2. Test: Cek semua nilai divisi di tabel tasks
            $results['all_divisi_values'] = [
                'table' => 'tasks',
                'query' => "SELECT DISTINCT target_divisi FROM tasks WHERE target_divisi IS NOT NULL AND target_divisi != ''",
                'data' => DB::table('tasks')
                    ->select('target_divisi')
                    ->distinct()
                    ->whereNotNull('target_divisi')
                    ->where('target_divisi', '!=', '')
                    ->get()
                    ->pluck('target_divisi')
                    ->toArray(),
            ];
            
            // 3. Test: Query spesifik untuk divisi user
            $userDivisi = $user->divisi;
            $results['tasks_for_user_divisi'] = [
                'table' => 'tasks',
                'query' => "SELECT * FROM tasks WHERE target_divisi = '{$userDivisi}' AND target_type = 'divisi'",
                'data' => Task::where('target_divisi', $userDivisi)
                    ->where('target_type', 'divisi')
                    ->select('id', 'judul', 'target_type', 'target_divisi', 'status', 'created_at')
                    ->get()
                    ->toArray(),
            ];
            
            // 4. Test: Query case-insensitive
            $results['tasks_case_insensitive'] = [
                'table' => 'tasks',
                'query' => "SELECT * FROM tasks WHERE LOWER(target_divisi) = LOWER('{$userDivisi}')",
                'data' => DB::table('tasks')
                    ->select('id', 'judul', 'target_type', 'target_divisi', 'status')
                    ->whereRaw('LOWER(target_divisi) = ?', [strtolower($userDivisi)])
                    ->get()
                    ->toArray(),
            ];
            
            // 5. Test: Total tugas di database
            $results['total_tasks'] = [
                'table' => 'tasks',
                'query' => "SELECT COUNT(*) as total FROM tasks",
                'data' => ['total' => Task::count()],
            ];
            
            // 6. Test: Cek jika ada tugas untuk user ID
            $results['tasks_for_user_id'] = [
                'table' => 'tasks',
                'query' => "SELECT * FROM tasks WHERE assigned_to = {$user->id}",
                'data' => Task::where('assigned_to', $user->id)
                    ->select('id', 'judul', 'target_type', 'assigned_to', 'status')
                    ->get()
                    ->toArray(),
            ];
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'divisi' => $user->divisi,
                    'role' => $user->role,
                ],
                'tests' => $results,
                'timestamp' => now()->toDateTimeString(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Database Test Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
    
    /**
     * Create test task for debugging.
     */
    public function createTestTask(Request $request)
    {
        try {
            $user = Auth::user();
            $userDivisi = $user->divisi;
            
            Log::info('=== CREATING TEST TASK ===', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_divisi' => $userDivisi,
            ]);
            
            // Buat tugas test
            $task = Task::create([
                'judul' => '[TEST] Tugas untuk ' . $userDivisi . ' - ' . now()->format('d/m/Y H:i'),
                'deskripsi' => 'Ini adalah tugas test yang dibuat otomatis untuk debugging. Divisi: ' . $userDivisi,
                'deadline' => now()->addDays(7),
                'status' => 'pending',
                'target_type' => 'divisi',
                'target_divisi' => $userDivisi,
                'created_by' => $user->id,
                'is_broadcast' => true,
            ]);
            
            Log::info('Test Task Created Successfully:', [
                'task_id' => $task->id,
                'task_judul' => $task->judul,
                'target_divisi' => $task->target_divisi,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test task created successfully!',
                'task' => [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'target_divisi' => $task->target_divisi,
                    'target_type' => $task->target_type,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Create Test Task Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =================================================================
    // METHOD UNTUK API (DIPANGGIL OLEH JAVASCRIPT)
    // =================================================================

    /**
     * Mengambil status absensi hari ini.
     */
    public function getTodayStatus()
    {
        $today = now()->toDateString();
        $absen = Absensi::where('user_id', Auth::id())
                        ->where('tanggal', $today)
                        ->first();

        if (!$absen) {
            return response()->json([
                'jam_masuk' => null,
                'jam_pulang' => null,
                'status' => 'Belum Absen',
                'status_type' => 'none',
                'late_minutes' => 0,
                'approval_status' => 'approved',
            ]);
        }

        return response()->json([
            'jam_masuk' => $absen->jam_masuk,
            'jam_pulang' => $absen->jam_pulang,
            'status' => $absen->status,
            'status_type' => $absen->status_type,
            'late_minutes' => $absen->late_minutes,
            'approval_status' => $absen->approval_status,
        ]);
    }

    /**
     * Mengambil riwayat absensi.
     */
    public function getHistory()
    {
        $history = Absensi::where('user_id', Auth::id())
                          ->orderBy('tanggal', 'desc')
                          ->get()
                          ->map(function ($item) {
                              return [
                                  'id' => $item->id,
                                  'date' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                                  'checkIn' => $item->jam_masuk,
                                  'checkOut' => $item->jam_pulang,
                                  'status' => $item->status,
                                  'statusType' => $item->status_type,
                                  'lateMinutes' => $item->late_minutes,
                                  'isEarlyCheckout' => $item->is_early_checkout,
                                  'earlyCheckoutReason' => $item->early_checkout_reason,
                                  'approvalStatus' => $item->approval_status,
                                  'userName' => $item->name,
                                  'reason' => $item->reason,
                                  'location' => $item->location,
                                  'purpose' => $item->purpose,
                              ];
                          })
                          ->all();

        return response()->json($history);
    }

    /**
     * Mengambil data untuk dashboard karyawan via API.
     */
    public function getDashboardData()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        $absenToday = Absensi::where('user_id', $userId)
                            ->where('tanggal', $today)
                            ->first();

        $attendanceStatus = 'Belum Absen';
        if ($absenToday) {
            $attendanceStatus = $absenToday->status;
        }

        $ketidakhadiranCount = Absensi::where('user_id', $userId)
                                ->where('approval_status', 'approved')
                                ->whereIn('status', ['Cuti', 'Sakit', 'Izin'])
                                ->count();

        $userDivisi = Auth::user()->divisi;
        $tugasCount = Task::where(function($query) use ($userId, $userDivisi) {
                        $query->where('assigned_to', $userId)
                              ->orWhere(function($q) use ($userDivisi) {
                                  $q->where('target_type', 'divisi')
                                    ->where('target_divisi', $userDivisi);
                              });
                    })
                    ->whereNotIn('status', ['selesai', 'dibatalkan'])
                    ->count();

        return response()->json([
            'attendance_status' => $attendanceStatus,
            'ketidakhadiran_count' => $ketidakhadiranCount,
            'tugas_count' => $tugasCount,
        ]);
    }

    /**
     * Mengambil status pengajuan (pending, approved, rejected).
     */
    public function getPengajuanStatus()
    {
        $userId = Auth::id();
        
        $pendingSubmissions = Absensi::where('user_id', $userId)
                                    ->where('approval_status', 'pending')
                                    ->orderBy('tanggal', 'desc')
                                    ->get()
                                    ->map(function ($item) {
                                        return [
                                            'id' => $item->id,
                                            'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                                            'status' => $item->status,
                                            'approval_status' => $item->approval_status,
                                            'reason' => $item->reason,
                                        ];
                                    });

        $recentSubmissions = Absensi::where('user_id', $userId)
                                    ->whereIn('approval_status', ['approved', 'rejected'])
                                    ->where('tanggal', '>=', now()->subDays(7)->toDateString())
                                    ->orderBy('tanggal', 'desc')
                                    ->get()
                                    ->map(function ($item) {
                                        return [
                                            'id' => $item->id,
                                            'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                                            'status' => $item->status,
                                            'approval_status' => $item->approval_status,
                                            'reason' => $item->reason,
                                        ];
                                    });

        return response()->json([
            'pending' => $pendingSubmissions,
            'recent' => $recentSubmissions,
        ]);
    }

    /**
     * Proses absen masuk via AJAX.
     */
    public function absenMasukApi(Request $request)
    {
        try {
            $today = now()->toDateString();
            
            $existingAbsence = Absensi::where('user_id', Auth::id())
                                      ->where('tanggal', $today)
                                      ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Dinas Luar'])
                                      ->where('approval_status', 'approved')
                                      ->first();

            if ($existingAbsence) {
                return response()->json([
                    'message' => 'Anda tidak dapat melakukan absen masuk karena telah mengajukan "' . $existingAbsence->status . '" pada hari ini.'
                ], 403);
            }
            
            $nowLocal = now();
            $userName = Auth::user()->name;

            $cek = Absensi::where('user_id', Auth::id())->where('tanggal', $today)->first();
            if ($cek && $cek->jam_masuk) {
                return response()->json(['message' => 'Kamu sudah absen masuk hari ini'], 409);
            }

            $workStartTime = $nowLocal->copy()->setTime(9, 5, 0); 
            $lateMinutes = $nowLocal->greaterThan($workStartTime) ? $workStartTime->diffInMinutes($nowLocal) : 0;
            
            $status = $lateMinutes > 0 ? 'Terlambat' : 'Tepat Waktu';
            $statusType = $lateMinutes > 0 ? 'late' : 'on-time';

            Absensi::updateOrCreate(
                ['user_id' => Auth::id(), 'tanggal' => $today],
                [
                    'name' => $userName,
                    'jam_masuk' => $nowLocal,
                    'status' => $status,
                    'status_type' => $statusType,
                    'late_minutes' => $lateMinutes,
                    'approval_status' => 'approved',
                ]
            );

            return response()->json([
                'message' => 'Absen masuk berhasil!',
                'data' => [
                    'time' => $nowLocal->toDateTimeString(),
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server. Silakan coba lagi.'], 500);
        }
    }

    /**
     * Proses absen pulang via AJAX.
     */
    public function absenPulangApi(Request $request)
    {
        try {
            $today = now()->toDateString();
            $nowLocal = now();
            $userName = Auth::user()->name;

            $absen = Absensi::where('user_id', Auth::id())->where('tanggal', $today)->first();

            if (!$absen || !$absen->jam_masuk) {
                return response()->json(['message' => 'Anda belum melakukan absen masuk hari ini.'], 400);
            }

            if ($absen->jam_pulang) {
                return response()->json(['message' => 'Anda sudah absen pulang hari ini.'], 409);
            }

            $workEndTime = $nowLocal->copy()->setTime(17, 0, 0);
            $isEarlyCheckout = $nowLocal->lessThan($workEndTime);
            
            $reason = null;
            if ($isEarlyCheckout) {
                $request->validate([
                    'reason' => 'required|string|max:255',
                ]);
                $reason = $request->input('reason');
            }

            $absen->update([
                'name' => $userName,
                'jam_pulang' => $nowLocal,
                'is_early_checkout' => $isEarlyCheckout,
                'early_checkout_reason' => $reason,
                'approval_status' => 'approved',
            ]);

            return response()->json([
                'message' => 'Absen pulang berhasil!',
                'data' => [
                    'time' => $nowLocal->toDateTimeString(),
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server. Silakan coba lagi.'], 500);
        }
    }

    /**
     * Proses pengajuan izin (bisa multi-hari).
     */
    public function submitIzinApi(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $userName = Auth::user()->name;
        $period = CarbonPeriod::create($request->start_date, $request->end_date);

        DB::beginTransaction();
        try {
            foreach ($period as $date) {
                Absensi::updateOrCreate(
                    ['user_id' => Auth::id(), 'tanggal' => $date->toDateString()],
                    [
                        'name' => $userName,
                        'status' => $request->type,
                        'status_type' => 'absent',
                        'reason' => $request->reason,
                        'approval_status' => 'pending',
                    ]
                );
            }
            DB::commit();

            return response()->json(['message' => 'Pengajuan izin untuk ' . $period->count() . ' hari berhasil dikirim dan menunggu persetujuan admin.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal mengajukan izin. Terjadi kesalahan pada server.'], 500);
        }
    }

    /**
     * Proses pengajuan dinas luar (bisa multi-hari).
     */
    public function submitDinasApi(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string',
            'purpose' => 'required|string',
            'description' => 'required|string',
        ]);

        $userName = Auth::user()->name;
        $period = CarbonPeriod::create($request->start_date, $request->end_date);
        
        DB::beginTransaction();
        try {
            foreach ($period as $date) {
                Absensi::updateOrCreate(
                    ['user_id' => Auth::id(), 'tanggal' => $date->toDateString()],
                    [
                        'name' => $userName,
                        'status' => 'Dinas Luar',
                        'status_type' => 'absent',
                        'reason' => $request->description,
                        'location' => $request->location,
                        'purpose' => $request->purpose,
                        'approval_status' => 'pending',
                    ]
                );
            }
            DB::commit();

            return response()->json(['message' => 'Pengajuan dinas luar untuk ' . $period->count() . ' hari berhasil dikirim dan menunggu persetujuan admin.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal mengajukan dinas luar. Terjadi kesalahan pada server.'], 500);
        }
    }
    
    /**
     * API untuk mendapatkan tugas karyawan (digunakan oleh frontend)
     */
    public function apiGetTasks()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            $userDivisi = $user->divisi;
            
            Log::info('=== API GET TASKS FOR KARYAWAN ===', [
                'user_id' => $userId,
                'user_divisi' => $userDivisi,
            ]);
            
            $tasks = Task::where(function($query) use ($userId, $userDivisi) {
                    $query->where('target_type', 'divisi')
                          ->where('target_divisi', $userDivisi);
                })
                ->orWhere(function($query) use ($userId) {
                    $query->where('assigned_to', $userId)
                          ->where('target_type', 'karyawan');
                })
                ->orWhere(function($query) use ($userId) {
                    $query->where('target_manager_id', $userId)
                          ->where('target_type', 'manager');
                })
                ->with([
                    'creator:id,name', 
                    'assignedUser:id,name,divisi',
                    'targetManager:id,name,divisi'
                ])
                ->orderBy('deadline', 'asc')
                ->get();
            
            // Transform data untuk frontend
            $transformedTasks = $tasks->map(function($task) use ($userId, $userDivisi) {
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
                
                $isOverdue = $task->deadline && 
                             now()->gt($task->deadline) && 
                             !in_array($task->status, ['selesai', 'dibatalkan']);
                
                $isForMe = false;
                if ($task->target_type === 'divisi') {
                    $isForMe = strtolower($task->target_divisi) === strtolower($userDivisi);
                } elseif ($task->target_type === 'manager' && $task->target_manager_id === $userId) {
                    $isForMe = true;
                } elseif ($task->target_type === 'karyawan' && $task->assigned_to === $userId) {
                    $isForMe = true;
                }
                
                return [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status' => $task->status,
                    'target_type' => $task->target_type,
                    'target_divisi' => $task->target_divisi,
                    'assignee_text' => $assigneeText,
                    'assignee_divisi' => $assigneeDivisi,
                    'creator_name' => $task->creator->name ?? '-',
                    'is_overdue' => $isOverdue,
                    'is_for_me' => $isForMe,
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                ];
            });
            
            Log::info('API Response Tasks Count:', ['count' => $transformedTasks->count()]);
            
            return response()->json($transformedTasks);
            
        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load tasks: ' . $e->getMessage()
            ], 500);
        }
    }
}