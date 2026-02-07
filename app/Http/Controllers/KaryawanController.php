<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Task;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\CatatanRapat;
use App\Models\Pengumuman;
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

        return redirect()->route('pegawai.index')->with('success', 'karyawan berhasil diperbarui.');
    }

    /**
     * Delete karyawan.
     */
    public function destroyPegawai($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('pegawai.index')->with('success', 'karyawan berhasil dihapus.');
    }

    /**
     * Helper method untuk cek apakah user sedang cuti hari ini
     */
    private function checkIfOnLeaveToday($userId)
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $cuti = Cuti::where('user_id', $userId)
            ->where('status', 'disetujui') // Pastikan kolom status di tabel cuti menggunakan 'disetujui'
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

    /**
     * Menampilkan halaman beranda karyawan.
     */
    public function home()
    {
        $userId = Auth::id();
        $today = now()->toDateString();
        $user = Auth::user(); 
        $userRole = $user->role; 
        
        // Ambil divisi dengan Fallback
        $userDivisi = $user->divisi ?? null;
        if (!$userDivisi) {
            $karyawanData = Karyawan::where('user_id', $userId)->first();
            if ($karyawanData) {
                $userDivisi = $karyawanData->divisi;
            }
        }

        // 1. Ambil status absensi hari ini
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
                    'lainnya' => 'Tidak Hadir',
                    default => 'Tidak Hadir',
                };
            }
        }

        // Hitung Jumlah Ketidakhadiran (Sakit, Izin, Cuti yang disetujui)
        $ketidakhadiranCount = Absensi::where('user_id', $userId)
                                ->where('approval_status', 'approved')
                                ->whereIn('jenis_ketidakhadiran', ['cuti', 'sakit', 'izin'])
                                ->count();

        // Hitung Jumlah Tugas
        $userDivisi = $user->divisi; 
        $tugasCount = Task::where(function ($query) use ($userId, $userDivisi) {
            $query->where('assigned_to', $userId);
            
            if (Schema::hasColumn('tasks', 'target_type') && $userDivisi) {
                $query->orWhere(function ($q) use ($userDivisi) {
                    $q->where('target_type', 'divisi');
                    if (Schema::hasColumn('tasks', 'target_divisi')) $q->where('target_divisi', $userDivisi);
                    elseif (Schema::hasColumn('tasks', 'target_id')) $q->where('target_id', $userDivisi);
                });
            } else {
                if ($userDivisi && Schema::hasColumn('tasks', 'divisi')) {
                    $query->orWhere('divisi', $userDivisi);
                }
            }
        })
        ->whereNotIn('status', ['selesai', 'dibatalkan'])
        ->count();

        $roleBasedData = [];

        if ($userRole === 'general_manager') {
            $roleBasedData['totalKaryawan'] = Karyawan::count();
            $roleBasedData['totalDivisi'] = Karyawan::distinct('divisi')->count('divisi');

            $countPendingManual = Absensi::where('approval_status', 'pending')
                ->whereNotNull('jenis_ketidakhadiran')
                ->count();

            $countPendingCuti = 0;
            if (Schema::hasTable('cutis')) {
                 $queryCuti = \App\Models\Cuti::query();
                 if (Schema::hasColumn('cutis', 'status')) {
                     $queryCuti->where('status', 'pending');
                 } elseif (Schema::hasColumn('cutis', 'status_pengajuan')) {
                     $queryCuti->where('status_pengajuan', 'pending');
                 }
                 $countPendingCuti = $queryCuti->count();
            }

            $roleBasedData['pendingApprovals'] = $countPendingManual + $countPendingCuti;

            Log::info('GM Dashboard Check', [
                'user_id' => $userId,
                'user_role' => $userRole,
                'user_divisi' => $userDivisi,
                'count_pending_manual' => $countPendingManual,
                'count_cuti_table' => $countPendingCuti,
                'final_total' => $roleBasedData['pendingApprovals']
            ]);

        } elseif ($userRole === 'manager') {
            if ($userDivisi) {
                $roleBasedData['teamMembers'] = Karyawan::where('divisi', $userDivisi)->count();
                
                $roleBasedData['teamPendingApprovals'] = Absensi::whereIn('user_id', function($query) use ($userDivisi) {
                    $query->select('user_id')
                          ->from('karyawan')
                          ->where('divisi', $userDivisi);
                })
                ->where('approval_status', 'pending')
                ->count();
            } else {
                $roleBasedData['teamMembers'] = 0;
                $roleBasedData['teamPendingApprovals'] = 0;
            }
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

        // 6. Total Alpha = tidak ada data absensi sama sekali untuk hari kerja
        // (Perlu logika khusus)

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
     * Menampilkan halaman daftar TUGAS karyawan (Web View).
     */
    public function listPage()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            $userDivisi = $user->divisi ?? null;
            
            if (!$userDivisi) {
                $karyawan = Karyawan::where('user_id', $userId)->first();
                $userDivisi = $karyawan ? $karyawan->divisi : null;
            }
            
            $userName = $user->name;

            Log::info('=== KARYAWAN TUGAS LIST ===', [
                'controller' => 'KaryawanController@listPage',
                'user_id' => $userId,
                'user_name' => $userName,
                'user_divisi' => $userDivisi,
                'role' => $user->role,
                'timestamp' => now()->toDateTimeString(),
            ]);

            // PERBAIKAN UTAMA: Query yang lebih komprehensif
            $tasks = Task::where(function ($query) use ($userId, $userDivisi) {
                // 1. Tugas untuk user
                $query->where('assigned_to', $userId);
                
                // 2. Tugas untuk divisi
                if (Schema::hasColumn('tasks', 'target_type') && $userDivisi) {
                    $query->orWhere(function ($q) use ($userDivisi) {
                        $q->where('target_type', 'divisi');
                        if (Schema::hasColumn('tasks', 'target_divisi')) $q->where('target_divisi', $userDivisi);
                        elseif (Schema::hasColumn('tasks', 'target_id')) $q->where('target_id', $userDivisi);
                    });
                } elseif ($userDivisi && Schema::hasColumn('tasks', 'divisi')) {
                     $query->orWhere('divisi', $userDivisi);
                }
            })
            // PERBAIKAN KRUSIAL: Hanya select id dan name dari relasi User
            // Jangan select 'divisi' karena tabel users tidak punya kolom itu
            ->with([
                'creator:id,name',
                'assignee:id,name', 
                'targetManager:id,name'
            ])
            ->orderBy('deadline', 'asc')
            ->get();

            // Jika view butuh nama divisi dari assignee, kita ambil manual
            // Option A: Loop tambah data (Cara ini aman untuk view blade)
            $tasks->transform(function ($task) {
                if ($task->assignee) {
                    // Cari data karyawan berdasarkan user_id assignee
                    $karyawanInfo = Karyawan::where('user_id', $task->assignee->id)->first(['divisi']);
                    $task->assignee_divisi = $karyawanInfo ? $karyawanInfo->divisi : null;
                } else {
                    $task->assignee_divisi = null;
                }
                return $task;
            });

            return view('karyawan.list', compact('tasks'));

        } catch (\Exception $e) {
            Log::error('CRITICAL ERROR in KaryawanController@listPage: ' . $e->getMessage());
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

    // =================================================================
    // API UNTUK HALAMAN ABSENSI (FRONTEND JAVASCRIPT)
    // =================================================================

    /**
     * Mengambil status absensi hari ini.
     * PERBAIKAN: Format response sesuai harapan Frontend { success: true, data: {...} }
     */
    public function getTodayStatusApi()
    {
        try {
            $userId = Auth::id();
            $today = now()->toDateString();
            
            $absen = Absensi::where('user_id', $userId)
                ->where('tanggal', $today)
                ->first();

        $data = [
            'jam_masuk' => null,
            'jam_pulang' => null,
            'status' => 'Belum Absen',
            'late_minutes' => 0,
            'approval_status' => 'approved',
            'is_on_leave' => false,
            'jenis_ketidakhadiran' => null,
            'keterangan' => null
        ];

        if ($absen) {
            $data['jam_masuk'] = $absen->jam_masuk;
            $data['jam_pulang'] = $absen->jam_pulang;
            $data['approval_status'] = $absen->approval_status;
            $data['jenis_ketidakhadiran'] = $absen->jenis_ketidakhadiran;
            $data['keterangan'] = $absen->keterangan;

            if ($absen->jam_masuk) {
                // Hitung keterlambatan
                $jamMasuk = Carbon::parse($absen->jam_masuk);
                $jamBatas = Carbon::parse('09:05'); // Sesuai logika check-in API
                $data['status'] = $jamMasuk->gt($jamBatas) ? 'Terlambat' : 'Tepat Waktu';
                $data['late_minutes'] = $jamMasuk->gt($jamBatas) ? $jamMasuk->diffInMinutes($jamBatas) : 0;
            } elseif ($absen->jenis_ketidakhadiran) {
                $data['status'] = match($absen->jenis_ketidakhadiran) {
                    'cuti' => 'Cuti',
                    'sakit' => 'Sakit',
                    'izin' => 'Izin',
                    'dinas-luar' => 'Dinas Luar',
                    default => 'Tidak Hadir',
                };
            }
        }

        // Cek status cuti
        $leaveStatus = $this->checkIfOnLeaveToday(Auth::id());
        if ($leaveStatus['on_leave']) {
            $data['is_on_leave'] = true;
            $data['leave_type'] = $leaveStatus['details']->tipe_cuti;
            $data['leave_reason'] = $leaveStatus['details']->alasan;
            $data['leave_dates'] = [
                'start' => $leaveStatus['details']->tanggal_mulai,
                'end' => $leaveStatus['details']->tanggal_selesai
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
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
     * Mengambil riwayat absensi.
     * PERBAIKAN PENTING:
     * 1. Format JSON { success: true, data: [...] } agar frontend membaca.
     * 2. Nama Key disesuaikan (jam_masuk, jam_pulang) sesuai frontend.
     */
    public function getHistory(Request $request)
    {
        $query = Absensi::where('user_id', Auth::id());
        
        // Filter Logic sesuai frontend JS
        $filterType = $request->get('filter', 'month');
        $month = $request->get('month');
        $year = $request->get('year');

        if ($filterType === 'custom' && $month && $year) {
            $query->whereMonth('tanggal', '=', $month)
                  ->whereYear('tanggal', '=', $year);
        } elseif ($filterType === 'week') {
            $query->whereBetween('tanggal', [
                Carbon::now()->startOfWeek()->toDateString(),
                Carbon::now()->endOfWeek()->toDateString()
            ]);
        } elseif ($filterType === 'year') {
            $query->whereYear('tanggal', '=', date('Y'));
        } else {
            // Default: Bulan Ini
            $query->whereMonth('tanggal', '=', date('m'))
                  ->whereYear('tanggal', '=', date('Y'));
        }

        $history = $query->orderBy('tanggal', 'desc')->get();

        $formattedData = $history->map(function ($item) {
            // Tentukan status
            $status = 'Tidak Hadir';
            $lateMinutes = 0;

            if ($item->jam_masuk) {
                $jamMasuk = Carbon::parse($item->jam_masuk);
                $jamBatas = Carbon::parse('09:05');
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
                'tanggal' => $item->tanggal, // Tanggal string mentah
                'date' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'), // Format label
                'jam_masuk' => $item->jam_masuk, // Key disamakan dengan frontend
                'jam_pulang' => $item->jam_pulang, // Key disamakan dengan frontend
                'status' => $status,
                'lateMinutes' => $lateMinutes,
                'is_early_checkout' => $item->is_early_checkout,
                'early_checkout_reason' => $item->early_checkout_reason,
                'approval_status' => $item->approval_status,
                'reason' => $item->reason,
                'location' => $item->location,
                'purpose' => $item->purpose,
                'jenis_ketidakhadiran' => $item->jenis_ketidakhadiran,
                'keterangan' => $item->keterangan,
                'is_on_leave' => false // Akan dihandle di level view/logika jika perlu
            ];
        })->all();

        return response()->json([
            'success' => true,
            'data' => $formattedData
        ]);
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
                $lateMinutes = $jamMasuk->gt($jamBatas) ? $jamMasuk->diffInMinutes($jamBatas) : 0;
                $isLate = $lateMinutes > 0;
                $attendanceStatus = $isLate ? 'Terlambat' : 'Tepat Waktu';
            } elseif ($absenToday->jenis_ketidakhadiran) { // FIXED: undefined variable $absen
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

        $ketidakhadiranCount = Absensi::where('user_id', $userId)
                                ->where('approval_status', 'approved')
                                ->whereIn('jenis_ketidakhadiran', ['cuti', 'sakit', 'izin'])
                                ->count();

        $userDivisi = Auth::user()->divisi;
        $tugasCount = Task::where(function ($query) use ($userId, $userDivisi) {
            $query->where('assigned_to', $userId);
            if ($userDivisi) {
                $query->orWhere(function ($q) use ($userDivisi) {
                    $q->where('target_type', 'divisi')
                        ->where('target_divisi', $userDivisi);
                });
            }
        })
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'attendance_status' => $attendanceStatus,
                'ketidakhadiran_count' => $ketidakhadiranCount,
                'tugas_count' => $tugasCount,
            ]
        ]);
    }

    /**
     * API: Mengambil data dashboard untuk karyawan (endpoint baru untuk /api/karyawan/dashboard-data)
     * (Menggantikan duplikat method sebelumnya)
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
                        // Hati-hati memanggil divisi jika kolom tidak ada di users
                        'divisi' => $user->divisi ?? '-', 
                        'jabatan' => $user->jabatan ?? '-',
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
     * API: Mengambil riwayat absensi (untuk frontend)
     */
    public function getHistoryApi(Request $request)
    {
        try {
            $userId = Auth::id();
            
            $filter = $request->query('filter', 'month');
            $month = $request->query('month', date('m'));
            $year = $request->query('year', date('Y'));
            
            $query = Absensi::where('user_id', $userId);
            
            if ($filter === 'custom' && $month && $year) {
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } elseif ($filter === 'week') {
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } elseif ($filter === 'month') {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } elseif ($filter === 'year') {
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            }
            
            $history = $query->orderBy('tanggal', 'desc')
                            ->get()
                            ->map(function ($item) {
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
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    /**
     * API: Mengambil status pengajuan (Pending & Recent)
     */
    public function getPengajuanStatus()
    {
        try {
            $userId = Auth::id();
            $pending = Absensi::where('user_id', $userId)
                ->where('approval_status', 'pending')
                ->whereNotNull('jenis_ketidakhadiran')
                ->orderBy('tanggal', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                        'jenis' => $item->jenis_ketidakhadiran,
                        'status' => $item->approval_status,
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
                'success' => true,
                'data' => [
                    'pending' => $pending,
                    'recent' => $recentSubmissions,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Pengajuan Status Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error'], 500);
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
                    'message' => 'Anda tidak dapat melakukan absen masuk karena telah mengajukan ketidakhadiran pada hari ini.'
                ], 403);
            }

            $cek = Absensi::where('user_id', $user->id)
                ->where('tanggal', $today)
                ->first();

            if ($cek && $cek->jam_masuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamu sudah absen masuk hari ini'
                ], 409);
            }

            $nowLocal = now();
            $workStartTime = $nowLocal->copy()->setTime(9, 5, 0);
            $lateMinutes = $nowLocal->greaterThan($workStartTime) ? $workStartTime->diffInMinutes($nowLocal) : 0;

            $status = $lateMinutes > 0 ? 'Terlambat' : 'Tepat Waktu';

            $absensi = Absensi::updateOrCreate(
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
                    'id' => $absensi->id,
                    'time' => $nowLocal->toDateTimeString(),
                    'jam_masuk' => $nowLocal->toTimeString(), // Tambahkan jam_masuk
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Absen Masuk Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
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

            $absen = Absensi::where('user_id', $user->id)
                ->where('tanggal', $today)
                ->first();

            if (!$absen || !$absen->jam_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda belum absen masuk.'], 400);
            }

            if ($absen->jam_pulang) {
                return response()->json(['success' => false, 'message' => 'Anda sudah absen pulang.'], 409);
            }

            $nowLocal = now();
            $workEndTime = $nowLocal->copy()->setTime(17, 0, 0);
            $isEarlyCheckout = $nowLocal->lessThan($workEndTime);

            $reason = null;
            if ($isEarlyCheckout) {
                $request->validate(['reason' => 'required|string|max:255']);
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
                    'time' => $nowLocal->toDateTimeString(),
                    'jam_pulang' => $nowLocal->toTimeString(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Absen Pulang Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
        }
    }

    /**
     * API: Proses pengajuan izin
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
            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal mengajukan.'], 500);
        }
    }

    /**
     * API: Proses pengajuan dinas luar
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
            return response()->json(['success' => true, 'message' => 'Pengajuan dinas luar berhasil.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal mengajukan dinas luar.'], 500);
        }
    }

    /**
     * API: Mendapatkan Tugas (JSON)
     */
    public function apiGetTasks()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            $userDivisi = $user->divisi ?? null;
            if (!$userDivisi) {
                $k = Karyawan::where('user_id', $userId)->first();
                $userDivisi = $k ? $k->divisi : null;
            }

            $tasks = Task::where(function ($query) use ($userId, $userDivisi) {
                $query->where('assigned_to', $userId);
                
                if (Schema::hasColumn('tasks', 'target_type') && $userDivisi) {
                    $query->orWhere(function ($q) use ($userDivisi) {
                        $q->where('target_type', 'divisi');
                        if (Schema::hasColumn('tasks', 'target_divisi')) $q->where('target_divisi', $userDivisi);
                        elseif (Schema::hasColumn('tasks', 'target_id')) $q->where('target_id', $userDivisi);
                    });
                } else {
                    if ($userDivisi && Schema::hasColumn('tasks', 'divisi')) {
                        $query->orWhere('divisi', $userDivisi);
                    }
                }
            })
            // PERBAIKAN: Hanya select id & name, hindari kolom divisi di tabel users
            ->with(['creator:id,name', 'assignee:id,name'])
            ->orderBy('deadline', 'asc')
            ->get();

            $transformedTasks = $tasks->map(function ($task) {
                // Ambil divisi secara manual jika diperlukan
                $assigneeDivisi = null;
                if ($task->assignee) {
                    $k = Karyawan::where('user_id', $task->assignee->id)->first(['divisi']);
                    $assigneeDivisi = $k ? $k->divisi : null;
                }

                return [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status' => $task->status,
                    'target_type' => $task->target_type ?? 'unknown',
                    'assignee_text' => $task->assignee ? $task->assignee->name : ($task->target_type === 'divisi' ? 'Divisi' : 'Unknown'),
                    'assignee_divisi' => $assigneeDivisi, // Tambahan data divisi
                    'creator_name' => $task->creator ? $task->creator->name : 'System',
                ];
            });

            return response()->json(['success' => true, 'data' => $transformedTasks]);
        } catch (\Exception $e) {
            Log::error('API Get Tasks Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat tugas'], 500);
        }
    }

    // =================================================================
    // METHOD UNTUK MEETING NOTES DAN PENGUMUMAN
    // =================================================================

    /**
     * API: Mengambil tanggal-tanggal yang memiliki meeting notes
     */
    public function getMeetingNotesDatesApi()
    {
        try {
            if (!auth()->check()) return response()->json(['success' => false], 401);
            
            if (!Schema::hasTable('catatan_rapats')) {
                return response()->json(['success' => true, 'dates' => []]);
            }
            
            $dates = CatatanRapat::where('status', 'published')
                ->select('tanggal_rapat') // Pastikan kolom ini ada
                ->distinct()
                ->orderBy('tanggal_rapat', 'desc')
                ->pluck('tanggal_rapat')
                ->map(function($date) { return Carbon::parse($date)->format('Y-m-d'); })
                ->toArray();
            
            return response()->json(['success' => true, 'dates' => $dates]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Mengambil meeting notes untuk tanggal tertentu
     */
    public function getMeetingNotesApi(Request $request)
    {
        try {
            if (!auth()->check()) return response()->json(['success' => false], 401);
            $date = $request->query('date');
            if (!$date) return response()->json(['success' => false, 'message' => 'Date required'], 400);
            
            if (!Schema::hasTable('catatan_rapats')) {
                return response()->json(['success' => true, 'data' => []]);
            }
            
            $meetingNotes = CatatanRapat::where('status', 'published')
                ->whereDate('tanggal_rapat', $date)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'judul', 'isi', 'tanggal_rapat', 'lokasi', 'created_at']);
            
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
            
            return response()->json(['success' => true, 'data' => $formattedNotes, 'date' => $date]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * API untuk mendapatkan daftar tanggal yang memiliki catatan rapat
     */
    public function getMeetingNotesDates()
    {
        try {
            $userId = Auth::id();
            
            if (!Schema::hasTable('catatan_rapats')) {
                return response()->json([]);
            }
            
            $dates = CatatanRapat::where('status', 'published')
                ->select('tanggal_rapat')
                ->distinct()
                ->orderBy('tanggal_rapat', 'desc')
                ->pluck('tanggal_rapat')
                ->map(function($date) { return Carbon::parse($date)->format('Y-m-d'); })
                ->toArray();
                
            return response()->json($dates);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * API: Mengambil tanggal-tanggal yang memiliki pengumuman
     */
    public function getAnnouncementDatesApi()
    {
        try {
            if (!auth()->check()) return response()->json(['success' => false], 401);
            if (!Schema::hasTable('pengumuman')) return response()->json(['success' => true, 'dates' => []]);
            
            $dates = Pengumuman::where('status', 'published')
                ->select('tanggal')
                ->distinct()
                ->orderBy('tanggal', 'desc')
                ->pluck('tanggal')
                ->map(function($date) { return Carbon::parse($date)->format('Y-m-d'); })
                ->toArray();
            
            return response()->json(['success' => true, 'dates' => $dates]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * API: Mengambil daftar pengumuman
     */
    public function getAnnouncementsApi()
    {
        try {
            if (!auth()->check()) return response()->json(['success' => false], 401);
            if (!Schema::hasTable('pengumuman')) return response()->json(['success' => true, 'data' => []]);
            
            $announcements = Pengumuman::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get(['id', 'judul', 'isi', 'tanggal', 'lampiran', 'created_at']);
            
            $formattedAnnouncements = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'judul' => $announcement->judul,
                    'isi' => $announcement->isi,
                    'tanggal' => $announcement->tanggal,
                    'formatted_tanggal' => Carbon::parse($announcement->tanggal)->translatedFormat('d F Y'),
                    'lampiran' => $announcement->lampiran,
                    'lampiran_url' => $announcement->lampiran ? asset('storage/' . $announcement->lampiran) : null,
                    'created_at' => $announcement->created_at->format('Y-m-d H:i:s'),
                    'formatted_created_at' => $announcement->created_at->translatedFormat('d F Y H:i'),
                ];
            });
            
            return response()->json(['success' => true, 'data' => $formattedAnnouncements]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * API untuk mendapatkan pengumuman (Web View)
     */
    public function getAnnouncements()
    {
        try {
            $userId = Auth::id();
            
            if (!Schema::hasTable('pengumuman')) {
                return response()->json([]);
            }
            
            $announcements = Pengumuman::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->get();
                
            $formattedAnnouncements = $announcements->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'isi_pesan' => $item->isi,
                    'ringkasan' => $item->ringkasan ?? null,
                    'lampiran' => $item->lampiran,
                    'lampiran_url' => $item->lampiran ? asset('storage/' . $item->lampiran) : null,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                    'tanggal_indo' => $item->created_at->translatedFormat('d F Y'),
                ];
            });

            return response()->json($formattedAnnouncements);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function debugGmDashboard()
    {
        $pendingAbsensi = Absensi::where('approval_status', 'pending')
            ->whereNotNull('jenis_ketidakhadiran')
            ->get(['id', 'user_id', 'jenis_ketidakhadiran', 'approval_status', 'tanggal']);
        
        $pendingCuti = Cuti::where('status', 'pending')->get(); 

        return response()->json([
            'status' => 'ok',
            'total_pending_absensi' => $pendingAbsensi->count(),
            'data_pending_absensi' => $pendingAbsensi,
            'total_pending_cuti' => $pendingCuti->count(),
            'data_pending_cuti' => $pendingCuti,
            'message' => 'Lihat output ini. Jika data sakit ada di sini, berarti query controller salah. Jika KOSONG, berarti status data di DB bukan PENDING.'
        ]);
    }

    public function testApiEndpoints()
    {
        return response()->json(['status' => 'ok', 'message' => 'API endpoints are working.']);
    }
}