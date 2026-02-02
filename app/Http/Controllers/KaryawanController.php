<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Task;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Karyawan;
use App\Models\CatatanRapat;
use App\Models\Pengumuman;
use App\Models\PengumumanUser;
use App\Models\Cuti;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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
        $user = Auth::user(); // Get the full user object
        $userRole = $user->role; // Get the user's role

        // 1. Ambil status absensi hari ini
        $absenToday = Absensi::where('user_id', $userId)
            ->where('tanggal', $today)
            ->first();

        $attendanceStatus = 'Belum Absen';
        if ($absenToday) {
            if ($absenToday->jam_masuk) {
                // Hitung keterlambatan
                $jamMasuk = Carbon::parse($absenToday->jam_masuk);
                $jamBatas = Carbon::parse('08:00');
                $attendanceStatus = $jamMasuk->gt($jamBatas) ? 'Terlambat' : 'Tepat Waktu';
            } elseif ($absenToday->jenis_ketidakhadiran) {
                // Gunakan jenis_ketidakhadiran untuk label
                $attendanceStatus = match($absenToday->jenis_ketidakhadiran) {
                    'cuti' => 'Cuti',
                    'sakit' => 'Sakit',
                    'izin' => 'Izin',
                    'dinas-luar' => 'Dinas Luar',
                    'lainnya' => 'Tidak Hadir',
                    default => 'Tidak Hadir',
                };
            }
        }

        // Hitung Jumlah Ketidakhadiran (Sakit, Izin, Cuti yang disetujui) - PERBAIKAN
        $ketidakhadiranCount = Absensi::where('user_id', $userId)
                                ->where('approval_status', 'approved')
                                ->whereIn('jenis_ketidakhadiran', ['cuti', 'sakit', 'izin']) // PERBAIKAN
                                ->count();

        // Hitung Jumlah Tugas dari tabel tasks - SOLUSI UNTUK KOLOM target_divisi
        $userDivisi = $user->divisi;
        $tugasCount = Task::where(function ($query) use ($userId, $userDivisi) {
            // Tugas yang ditugaskan langsung ke user
            $query->where('assigned_to', $userId)
                // ATAU tugas yang ditugaskan ke divisi user
                ->orWhere(function ($q) use ($userDivisi) {
                    $q->where('target_type', 'divisi')
                        // PERBAIKAN: Gunakan kolom yang tersedia di database
                        ->where(function($subQuery) use ($userDivisi) {
                            // Coba beberapa kemungkinan nama kolom
                            if (Schema::hasColumn('tasks', 'target_divisi')) {
                                $subQuery->where('target_divisi', $userDivisi);
                            } elseif (Schema::hasColumn('tasks', 'divisi')) {
                                $subQuery->where('divisi', $userDivisi);
                            } elseif (Schema::hasColumn('tasks', 'target_id')) {
                                $subQuery->where('target_id', $userDivisi);
                            }
                        });
                });
        })
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->count();

        // Additional data based on role
        $roleBasedData = [];

        if ($userRole === 'general_manager') {
            // Get data for general manager
            $roleBasedData['totalKaryawan'] = Karyawan::count();
            $roleBasedData['totalDivisi'] = Karyawan::distinct('divisi')->count('divisi');
            $roleBasedData['pendingApprovals'] = Absensi::where('approval_status', 'pending')->count();
        } elseif ($userRole === 'manager') {
            // Get data for manager
            $roleBasedData['teamMembers'] = Karyawan::where('divisi', $userDivisi)->count();
            $roleBasedData['teamPendingApprovals'] = Absensi::join('users', 'absensis.user_id', '=', 'users.id')
                ->where('users.divisi', $userDivisi)
                ->where('absensis.approval_status', 'pending')
                ->count();
        }

        return view('karyawan.home', [
            'attendance_status' => $attendanceStatus,
            'ketidakhadiran_count' => $ketidakhadiranCount,
            'tugas_count' => $tugasCount,
            'user_role' => $userRole,
            'user_divisi' => $userDivisi,
            'role_based_data' => $roleBasedData,
        ]);
    }

    /**
     * Menampilkan halaman absensi karyawan.
     */
    public function absensiPage(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Cek absensi hari ini
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();
        
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $riwayatAbsensi = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Hitung statistik - SESUAIKAN DENGAN STRUKTUR TABEL
        
        // 1. Total Hadir = ada jam_masuk DAN approval_status = approved
        $totalHadir = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereNotNull('jam_masuk') // ADA CHECK-IN
            ->where('approval_status', 'approved') // DISETUJUI
            ->count();
    
        // 2. Total Izin = jenis_ketidakhadiran = 'izin' DAN approval_status = approved
        $totalIzin = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('jenis_ketidakhadiran', 'izin')
            ->where('approval_status', 'approved')
            ->count();
    
        // 3. Total Sakit
        $totalSakit = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('jenis_ketidakhadiran', 'sakit')
            ->where('approval_status', 'approved')
            ->count();
    
        // 4. Total Cuti
        $totalCuti = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('jenis_ketidakhadiran', 'cuti')
            ->where('approval_status', 'approved')
            ->count();
    
        // 5. Total Dinas Luar
        $totalDinasLuar = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('jenis_ketidakhadiran', 'dinas-luar')
            ->where('approval_status', 'approved')
            ->count();
    
        return view('karyawan.absen', [
            'on_leave' => false,
            'cuti_details' => null,
            'absensiHariIni' => $absensiHariIni,
            'riwayatAbsensi' => $riwayatAbsensi,
            'totalHadir' => $totalHadir,
            'totalIzin' => $totalIzin,
            'totalSakit' => $totalSakit,
            'totalCuti' => $totalCuti,
            'totalDinasLuar' => $totalDinasLuar,
        ]);
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
            
            // LOG 2: Cek struktur tabel tasks
            $tableColumns = Schema::getColumnListing('tasks');
            Log::info('Tasks Table Columns:', $tableColumns);
            
            // LOG 3: Cek total tugas di database
            $totalTasksInDB = Task::count();
            Log::info('Total Tasks in Database:', ['count' => $totalTasksInDB]);
            
            // LOG 4: Cek tugas untuk divisi secara spesifik
            $divisiTasks = Task::where('target_type', 'divisi')->get();
            Log::info('Divisi Tasks in Database:', [
                'count' => $divisiTasks->count(),
                'tasks' => $divisiTasks->map(function($task) {
                    return [
                        'id' => $task->id,
                        'judul' => $task->judul,
                        'target_type' => $task->target_type,
                        'target_divisi' => $task->target_divisi ?? null,
                        'divisi' => $task->divisi ?? null,
                        'target_id' => $task->target_id ?? null,
                        'status' => $task->status,
                    ];
                })->toArray()
            ]);
            
            // PERBAIKAN UTAMA: Query yang fleksibel berdasarkan kolom yang ada
            $tasks = Task::where(function ($query) use ($userId, $userDivisi, $tableColumns) {
                // 1. Tugas untuk divisi (berdasarkan kolom yang tersedia)
                if (in_array('target_divisi', $tableColumns)) {
                    // Jika kolom target_divisi ada
                    $query->where('target_type', 'divisi')
                        ->where('target_divisi', $userDivisi);
                } elseif (in_array('divisi', $tableColumns)) {
                    // Jika kolom divisi ada
                    $query->where('target_type', 'divisi')
                        ->where('divisi', $userDivisi);
                } elseif (in_array('target_id', $tableColumns)) {
                    // Jika kolom target_id ada (dan berisi nama divisi)
                    $query->where('target_type', 'divisi')
                        ->where('target_id', $userDivisi);
                }
            })
                ->orWhere(function ($query) use ($userId) {
                    // 2. Tugas yang ditugaskan langsung ke user
                    $query->where('assigned_to', $userId)
                        ->where('target_type', 'karyawan');
                })
                ->orWhere(function ($query) use ($userId) {
                    // 3. Tugas untuk manajer (jika user adalah manajer)
                    if (Schema::hasColumn('tasks', 'target_manager_id')) {
                        $query->where('target_manager_id', $userId)
                            ->where('target_type', 'manager');
                    }
                })
                ->with([
                    'creator:id,name',
                    'assignedUser:id,name,divisi',
                    'targetManager:id,name,divisi'
                ])
                ->orderBy('deadline', 'asc')
                ->get();

            Log::info('Final Query Results:', [
                'total_tasks_found' => $tasks->count(),
                'tasks_for_user' => $tasks->count()
            ]);

            return view('karyawan.list', compact('tasks'));

        } catch (\Exception $e) {
            Log::error('CRITICAL ERROR in KaryawanController@listPage: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

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
     * Helper method untuk cek apakah user sedang cuti hari ini
     */
    private function checkIfOnLeaveToday($userId)
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $cuti = Cuti::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->first();
        
        if ($cuti) {
            return [
                'on_leave' => true,
                'details' => $cuti
            ];
        }
        
        return [
            'on_leave' => false,
            'details' => null
        ];
    }

    // =================================================================
    // API UNTUK HALAMAN ABSENSI (FRONTEND JAVASCRIPT)
    // =================================================================

    /**
     * API: Status hari ini dengan data lengkap (untuk frontend)
     */
    public function getTodayStatusApi()
    {
        try {
            $userId = Auth::id();
            $today = now()->toDateString();
            
            $absen = Absensi::where('user_id', $userId)
                ->where('tanggal', $today)
                ->first();

            if (!$absen) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                        'status' => 'Belum Absen',
                        'late_minutes' => 0,
                        'is_late' => false,
                        'approval_status' => 'approved',
                        'jenis_ketidakhadiran' => null,
                        'jenis_ketidakhadiran_label' => null,
                        'tanggal' => $today,
                    ]
                ]);
            }

            // Tentukan status
            $status = 'Belum Absen';
            $lateMinutes = 0;
            $isLate = false;
            
            if ($absen->jam_masuk) {
                // Hitung keterlambatan
                $jamMasuk = Carbon::parse($absen->jam_masuk);
                $jamBatas = Carbon::parse('08:00');
                $lateMinutes = $jamMasuk->gt($jamBatas) ? $jamMasuk->diffInMinutes($jamBatas) : 0;
                $isLate = $lateMinutes > 0;
                $status = $isLate ? 'Terlambat' : 'Tepat Waktu';
            } elseif ($absen->jenis_ketidakhadiran) {
                $status = match($absen->jenis_ketidakhadiran) {
                    'cuti' => 'Cuti',
                    'sakit' => 'Sakit',
                    'izin' => 'Izin',
                    'dinas-luar' => 'Dinas Luar',
                    default => 'Tidak Hadir',
                };
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'jam_masuk' => $absen->jam_masuk,
                    'jam_pulang' => $absen->jam_pulang,
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'is_late' => $isLate,
                    'approval_status' => $absen->approval_status,
                    'jenis_ketidakhadiran' => $absen->jenis_ketidakhadiran,
                    'jenis_ketidakhadiran_label' => match($absen->jenis_ketidakhadiran) {
                        'cuti' => 'Cuti',
                        'sakit' => 'Sakit',
                        'izin' => 'Izin',
                        'dinas-luar' => 'Dinas Luar',
                        default => null,
                    },
                    'reason' => $absen->reason,
                    'keterangan' => $absen->keterangan,
                    'tanggal' => $absen->tanggal,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Today Status API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load today status',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * API: Mengambil riwayat absensi (untuk frontend)
     */
    public function getHistoryApi(Request $request)
    {
        try {
            $userId = Auth::id();
            
            // Default: bulan ini
            $filter = $request->query('filter', 'month');
            $month = $request->query('month', date('m'));
            $year = $request->query('year', date('Y'));
            
            $query = Absensi::where('user_id', $userId);
            
            // Terapkan filter
            if ($filter === 'custom' && $month && $year) {
                // Filter bulan dan tahun spesifik
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } elseif ($filter === 'week') {
                // Minggu ini
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } elseif ($filter === 'month') {
                // Bulan ini (default)
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } elseif ($filter === 'year') {
                // Tahun ini
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            }
            
            // Get data
            $history = $query->orderBy('tanggal', 'desc')
                            ->get()
                            ->map(function ($item) {
                                // Tentukan status
                                $status = 'Tidak Hadir';
                                $lateMinutes = 0;
                                $isLate = false;
                                
                                if ($item->jam_masuk) {
                                    $jamMasuk = Carbon::parse($item->jam_masuk);
                                    $jamBatas = Carbon::parse('08:00');
                                    $lateMinutes = $jamMasuk->gt($jamBatas) ? $jamMasuk->diffInMinutes($jamBatas) : 0;
                                    $isLate = $lateMinutes > 0;
                                    $status = $isLate ? 'Terlambat' : 'Tepat Waktu';
                                } elseif ($item->jenis_ketidakhadiran) {
                                    $status = match($item->jenis_ketidakhadiran) {
                                        'cuti' => 'Cuti',
                                        'sakit' => 'Sakit',
                                        'izin' => 'Izin',
                                        'dinas-luar' => 'Dinas Luar',
                                        default => 'Tidak Hadir',
                                    };
                                }
                                
                                // Format untuk frontend
                                return [
                                    'id' => $item->id,
                                    'tanggal' => $item->tanggal,
                                    'jam_masuk' => $item->jam_masuk,
                                    'jam_pulang' => $item->jam_pulang,
                                    'status' => $status,
                                    'late_minutes' => $lateMinutes,
                                    'is_late' => $isLate,
                                    'jenis_ketidakhadiran' => $item->jenis_ketidakhadiran,
                                    'jenis_ketidakhadiran_label' => match($item->jenis_ketidakhadiran) {
                                        'cuti' => 'Cuti',
                                        'sakit' => 'Sakit',
                                        'izin' => 'Izin',
                                        'dinas-luar' => 'Dinas Luar',
                                        default => null,
                                    },
                                    'approval_status' => $item->approval_status,
                                    'reason' => $item->reason,
                                    'keterangan' => $item->keterangan,
                                ];
                            });

            return response()->json([
                'success' => true,
                'data' => $history,
                'filter' => $filter,
                'count' => $history->count()
            ]);

        } catch (\Exception $e) {
            Log::error('History API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load attendance history',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * API: Proses absen masuk via AJAX
     */
    public function absenMasukApi(Request $request)
    {
        try {
            $user = Auth::user();
            $today = now()->toDateString();
            
            // 1. Cek apakah user sedang cuti hari ini
            $cutiCheck = $this->checkIfOnLeaveToday($user->id);
            if ($cutiCheck['on_leave']) {
                $cuti = $cutiCheck['details'];
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sedang cuti dari ' . 
                        Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') . 
                        ' sampai ' . 
                        Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') . 
                        '. Tidak dapat melakukan absensi.',
                    'cuti_details' => [
                        'tanggal_mulai' => $cuti->tanggal_mulai,
                        'tanggal_selesai' => $cuti->tanggal_selesai,
                        'tipe_cuti' => $cuti->tipe_cuti,
                        'alasan' => $cuti->alasan
                    ]
                ], 403);
            }
            
            // 2. Cek apakah sudah ada pengajuan ketidakhadiran yang disetujui
            $existingAbsence = Absensi::where('user_id', $user->id)
                                      ->where('tanggal', $today)
                                      ->whereNotNull('jenis_ketidakhadiran')
                                      ->where('approval_status', 'approved')
                                      ->first();

            if ($existingAbsence) {
                $jenisLabel = match($existingAbsence->jenis_ketidakhadiran) {
                    'cuti' => 'Cuti',
                    'sakit' => 'Sakit',
                    'izin' => 'Izin',
                    'dinas-luar' => 'Dinas Luar',
                    default => 'Ketidakhadiran',
                };
                
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat melakukan absen masuk karena telah mengajukan "' . $jenisLabel . '" pada hari ini.'
                ], 403);
            }

            $nowLocal = now();
            $userName = $user->name;

            $cek = Absensi::where('user_id', $user->id)
                ->where('tanggal', $today)
                ->first();
                
            if ($cek && $cek->jam_masuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamu sudah absen masuk hari ini'
                ], 409);
            }

            $workStartTime = $nowLocal->copy()->setTime(9, 5, 0);
            $lateMinutes = $nowLocal->greaterThan($workStartTime) ? $workStartTime->diffInMinutes($nowLocal) : 0;

            $status = $lateMinutes > 0 ? 'Terlambat' : 'Tepat Waktu';

            $absen = Absensi::updateOrCreate(
                ['user_id' => $user->id, 'tanggal' => $today],
                [
                    'jam_masuk' => $nowLocal,
                    'approval_status' => 'approved',
                    'status' => 'hadir'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Absen masuk berhasil!',
                'data' => [
                    'id' => $absen->id,
                    'jam_masuk' => $absen->jam_masuk,
                    'jam_pulang' => $absen->jam_pulang,
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'is_late' => $lateMinutes > 0,
                    'tanggal' => $absen->tanggal,
                    'approval_status' => $absen->approval_status,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Absen Masuk Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * API: Proses absen pulang via AJAX
     */
    public function absenPulangApi(Request $request)
    {
        try {
            $user = Auth::user();
            $today = now()->toDateString();
            
            // 1. Cek apakah user sedang cuti hari ini
            $cutiCheck = $this->checkIfOnLeaveToday($user->id);
            if ($cutiCheck['on_leave']) {
                $cuti = $cutiCheck['details'];
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sedang cuti dari ' . 
                        Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') . 
                        ' sampai ' . 
                        Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') . 
                        '. Tidak dapat melakukan absensi.',
                    'cuti_details' => [
                        'tanggal_mulai' => $cuti->tanggal_mulai,
                        'tanggal_selesai' => $cuti->tanggal_selesai,
                        'tipe_cuti' => $cuti->tipe_cuti,
                        'alasan' => $cuti->alasan
                    ]
                ], 403);
            }

            $nowLocal = now();
            $userName = $user->name;

            $absen = Absensi::where('user_id', $user->id)
                ->where('tanggal', $today)
                ->first();

            if (!$absen || !$absen->jam_masuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum melakukan absen masuk hari ini.'
                ], 400);
            }

            if ($absen->jam_pulang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah absen pulang hari ini.'
                ], 409);
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
                'jam_pulang' => $nowLocal,
                'is_early_checkout' => $isEarlyCheckout,
                'early_checkout_reason' => $reason,
                'approval_status' => 'approved',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absen pulang berhasil!',
                'data' => [
                    'id' => $absen->id,
                    'jam_masuk' => $absen->jam_masuk,
                    'jam_pulang' => $absen->jam_pulang,
                    'status' => 'Selesai',
                    'tanggal' => $absen->tanggal,
                    'approval_status' => $absen->approval_status,
                    'is_early_checkout' => $isEarlyCheckout,
                    'early_checkout_reason' => $reason,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Absen Pulang Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * API: Proses pengajuan izin (bisa multi-hari)
     */
    public function submitIzinApi(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'keterangan' => 'required|string',
            'jenis' => 'required|string|in:sakit,izin',
        ]);

        $user = Auth::user();
        $period = CarbonPeriod::create($request->tanggal, $request->tanggal_akhir);

        // Validasi: Cek apakah ada tanggal dalam periode yang sudah termasuk cuti
        foreach ($period as $date) {
            $dateStr = $date->toDateString();
            
            // Cek apakah sudah ada cuti yang disetujui di tanggal ini
            $existingCuti = Cuti::where('user_id', $user->id)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $dateStr)
                ->whereDate('tanggal_selesai', '>=', $dateStr)
                ->exists();
            
            if ($existingCuti) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki cuti yang disetujui pada tanggal ' . 
                        Carbon::parse($dateStr)->format('d/m/Y') . 
                        '. Tidak dapat mengajukan izin.'
                ], 400);
            }
        }

        DB::beginTransaction();
        try {
            foreach ($period as $date) {
                Absensi::updateOrCreate(
                    ['user_id' => Auth::id(), 'tanggal' => $date->toDateString()],
                    [
                        'jenis_ketidakhadiran' => $request->jenis,
                        'reason' => $request->keterangan,
                        'approval_status' => 'pending',
                        'tanggal_akhir' => $request->tanggal_akhir,
                        'keterangan' => 'Pengajuan ' . $request->jenis,
                        'status' => 'tidak_hadir'
                    ]
                );
            }
            DB::commit();

            // Ambil data terbaru untuk hari ini
            $today = now()->toDateString();
            $latestAbsen = Absensi::where('user_id', Auth::id())
                ->where('tanggal', $today)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan ' . $request->jenis . ' untuk ' . $period->count() . ' hari berhasil dikirim dan menunggu persetujuan admin.',
                'data' => $latestAbsen ? [
                    'id' => $latestAbsen->id,
                    'tanggal' => $latestAbsen->tanggal,
                    'jam_masuk' => $latestAbsen->jam_masuk,
                    'jam_pulang' => $latestAbsen->jam_pulang,
                    'jenis_ketidakhadiran' => $latestAbsen->jenis_ketidakhadiran,
                    'jenis_ketidakhadiran_label' => match($latestAbsen->jenis_ketidakhadiran) {
                        'sakit' => 'Sakit',
                        'izin' => 'Izin',
                        default => null,
                    },
                    'approval_status' => $latestAbsen->approval_status,
                    'reason' => $latestAbsen->reason,
                    'keterangan' => $latestAbsen->keterangan,
                ] : null
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Submit Izin Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan. Terjadi kesalahan pada server.'
            ], 500);
        }
    }

    /**
     * API: Proses pengajuan dinas luar (bisa multi-hari)
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

        $user = Auth::user();
        $period = CarbonPeriod::create($request->start_date, $request->end_date);

        // Validasi: Cek apakah ada tanggal dalam periode yang sudah termasuk cuti
        foreach ($period as $date) {
            $dateStr = $date->toDateString();
            
            // Cek apakah sudah ada cuti yang disetujui di tanggal ini
            $existingCuti = Cuti::where('user_id', $user->id)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $dateStr)
                ->whereDate('tanggal_selesai', '>=', $dateStr)
                ->exists();
            
            if ($existingCuti) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki cuti yang disetujui pada tanggal ' . 
                        Carbon::parse($dateStr)->format('d/m/Y') . 
                        '. Tidak dapat mengajukan dinas luar.'
                ], 400);
            }
        }

        DB::beginTransaction();
        try {
            foreach ($period as $date) {
                Absensi::updateOrCreate(
                    ['user_id' => Auth::id(), 'tanggal' => $date->toDateString()],
                    [
                        'jenis_ketidakhadiran' => 'dinas-luar',
                        'reason' => $request->description,
                        'location' => $request->location,
                        'purpose' => $request->purpose,
                        'approval_status' => 'pending',
                        'tanggal_akhir' => $request->end_date,
                        'keterangan' => 'Pengajuan dinas luar',
                        'status' => 'tidak_hadir'
                    ]
                );
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan dinas luar untuk ' . $period->count() . ' hari berhasil dikirim dan menunggu persetujuan admin.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Submit Dinas Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan dinas luar. Terjadi kesalahan pada server.'
            ], 500);
        }
    }

    // =================================================================
    // METHOD UNTUK API (DIPANGGIL OLEH JAVASCRIPT) - LAMA
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
                'late_minutes' => 0,
                'approval_status' => 'approved',
            ]);
        }

        // Tentukan status berdasarkan jam_masuk atau jenis_ketidakhadiran
        $status = 'Belum Absen';
        $lateMinutes = 0;
        
        if ($absen->jam_masuk) {
            // Hitung keterlambatan
            $jamMasuk = Carbon::parse($absen->jam_masuk);
            $jamBatas = Carbon::parse('08:00');
            $status = $jamMasuk->gt($jamBatas) ? 'Terlambat' : 'Tepat Waktu';
            $lateMinutes = $jamMasuk->gt($jamBatas) ? $jamMasuk->diffInMinutes($jamBatas) : 0;
        } elseif ($absen->jenis_ketidakhadiran) {
            $status = match($absen->jenis_ketidakhadiran) {
                'cuti' => 'Cuti',
                'sakit' => 'Sakit',
                'izin' => 'Izin',
                'dinas-luar' => 'Dinas Luar',
                default => 'Tidak Hadir',
            };
        }

        return response()->json([
            'jam_masuk' => $absen->jam_masuk,
            'jam_pulang' => $absen->jam_pulang,
            'status' => $status,
            'late_minutes' => $lateMinutes,
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
                              // Tentukan status berdasarkan kondisi
                              $status = 'Tidak Hadir';
                              $lateMinutes = 0;
                              
                              if ($item->jam_masuk) {
                                  $jamMasuk = Carbon::parse($item->jam_masuk);
                                  $jamBatas = Carbon::parse('08:00');
                                  $status = $jamMasuk->gt($jamBatas) ? 'Terlambat' : 'Tepat Waktu';
                                  $lateMinutes = $jamMasuk->gt($jamBatas) ? $jamMasuk->diffInMinutes($jamBatas) : 0;
                              } elseif ($item->jenis_ketidakhadiran) {
                                  $status = match($item->jenis_ketidakhadiran) {
                                      'cuti' => 'Cuti',
                                      'sakit' => 'Sakit',
                                      'izin' => 'Izin',
                                      'dinas-luar' => 'Dinas Luar',
                                      default => 'Tidak Hadir',
                                  };
                              }
                              
                              return [
                                  'id' => $item->id,
                                  'date' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                                  'checkIn' => $item->jam_masuk,
                                  'checkOut' => $item->jam_pulang,
                                  'status' => $status,
                                  'lateMinutes' => $lateMinutes,
                                  'isEarlyCheckout' => $item->is_early_checkout,
                                  'earlyCheckoutReason' => $item->early_checkout_reason,
                                  'approvalStatus' => $item->approval_status,
                                  'reason' => $item->reason,
                                  'location' => $item->location,
                                  'purpose' => $item->purpose,
                                  'jenis_ketidakhadiran' => $item->jenis_ketidakhadiran,
                                  'keterangan' => $item->keterangan,
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
            if ($absenToday->jam_masuk) {
                $jamMasuk = Carbon::parse($absenToday->jam_masuk);
                $jamBatas = Carbon::parse('08:00');
                $attendanceStatus = $jamMasuk->gt($jamBatas) ? 'Terlambat' : 'Tepat Waktu';
            } elseif ($absenToday->jenis_ketidakhadiran) {
                $attendanceStatus = match($absenToday->jenis_ketidakhadiran) {
                    'cuti' => 'Cuti',
                    'sakit' => 'Sakit',
                    'izin' => 'Izin',
                    'dinas-luar' => 'Dinas Luar',
                    default => 'Tidak Hadir',
                };
            }
        }

        $ketidakhadiranCount = Absensi::where('user_id', $userId)
                                ->where('approval_status', 'approved')
                                ->whereIn('jenis_ketidakhadiran', ['cuti', 'sakit', 'izin'])
                                ->count();

        // PERBAIKAN: Query tugas yang aman untuk kolom target_divisi
        $userDivisi = Auth::user()->divisi;
        $tugasCount = Task::where(function ($query) use ($userId, $userDivisi) {
            $query->where('assigned_to', $userId)
                ->orWhere(function ($q) use ($userDivisi) {
                    $q->where('target_type', 'divisi')
                        ->where(function($subQuery) use ($userDivisi) {
                            // Cek kolom yang tersedia
                            if (Schema::hasColumn('tasks', 'target_divisi')) {
                                $subQuery->where('target_divisi', $userDivisi);
                            } elseif (Schema::hasColumn('tasks', 'divisi')) {
                                $subQuery->where('divisi', $userDivisi);
                            } elseif (Schema::hasColumn('tasks', 'target_id')) {
                                $subQuery->where('target_id', $userDivisi);
                            }
                        });
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
     * API: Mengambil data dashboard untuk karyawan (endpoint baru untuk /api/karyawan/dashboard-data)
     */
    public function getDashboardDataApi()
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            $user = auth()->user();
            $today = now()->format('Y-m-d');
            
            // Get today's attendance
            $absensiHariIni = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();
            
            // Check if user is on leave
            $onLeave = $this->checkIfOnLeaveToday($user->id);
            
            // Get tasks statistics
            $totalTasks = Task::where('assigned_to', $user->id)->count();
            $pendingTasks = Task::where('assigned_to', $user->id)
                ->where('status', '!=', 'selesai')
                ->count();
            $completedTasks = Task::where('assigned_to', $user->id)
                ->where('status', 'selesai')
                ->count();
            
            // Get leave requests
            $pendingCuti = Cuti::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'absensi_today' => $absensiHariIni ? [
                        'status' => $absensiHariIni->status,
                        'jam_masuk' => $absensiHariIni->jam_masuk,
                        'jam_pulang' => $absensiHariIni->jam_pulang,
                        'keterangan' => $absensiHariIni->keterangan
                    ] : null,
                    'on_leave' => $onLeave['on_leave'],
                    'cuti_details' => $onLeave['on_leave'] ? [
                        'tanggal_mulai' => $onLeave['details']->tanggal_mulai,
                        'tanggal_selesai' => $onLeave['details']->tanggal_selesai,
                        'tipe_cuti' => $onLeave['details']->tipe_cuti,
                        'alasan' => $onLeave['details']->alasan
                    ] : null,
                    'tasks' => [
                        'total' => $totalTasks,
                        'pending' => $pendingTasks,
                        'completed' => $completedTasks,
                        'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0
                    ],
                    'cuti' => [
                        'pending' => $pendingCuti
                    ],
                    'user' => [
                        'name' => $user->name,
                        'divisi' => $user->divisi,
                        'jabatan' => $user->jabatan,
                        'email' => $user->email
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Dashboard API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Mengambil status pengajuan (pending, approved, rejected).
     */
    public function getPengajuanStatus()
    {
        $userId = Auth::id();

        $pendingSubmissions = Absensi::where('user_id', $userId)
                                    ->where('approval_status', 'pending')
                                    ->whereNotNull('jenis_ketidakhadiran')
                                    ->orderBy('tanggal', 'desc')
                                    ->get()
                                    ->map(function ($item) {
                                        return [
                                            'id' => $item->id,
                                            'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                                            'jenis_ketidakhadiran' => $item->jenis_ketidakhadiran,
                                            'jenis_label' => match($item->jenis_ketidakhadiran) {
                                                'cuti' => 'Cuti',
                                                'sakit' => 'Sakit',
                                                'izin' => 'Izin',
                                                'dinas-luar' => 'Dinas Luar',
                                                default => 'Ketidakhadiran',
                                            },
                                            'approval_status' => $item->approval_status,
                                            'reason' => $item->reason,
                                            'keterangan' => $item->keterangan,
                                        ];
                                    });

        $recentSubmissions = Absensi::where('user_id', $userId)
                                    ->whereNotNull('jenis_ketidakhadiran')
                                    ->whereIn('approval_status', ['approved', 'rejected'])
                                    ->where('tanggal', '>=', now()->subDays(7)->toDateString())
                                    ->orderBy('tanggal', 'desc')
                                    ->get()
                                    ->map(function ($item) {
                                        return [
                                            'id' => $item->id,
                                            'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                                            'jenis_ketidakhadiran' => $item->jenis_ketidakhadiran,
                                            'jenis_label' => match($item->jenis_ketidakhadiran) {
                                                'cuti' => 'Cuti',
                                                'sakit' => 'Sakit',
                                                'izin' => 'Izin',
                                                'dinas-luar' => 'Dinas Luar',
                                                default => 'Ketidakhadiran',
                                            },
                                            'approval_status' => $item->approval_status,
                                            'reason' => $item->reason,
                                            'keterangan' => $item->keterangan,
                                        ];
                                    });

        return response()->json([
            'pending' => $pendingSubmissions,
            'recent' => $recentSubmissions,
        ]);
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

            // PERBAIKAN: Cek kolom yang tersedia
            $tasks = Task::where(function ($query) use ($userId, $userDivisi) {
                // Tugas untuk divisi (berdasarkan kolom yang ada)
                if (Schema::hasColumn('tasks', 'target_divisi')) {
                    $query->where('target_type', 'divisi')
                        ->where('target_divisi', $userDivisi);
                } elseif (Schema::hasColumn('tasks', 'divisi')) {
                    $query->where('target_type', 'divisi')
                        ->where('divisi', $userDivisi);
                } elseif (Schema::hasColumn('tasks', 'target_id')) {
                    $query->where('target_type', 'divisi')
                        ->where('target_id', $userDivisi);
                }
            })
                ->orWhere(function ($query) use ($userId) {
                    $query->where('assigned_to', $userId)
                        ->where('target_type', 'karyawan');
                })
                ->orWhere(function ($query) use ($userId) {
                    if (Schema::hasColumn('tasks', 'target_manager_id')) {
                        $query->where('target_manager_id', $userId)
                            ->where('target_type', 'manager');
                    }
                })
                ->with([
                    'creator:id,name',
                    'assignedUser:id,name,divisi',
                    'targetManager:id,name,divisi'
                ])
                ->orderBy('deadline', 'asc')
                ->get();

            // Transform data untuk frontend
            $transformedTasks = $tasks->map(function ($task) use ($userId, $userDivisi) {
                $assigneeText = '-';
                $assigneeDivisi = '-';

                if ($task->target_type === 'karyawan' && $task->assignedUser) {
                    $assigneeText = $task->assignedUser->name;
                    $assigneeDivisi = $task->assignedUser->divisi ?? '-';
                } elseif ($task->target_type === 'divisi') {
                    // Tentukan nilai divisi berdasarkan kolom yang ada
                    $divisiValue = $task->target_divisi ?? $task->divisi ?? $task->target_id ?? '-';
                    $assigneeText = 'Divisi ' . $divisiValue;
                    $assigneeDivisi = $divisiValue;
                } elseif ($task->target_type === 'manager' && $task->targetManager) {
                    $assigneeText = 'Manajer: ' . $task->targetManager->name;
                    $assigneeDivisi = $task->targetManager->divisi ?? '-';
                }

                $isOverdue = $task->deadline &&
                    now()->gt($task->deadline) &&
                    !in_array($task->status, ['selesai', 'dibatalkan']);

                $isForMe = false;
                if ($task->target_type === 'divisi') {
                    // Bandingkan dengan nilai divisi yang sesuai
                    $divisiValue = strtolower($task->target_divisi ?? $task->divisi ?? $task->target_id ?? '');
                    $isForMe = $divisiValue === strtolower($userDivisi);
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
                    'target_divisi' => $task->target_divisi ?? $task->divisi ?? $task->target_id ?? null,
                    'assignee_text' => $assigneeText,
                    'assignee_divisi' => $assigneeDivisi,
                    'creator_name' => $task->creator->name ?? '-',
                    'is_overdue' => $isOverdue,
                    'is_for_me' => $isForMe,
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedTasks
            ]);

        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load tasks: ' . $e->getMessage()
            ], 500);
        }
    }

    // =================================================================
    // METHOD UNTUK MEETING NOTES DAN PENGUMUMAN (MENGGUNAKAN RELASI)
    // =================================================================

    /**
     * API untuk mendapatkan catatan rapat berdasarkan tanggal
     */
    public function getMeetingNotes(Request $request)
    {
        try {
            Log::info('=== GET MEETING NOTES (USING MODEL RELATIONSHIPS) ===');
            
            $date = $request->query('date');
            Log::info('Requested date:', ['date' => $date]);
            
            if (!$date) {
                Log::warning('No date parameter provided');
                return response()->json(['error' => 'Date parameter is required'], 400);
            }

            $userId = Auth::id();
            Log::info('User ID:', ['user_id' => $userId]);
            
            if (!$userId) {
                Log::error('User not authenticated');
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            
            // Check if tables exist
            if (!Schema::hasTable('catatan_rapats') || !Schema::hasTable('catatan_rapat_penugasan')) {
                Log::error('Required tables do not exist');
                return response()->json(['error' => 'Tables not found'], 500);
            }
            
            // Get notes assigned to this user using the penugasan relationship
            $notes = CatatanRapat::whereHas('penugasan', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->where('tanggal', $date)
                ->with(['user:id,name', 'penugasan' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }])
                ->get();
                
            Log::info('Found notes:', ['count' => $notes->count()]);
            
            $formattedNotes = $notes->map(function ($item) {
                return [
                    'id' => $item->id,
                    'tanggal' => $item->tanggal,
                    'formatted_tanggal' => $item->formatted_tanggal,
                    'topik' => $item->topik,
                    'hasil_diskusi' => $item->hasil_diskusi,
                    'keputusan' => $item->keputusan,
                    'creator' => $item->user ? $item->user->name : 'Unknown',
                ];
            });

            Log::info('Final result:', [
                'count' => $formattedNotes->count(),
                'notes' => $formattedNotes->toArray()
            ]);
            
            return response()->json($formattedNotes);

        } catch (\Exception $e) {
            Log::error('Error in getMeetingNotes: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Mengambil tanggal-tanggal yang memiliki meeting notes (endpoint baru untuk /api/karyawan/meeting-notes-dates)
     */
    public function getMeetingNotesDatesApi()
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            
            Log::info('=== GET MEETING NOTES DATES API ===');
            
            // Check if table exists
            if (!Schema::hasTable('catatan_rapats')) {
                Log::warning('Table catatan_rapats does not exist');
                return response()->json(['success' => true, 'dates' => []]);
            }
            
            // Get dates with published meeting notes
            // Using the actual column names from CatatanRapat model
            $dates = CatatanRapat::where('status', 'published')
                ->select('tanggal_rapat') // Adjust based on your actual column name
                ->distinct()
                ->orderBy('tanggal_rapat', 'desc')
                ->get()
                ->pluck('tanggal_rapat')
                ->map(function($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })
                ->toArray();
            
            Log::info('Found dates:', ['count' => count($dates), 'dates' => $dates]);
            
            return response()->json([
                'success' => true,
                'dates' => $dates
            ]);
            
        } catch (\Exception $e) {
            Log::error('Meeting Dates API Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load meeting dates'
            ], 500);
        }
    }

    /**
     * API: Mengambil meeting notes untuk tanggal tertentu (endpoint baru untuk /api/karyawan/meeting-notes)
     */
    public function getMeetingNotesApi(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            
            $date = $request->query('date');
            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date parameter is required'
                ], 400);
            }
            
            Log::info('=== GET MEETING NOTES API ===', ['date' => $date]);
            
            // Check if table exists
            if (!Schema::hasTable('catatan_rapats')) {
                Log::warning('Table catatan_rapats does not exist');
                return response()->json(['success' => true, 'data' => []]);
            }
            
            // Get meeting notes for specific date
            // Using the actual column names from CatatanRapat model
            $meetingNotes = CatatanRapat::where('status', 'published')
                ->whereDate('tanggal_rapat', $date) // Adjust column name if different
                ->orderBy('created_at', 'desc')
                ->get([
                    'id', 
                    'judul', 
                    'isi', 
                    'tanggal_rapat', // Adjust column name
                    'lokasi', 
                    'created_at'
                ]);
            
            Log::info('Found meeting notes:', ['count' => $meetingNotes->count()]);
            
            $formattedNotes = $meetingNotes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'judul' => $note->judul,
                    'isi' => $note->isi,
                    'tanggal_rapat' => $note->tanggal_rapat,
                    'formatted_tanggal' => Carbon::parse($note->tanggal_rapat)->translatedFormat('d F Y'),
                    'lokasi' => $note->lokasi,
                    'created_at' => $note->created_at->format('Y-m-d H:i:s'),
                    'formatted_created_at' => $note->created_at->translatedFormat('d F Y H:i'),
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedNotes,
                'date' => $date
            ]);
            
        } catch (\Exception $e) {
            Log::error('Meeting Notes API Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load meeting notes'
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan daftar tanggal yang memiliki catatan rapat
     */
    public function getMeetingNotesDates()
    {
        try {
            Log::info('=== GET MEETING NOTES DATES (USING MODEL RELATIONSHIPS) ===');

            $userId = Auth::id();
            Log::info('User ID:', ['user_id' => $userId]);
            
            if (!$userId) {
                Log::error('User not authenticated');
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Check if tables exist
            if (!Schema::hasTable('catatan_rapats') || !Schema::hasTable('catatan_rapat_penugasan')) {
                Log::error('Required tables do not exist');
                return response()->json([]);
            }
            
            // Get distinct dates for notes assigned to this user using the penugasan relationship
            $dates = CatatanRapat::whereHas('penugasan', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->select('tanggal')
                ->distinct()
                ->orderBy('tanggal', 'desc')
                ->pluck('tanggal');

            Log::info('Found dates:', ['count' => $dates->count(), 'dates' => $dates->toArray()]);
            return response()->json($dates);

        } catch (\Exception $e) {
            Log::error('Error in getMeetingNotesDates: ' . $e->getMessage());
            return response()->json([], 200); // Return empty array on error
        }
    }

    /**
     * API untuk mendapatkan pengumuman (MENGGUNAKAN MODEL PENGUMUMAN)
     */
    public function getAnnouncements()
    {
        try {
            Log::info('=== GET ANNOUNCEMENTS (USING MODEL RELATIONSHIPS) ===');

            $userId = Auth::id();
            Log::info('User ID:', ['user_id' => $userId]);
            
            if (!$userId) {
                Log::error('User not authenticated');
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Check if tables exist
            if (!Schema::hasTable('pengumuman') || !Schema::hasTable('pengumuman_user')) {
                Log::error('Required tables do not exist');
                return response()->json([]);
            }
            
            // Get announcements for this user using the scopeForUser method
            $announcements = Pengumuman::forUser($userId)
                ->with(['creator:id,name']) // Load the creator's name
                ->orderBy('created_at', 'desc')
                ->get();
                
            Log::info('Found announcements:', ['count' => $announcements->count()]);
            
            $formattedAnnouncements = $announcements->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'isi_pesan' => $item->isi_pesan,
                    'ringkasan' => $item->ringkasan, // Menggunakan accessor dari model
                    'lampiran' => $item->lampiran,
                    'lampiran_url' => $item->lampiran_url, // Menggunakan accessor dari model
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                    'tanggal_indo' => $item->tanggal_indo, // Menggunakan accessor dari model
                    'creator' => $item->creator ? $item->creator->name : 'System',
                ];
            });

            Log::info('Final result:', [
                'count' => $formattedAnnouncements->count(),
                'announcements' => $formattedAnnouncements->toArray()
            ]);
            
            return response()->json($formattedAnnouncements);

        } catch (\Exception $e) {
            Log::error('Error in getAnnouncements: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([], 200); // Return empty array on error
        }
    }

    /**
     * API: Mengambil tanggal-tanggal yang memiliki pengumuman (endpoint baru untuk /api/karyawan/announcements-dates)
     */
    public function getAnnouncementDatesApi()
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            
            Log::info('=== GET ANNOUNCEMENT DATES API ===');
            
            // Check if table exists
            if (!Schema::hasTable('pengumuman')) {
                Log::warning('Table pengumuman does not exist');
                return response()->json(['success' => true, 'dates' => []]);
            }
            
            // Get dates with announcements
            // Adjust column names based on your Pengumuman model
            $dates = Pengumuman::where('status', 'published')
                ->select('tanggal') // Adjust column name if different
                ->distinct()
                ->orderBy('tanggal', 'desc')
                ->get()
                ->pluck('tanggal')
                ->map(function($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })
                ->toArray();
            
            Log::info('Found announcement dates:', ['count' => count($dates), 'dates' => $dates]);
            
            return response()->json([
                'success' => true,
                'dates' => $dates
            ]);
            
        } catch (\Exception $e) {
            Log::error('Announcement Dates API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load announcement dates'
            ], 500);
        }
    }

    /**
     * API: Mengambil daftar pengumuman (endpoint baru untuk /api/karyawan/announcements)
     */
    public function getAnnouncementsApi()
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            
            Log::info('=== GET ANNOUNCEMENTS API ===');
            
            // Check if table exists
            if (!Schema::hasTable('pengumuman')) {
                Log::warning('Table pengumuman does not exist');
                return response()->json(['success' => true, 'data' => []]);
            }
            
            // Get latest announcements
            // Adjust column names based on your Pengumuman model
            $announcements = Pengumuman::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get([
                    'id', 
                    'judul', 
                    'isi', // Adjust column name if different (isi_pesan)
                    'tanggal', // Adjust column name if different
                    'lampiran',
                    'created_at'
                ]);
            
            Log::info('Found announcements:', ['count' => $announcements->count()]);
            
            $formattedAnnouncements = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'judul' => $announcement->judul,
                    'isi' => $announcement->isi, // Adjust based on your column name
                    'tanggal' => $announcement->tanggal,
                    'formatted_tanggal' => Carbon::parse($announcement->tanggal)->translatedFormat('d F Y'),
                    'lampiran' => $announcement->lampiran,
                    'lampiran_url' => $announcement->lampiran ? asset('storage/' . $announcement->lampiran) : null,
                    'created_at' => $announcement->created_at->format('Y-m-d H:i:s'),
                    'formatted_created_at' => $announcement->created_at->translatedFormat('d F Y H:i'),
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedAnnouncements
            ]);
            
        } catch (\Exception $e) {
            Log::error('Announcements API Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load announcements'
            ], 500);
        }
    }

    /**
     * Debug endpoint untuk meeting notes dengan penugasan
     */
    public function debugMeetingNotesPenugasan(Request $request)
    {
        $userId = Auth::id();
        $date = $request->query('date', now()->toDateString());
        
        // Get all penugasan for user
        $allPenugasans = DB::table('catatan_rapat_penugasan')
            ->where('user_id', $userId)
            ->get();
        
        // Get assigned note IDs
        $assignedNoteIds = $allPenugasans->pluck('catatan_rapat_id')->toArray();
        
        // Get notes for specific date
        $dateNotes = CatatanRapat::whereIn('id', $assignedNoteIds)
            ->where('tanggal', $date)
            ->with(['user:id,name'])
            ->get();
        
        // Get all assigned notes
        $allAssignedNotes = CatatanRapat::whereIn('id', $assignedNoteIds)
            ->with(['user:id,name'])
            ->get();
        
        // Get notes using relationship
        $relationshipNotes = CatatanRapat::whereHas('penugasan', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('tanggal', $date)
            ->with(['user:id,name', 'penugasan'])
            ->get();
        
        return response()->json([
            'user_id' => $userId,
            'requested_date' => $date,
            'all_penugasans_count' => $allPenugasans->count(),
            'assigned_note_ids' => $assignedNoteIds,
            'all_assigned_notes_count' => $allAssignedNotes->count(),
            'date_notes_count' => $dateNotes->count(),
            'relationship_notes_count' => $relationshipNotes->count(),
            'all_penugasans' => $allPenugasans->toArray(),
            'all_assigned_notes' => $allAssignedNotes->toArray(),
            'date_notes' => $dateNotes->toArray(),
            'relationship_notes' => $relationshipNotes->toArray(),
        ]);
    }

    /**
     * Debug endpoint untuk pengumuman
     */
    public function debugPengumuman(Request $request)
    {
        $userId = Auth::id();
        
        // Get all pengumuman_user for user
        $allPengumumanUsers = DB::table('pengumuman_user')
            ->where('user_id', $userId)
            ->get();
        
        // Get assigned pengumuman IDs
        $assignedPengumumanIds = $allPengumumanUsers->pluck('pengumuman_id')->toArray();
        
        // Get all assigned announcements
        $allAssignedAnnouncements = Pengumuman::whereIn('id', $assignedPengumumanIds)
            ->with(['creator:id,name'])
            ->get();
        
        // Get announcements using scopeForUser method
        $scopeAnnouncements = Pengumuman::forUser($userId)
            ->with(['creator:id,name'])
            ->get();
        
        return response()->json([
            'user_id' => $userId,
            'all_pengumuman_users_count' => $allPengumumanUsers->count(),
            'assigned_pengumuman_ids' => $assignedPengumumanIds,
            'all_assigned_announcements_count' => $allAssignedAnnouncements->count(),
            'scope_announcements_count' => $scopeAnnouncements->count(),
            'all_pengumuman_users' => $allPengumumanUsers->toArray(),
            'all_assigned_announcements' => $allAssignedAnnouncements->toArray(),
            'scope_announcements' => $scopeAnnouncements->toArray(),
        ]);
    }

    /**
     * Test endpoint untuk debugging API endpoints
     */
    public function testApiEndpoints()
    {
        $userId = Auth::id();
        
        // Test database connection
        try {
            DB::connection()->getPdo();
            $dbStatus = 'Connected';
        } catch (\Exception $e) {
            $dbStatus = 'Error: ' . $e->getMessage();
        }
        
        // Check tables
        $tables = [
            'catatan_rapats' => Schema::hasTable('catatan_rapats'),
            'catatan_rapat_penugasan' => Schema::hasTable('catatan_rapat_penugasan'),
            'pengumuman' => Schema::hasTable('pengumuman'), // Perbaiki nama tabel
            'pengumuman_user' => Schema::hasTable('pengumuman_user'),
        ];
        
        // Check data
        $data = [
            'user_id' => $userId,
            'db_status' => $dbStatus,
            'tables' => $tables,
            'meeting_notes_penugasan_count' => 0,
            'announcements_count' => 0,
        ];
        
        if ($tables['catatan_rapat_penugasan']) {
            $data['meeting_notes_penugasan_count'] = DB::table('catatan_rapat_penugasan')
                ->where('user_id', $userId)
                ->count();
        }
        
        if ($tables['pengumuman_user']) {
            $data['announcements_count'] = DB::table('pengumuman_user')
                ->where('user_id', $userId)
                ->count();
        }
        
        return response()->json($data);
    }

    /**
     * API: Debug endpoint untuk testing semua API karyawan
     */
    public function debugAllApis()
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            
            $user = auth()->user();
            $results = [];
            
            // Test dashboard data
            $dashboardResponse = $this->getDashboardDataApi();
            $dashboardData = json_decode($dashboardResponse->getContent(), true);
            $results['dashboard_data'] = $dashboardData['success'] ? 'SUCCESS' : 'FAILED';
            
            // Test meeting notes dates
            $meetingDatesResponse = $this->getMeetingNotesDatesApi();
            $meetingDatesData = json_decode($meetingDatesResponse->getContent(), true);
            $results['meeting_notes_dates'] = $meetingDatesData['success'] ? 'SUCCESS' : 'FAILED';
            
            // Test announcements dates
            $announcementDatesResponse = $this->getAnnouncementDatesApi();
            $announcementDatesData = json_decode($announcementDatesResponse->getContent(), true);
            $results['announcement_dates'] = $announcementDatesData['success'] ? 'SUCCESS' : 'FAILED';
            
            // Test announcements
            $announcementsResponse = $this->getAnnouncementsApi();
            $announcementsData = json_decode($announcementsResponse->getContent(), true);
            $results['announcements'] = $announcementsData['success'] ? 'SUCCESS' : 'FAILED';
            
            // Check database tables
            $tables = [
                'catatan_rapats' => Schema::hasTable('catatan_rapats'),
                'pengumuman' => Schema::hasTable('pengumuman'),
            ];
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'divisi' => $user->divisi,
                ],
                'api_results' => $results,
                'tables_exist' => $tables,
                'routes' => [
                    'dashboard_data' => '/karyawan/api/dashboard-data',
                    'meeting_notes_dates' => '/karyawan/api/meeting-notes-dates',
                    'announcement_dates' => '/karyawan/api/announcements-dates',
                    'announcements' => '/karyawan/api/announcements',
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Method untuk menambahkan kolom target_divisi jika belum ada (optional)
     */
    public function addTargetDivisiColumn()
    {
        try {
            if (!Schema::hasColumn('tasks', 'target_divisi')) {
                // Jalankan migration untuk menambahkan kolom
                DB::statement('ALTER TABLE tasks ADD COLUMN target_divisi VARCHAR(255) NULL AFTER target_type');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Kolom target_divisi berhasil ditambahkan ke tabel tasks'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom target_divisi sudah ada di tabel tasks'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kolom: ' . $e->getMessage()
            ], 500);
        }
    }
}
