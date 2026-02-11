<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Task;
use App\Models\TaskAcceptance;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\Setting;
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
        $query = Karyawan::with('user.divisi');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($divisi = $request->query('divisi')) {
            // Filter berdasarkan divisi_id dari user
            $query->whereHas('user', function ($q) use ($divisi) {
                $q->whereHas('divisi', function ($sq) use ($divisi) {
                    $sq->where('divisi', $divisi);
                });
            });
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
        $user = Auth::user()->load('divisi');  // Eager load the divisi relationship
        $userRole = $user->role; 
        
        // Determine divisi id and name (prefer divisi_id on users table)
        $userDivisiId = null;
        $userDivisi = null; // name
        if ($user->divisi_id) {
            $userDivisiId = $user->divisi_id;
            $userDivisi = $user->divisi ? $user->divisi->divisi : null;
        }
        
        // Fallback to karyawan.divisi if still empty
        if (!$userDivisi) {
            $karyawanData = Karyawan::where('user_id', $userId)->first();
            if ($karyawanData) {
                $userDivisi = $karyawanData->divisi;
                if ($userDivisi) {
                    $div = Divisi::where('divisi', $userDivisi)->first();
                    if ($div) $userDivisiId = $div->id;
                }
            }
        }

        // 1. Ambil status absensi hari ini
        $absenToday = Absensi::where('user_id', $userId)
            ->where('tanggal', $today)
            ->first();

        $attendanceStatus = 'Belum Absen';
        if ($absenToday) {
            if ($absenToday->jam_masuk) {
                // Gunakan accessor model untuk menentukan keterlambatan (sumber kebenaran tunggal)
                $attendanceStatus = $absenToday->is_terlambat ? 'Terlambat' : 'Tepat Waktu';
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

        // Hitung Jumlah Tugas (use divisi_id when available)
        $tugasCount = Task::where(function ($query) use ($userId, $userDivisiId) {
            $query->where('assigned_to', $userId)
                  ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
            if (Schema::hasColumn('tasks', 'target_type') && $userDivisiId) {
                $query->orWhere(function ($q) use ($userDivisiId) {
                    $q->where('target_type', 'divisi');
                    if (Schema::hasColumn('tasks', 'target_divisi_id')) $q->where('target_divisi_id', $userDivisiId);
                    elseif (Schema::hasColumn('tasks', 'target_id')) $q->where('target_id', $userDivisiId);
                });
            } else {
                if ($userDivisiId && Schema::hasColumn('tasks', 'divisi')) {
                    $query->orWhere('divisi', $userDivisiId);
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
                'user_divisi_id' => $userDivisiId,
                'count_pending_manual' => $countPendingManual,
                'count_cuti_table' => $countPendingCuti,
                'final_total' => $roleBasedData['pendingApprovals']
            ]);

        } elseif ($userRole === 'manager') {
            if ($userDivisiId) {
                // Count team members by users.divisi_id
                $roleBasedData['teamMembers'] = Karyawan::whereHas('user', function($q) use ($userDivisiId) {
                    $q->where('divisi_id', $userDivisiId);
                })->count();

                // Pending approvals for users in the same divisi
                $roleBasedData['teamPendingApprovals'] = Absensi::whereIn('user_id', function($query) use ($userDivisiId) {
                    $query->select('id')
                          ->from('users')
                          ->where('divisi_id', $userDivisiId);
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
            'user_divisi_id' => $userDivisiId,
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
            // Determine divisi id and name
            $userDivisiId = null;
            $userDivisi = null;
            if ($user->divisi_id) {
                $userDivisiId = $user->divisi_id;
                $userDivisi = $user->divisi ? $user->divisi->divisi : null;
            } else {
                $karyawan = Karyawan::where('user_id', $userId)->first();
                $userDivisi = $karyawan ? $karyawan->divisi : null;
                if ($userDivisi) {
                    $divModel = Divisi::where('divisi', $userDivisi)->first();
                    if ($divModel) $userDivisiId = $divModel->id;
                }
            }
            
            $userName = $user->name;

            Log::info('=== KARYAWAN TUGAS LIST ===', [
                'controller' => 'KaryawanController@listPage',
                'user_id' => $userId,
                'user_name' => $userName,
                'user_divisi' => $userDivisi,
                'user_divisi_id' => $userDivisiId,
                'role' => $user->role,
                'timestamp' => now()->toDateTimeString(),
            ]);

            // PERBAIKAN UTAMA: Query yang lebih komprehensif
            $tasks = Task::where(function ($query) use ($userId, $userDivisiId) {
                // 1. Tugas untuk user - check both assigned_to dan assigned_to_ids (multi-assign)
                $query->where('assigned_to', $userId)
                      ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);

                // 2. Tugas untuk divisi (by id)
                if (Schema::hasColumn('tasks', 'target_type') && $userDivisiId) {
                    $query->orWhere(function ($q) use ($userDivisiId) {
                        $q->where('target_type', 'divisi');
                        if (Schema::hasColumn('tasks', 'target_divisi_id')) $q->where('target_divisi_id', $userDivisiId);
                        elseif (Schema::hasColumn('tasks', 'target_id')) $q->where('target_id', $userDivisiId);
                    });
                } elseif ($userDivisiId && Schema::hasColumn('tasks', 'divisi')) {
                     $query->orWhere('divisi', $userDivisiId);
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
                ->whereDate('tanggal', $today)
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
                // Use late_minutes from database if available
                $lateMin = $absen->late_minutes;
                $data['status'] = (is_numeric($lateMin) && $lateMin > 0) ? 'Terlambat' : 'Tepat Waktu';
                $data['late_minutes'] = is_numeric($lateMin) ? (int)$lateMin : 0;
                Log::debug('getTodayStatusApi status check', ['late_minutes' => $data['late_minutes'], 'status' => $data['status']]);
            } elseif ($absen->jenis_ketidakhadiran) {
                $data['status'] = match($absen->jenis_ketidakhadiran) {
                    'cuti' => 'Cuti',
                    'sakit' => 'Sakit',
                    'izin' => 'Izin',
                    'dinas-luar' => 'Dinas Luar',
                    'lainnya' => 'Lainnya',
                    default => 'Lainnya',
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
                // Use late_minutes from database if available
                $lateMinutes = $item->late_minutes !== null ? $item->late_minutes : 0;
                $status = $lateMinutes > 0 ? 'Terlambat' : 'Tepat Waktu';
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
        try {
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

            // Get today's attendance status
            $absenHariIni = Absensi::where('user_id', $userId)
                ->where('tanggal', $today)
                ->first();

            // Count statistics
            $totalHadir = Absensi::where('user_id', $userId)
                ->whereNotNull('jam_masuk')
                ->where('approval_status', 'approved')
                ->count();

            $totalTerlambat = Absensi::where('user_id', $userId)
                ->whereNotNull('jam_masuk')
                ->where('approval_status', 'approved')
                ->get()
                ->filter(function ($record) {
                    if (!$record->jam_masuk) return false;
                    $jamMasuk = Carbon::parse($record->jam_masuk);
                    $jamBatas = Carbon::parse('08:00');
                    return $jamMasuk->gt($jamBatas);
                })
                ->count();

            $totalIzin = Absensi::where('user_id', $userId)
                ->where('jenis_ketidakhadiran', 'izin')
                ->where('approval_status', 'approved')
                ->count();

            $totalSakit = Absensi::where('user_id', $userId)
                ->where('jenis_ketidakhadiran', 'sakit')
                ->where('approval_status', 'approved')
                ->count();

            $totalAbsen = Absensi::where('user_id', $userId)
                ->where('jenis_ketidakhadiran', 'lainnya')
                ->where('approval_status', 'approved')
                ->count();

            $totalCuti = Cuti::where('user_id', $userId)
                ->where('status', 'approved')
                ->get()
                ->sum(function ($cuti) {
                    return Carbon::parse($cuti->tanggal_mulai)
                        ->diffInDays(Carbon::parse($cuti->tanggal_selesai)) + 1;
                });

            $tugasCount = Task::where(function($q) use ($userId) {
                    $q->where('assigned_to', $userId)
                      ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
                })
                ->whereNotIn('status', ['selesai', 'dibatalkan'])
                ->count();

            return response()->json([
                'attendance_status' => $attendanceStatus,
                'attendance_today' => $absenHariIni ? [
                    'jam_masuk' => $absenHariIni->jam_masuk,
                    'jam_pulang' => $absenHariIni->jam_pulang,
                ] : null,
                'total_hadir' => $totalHadir,
                'total_terlambat' => $totalTerlambat,
                'total_izin' => $totalIzin,
                'total_sakit' => $totalSakit,
                'total_absen' => $totalAbsen,
                'total_cuti' => $totalCuti,
                'tugas_count' => $tugasCount,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getDashboardData: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Error loading dashboard data',
                'message' => $e->getMessage(),
                'attendance_status' => 'Error',
                'total_hadir' => 0,
                'total_terlambat' => 0,
                'total_izin' => 0,
                'total_sakit' => 0,
                'total_absen' => 0,
                'total_cuti' => 0,
                'tugas_count' => 0,
            ], 500);
        }
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
            $userId = $user->id;
            $today = now()->format('Y-m-d');

            // Get today's attendance
            $absensiHariIni = Absensi::where('user_id', $userId)
                ->whereDate('tanggal', $today)
                ->first();

            // Check if user is on leave
            $onLeave = $this->checkIfOnLeaveToday($userId);

            // Count statistics for all time or month
            $totalHadir = Absensi::where('user_id', $userId)
                ->whereNotNull('jam_masuk')
                ->where('approval_status', 'approved')
                ->count();

            $totalTerlambat = Absensi::where('user_id', $userId)
                ->whereNotNull('jam_masuk')
                ->where('approval_status', 'approved')
                ->get()
                ->filter(function ($record) {
                    return $record->late_minutes && $record->late_minutes > 0;
                })
                ->count();

            $totalIzin = Absensi::where('user_id', $userId)
                ->where('jenis_ketidakhadiran', 'izin')
                ->where('approval_status', 'approved')
                ->count();

            $totalSakit = Absensi::where('user_id', $userId)
                ->where('jenis_ketidakhadiran', 'sakit')
                ->where('approval_status', 'approved')
                ->count();

            $totalAbsen = Absensi::where('user_id', $userId)
                ->where('jenis_ketidakhadiran', 'lainnya')
                ->where('approval_status', 'approved')
                ->count();

            $totalCuti = Cuti::where('user_id', $userId)
                ->where('status', 'approved')
                ->get()
                ->sum(function ($cuti) {
                    return Carbon::parse($cuti->tanggal_mulai)
                        ->diffInDays(Carbon::parse($cuti->tanggal_selesai)) + 1;
                });

            // Get tasks statistics - include both assigned_to and assigned_to_ids
            $totalTasks = Task::where(function($q) use ($userId) {
                    $q->where('assigned_to', $userId)
                      ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
                })->count();
            $pendingTasks = Task::where(function($q) use ($userId) {
                    $q->where('assigned_to', $userId)
                      ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
                })
                ->where('status', '!=', 'selesai')
                ->count();
            $completedTasks = Task::where(function($q) use ($userId) {
                    $q->where('assigned_to', $userId)
                      ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
                })
                ->where('status', 'selesai')
                ->count();

            // Get leave requests
            $pendingCuti = Cuti::where('user_id', $userId)
                ->where('status', 'pending')
                ->count();

            return response()->json([
                'success' => true,
                'attendance_status' => $absensiHariIni && $absensiHariIni->jam_masuk ? (($absensiHariIni->late_minutes && $absensiHariIni->late_minutes > 0) ? 'Terlambat' : 'Tepat Waktu') : ($absensiHariIni && $absensiHariIni->jenis_ketidakhadiran ? $absensiHariIni->jenis_ketidakhadiran_label : 'Belum Absen'),
                'attendance_today' => $absensiHariIni ? [
                    'jam_masuk' => $absensiHariIni->jam_masuk,
                    'jam_pulang' => $absensiHariIni->jam_pulang,
                ] : null,
                'total_hadir' => $totalHadir,
                'total_terlambat' => $totalTerlambat,
                'total_izin' => $totalIzin,
                'total_sakit' => $totalSakit,
                'total_absen' => $totalAbsen,
                'total_cuti' => $totalCuti,
                'tugas_count' => $pendingTasks,
                'data' => [
                    'absensi_today' => $absensiHariIni ? [
                        // Use late_minutes from database to determine status
                        'status' => $absensiHariIni->jam_masuk ? ((is_numeric($absensiHariIni->late_minutes) && $absensiHariIni->late_minutes > 0) ? 'Terlambat' : 'Tepat Waktu') : ($absensiHariIni->jenis_ketidakhadiran ? $absensiHariIni->jenis_ketidakhadiran_label : 'Belum Absen'),
                        'jam_masuk' => $absensiHariIni->jam_masuk,
                        'jam_pulang' => $absensiHariIni->jam_pulang,
                        'keterangan' => $absensiHariIni->keterangan,
                        'late_minutes' => is_numeric($absensiHariIni->late_minutes) ? (int)$absensiHariIni->late_minutes : 0,
                    ] : [
                        'status' => 'Belum Absen',
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                        'keterangan' => null,
                        'late_minutes' => 0,
                    ],
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

            // 0. Validasi: Cek apakah jam sekarang sudah mencapai jam kerja mulai (START_TIME)
            $operationalHours = Setting::where('key', 'operational_hours')->first();
            $startTime = '08:00';
            if ($operationalHours) {
                $settings = json_decode($operationalHours->value, true);
                $startTime = $settings['start_time'] ?? '08:00';
            }

            // Parse start time
            [$startHour, $startMin] = explode(':', $startTime);
            $startSeconds = (int)$startHour * 3600 + (int)$startMin * 60;

            // Get current time in WIB (UTC+7)
            $nowWIB = now('Asia/Jakarta');
            $currentSeconds = $nowWIB->hour * 3600 + $nowWIB->minute * 60 + $nowWIB->second;

            if ($currentSeconds < $startSeconds) {
                $secondsUntilStart = $startSeconds - $currentSeconds;
                $hoursUntil = intdiv($secondsUntilStart, 3600);
                $minutesUntil = intdiv($secondsUntilStart % 3600, 60);

                return response()->json([
                    'success' => false,
                    'message' => "Absen masuk baru bisa dilakukan mulai pukul {$startTime} WIB. Sisa waktu: {$hoursUntil} jam {$minutesUntil} menit."
                ], 403);
            }

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
                    // Simpan status konkret (Terlambat/Tepat Waktu) sehingga konsisten saat dibaca kembali
                    'status' => $status
                    , 'late_minutes' => $lateMinutes
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
                        if (Schema::hasColumn('tasks', 'target_divisi_id')) $q->where('target_divisi_id', $userDivisi);
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
                    'judul' => $task->judul ?: $task->nama_tugas,
                    'nama_tugas' => $task->nama_tugas ?: $task->judul,
                    'deskripsi' => $task->deskripsi,
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : null,
                    'status' => $task->status,
                    'target_type' => $task->target_type ?? 'unknown',
                    'assignee_text' => $task->assignee ? $task->assignee->name : ($task->target_type === 'divisi' ? 'Divisi' : 'Unknown'),
                    'assignee_divisi' => $assigneeDivisi, // Tambahan data divisi
                    'creator_name' => $task->creator ? $task->creator->name : 'System',
                ];
            });

            return response()->json(['success' => true, 'data' => $transformedTasks->toArray()]);
        } catch (\Exception $e) {
            Log::error('API Get Tasks Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['success' => false, 'message' => 'Gagal memuat tugas: ' . $e->getMessage()], 500);
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
            
            // Use `tanggal` column from CatatanRapat model
            $dates = CatatanRapat::select('tanggal')
                ->distinct()
                ->orderBy('tanggal', 'desc')
                ->pluck('tanggal')
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
            
            $meetingNotes = CatatanRapat::whereDate('tanggal', $date)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'topik', 'hasil_diskusi', 'keputusan', 'tanggal', 'created_at']);

            $formattedNotes = $meetingNotes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'topik' => $note->topik,
                    'hasil_diskusi' => $note->hasil_diskusi,
                    'keputusan' => $note->keputusan,
                    'tanggal' => $note->tanggal,
                    'formatted_tanggal' => Carbon::parse($note->tanggal)->translatedFormat('d F Y'),
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
            
            $dates = CatatanRapat::select('tanggal')
                ->distinct()
                ->orderBy('tanggal', 'desc')
                ->pluck('tanggal')
                ->map(function($date) { return Carbon::parse($date)->format('Y-m-d'); })
                ->toArray();
                
            return response()->json($dates);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * API untuk mendapatkan catatan rapat berdasarkan tanggal
     */
    public function getMeetingNotes(Request $request)
    {
        try {
            if (!auth()->check()) return response()->json(['success' => false], 401);
            $date = $request->query('date');
            if (!$date) return response()->json(['success' => false, 'message' => 'Date required'], 400);
            
            if (!Schema::hasTable('catatan_rapats')) {
                return response()->json([]);
            }
            
            $meetingNotes = CatatanRapat::whereDate('tanggal', $date)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'topik', 'hasil_diskusi', 'keputusan', 'tanggal', 'created_at']);
            
            $formattedNotes = $meetingNotes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'topik' => $note->topik,
                    'hasil_diskusi' => $note->hasil_diskusi,
                    'keputusan' => $note->keputusan,
                    'tanggal' => $note->tanggal,
                    'formatted_tanggal' => Carbon::parse($note->tanggal)->translatedFormat('d F Y'),
                    'created_at' => $note->created_at->format('Y-m-d H:i:s'),
                    'formatted_created_at' => $note->created_at->translatedFormat('d F Y H:i'),
                ];
            });
            
            return response()->json($formattedNotes->toArray());
            
        } catch (\Exception $e) {
            \Log::error('Error in getMeetingNotes: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
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
            
            // Use created_at for announcement dates
            $dates = Pengumuman::select('created_at')
                ->distinct()
                ->orderBy('created_at', 'desc')
                ->pluck('created_at')
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
            
            $announcements = Pengumuman::orderBy('created_at', 'desc')
                ->limit(20)
                ->get(['id', 'judul', 'isi_pesan', 'lampiran', 'created_at']);

            $formattedAnnouncements = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'judul' => $announcement->judul,
                    'isi' => $announcement->isi_pesan,
                    'tanggal' => $announcement->created_at->format('Y-m-d'),
                    'formatted_tanggal' => Carbon::parse($announcement->created_at)->translatedFormat('d F Y'),
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
            
            $announcements = Pengumuman::orderBy('created_at', 'desc')
                ->get();
                
            $formattedAnnouncements = $announcements->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'isi_pesan' => $item->isi_pesan,
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

    /**
     * API untuk mendapatkan daftar tanggal yang memiliki pengumuman
     */
    public function getAnnouncementsDates()
    {
        try {
            if (!Schema::hasTable('pengumuman')) {
                return response()->json([]);
            }
            
            $dates = Pengumuman::select('created_at')
                ->distinct()
                ->orderBy('created_at', 'desc')
                ->pluck('created_at')
                ->map(function($date) { return Carbon::parse($date)->format('Y-m-d'); })
                ->toArray();
            
            return response()->json($dates);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * API untuk mendapatkan pengumuman berdasarkan tanggal
     */
    public function getAnnouncementsByDate(Request $request)
    {
        try {
            if (!auth()->check()) return response()->json(['success' => false], 401);
            $date = $request->query('date');
            if (!$date) return response()->json(['success' => false, 'message' => 'Date required'], 400);
            
            if (!Schema::hasTable('pengumuman')) {
                return response()->json([]);
            }
            
            $announcements = Pengumuman::whereDate('created_at', $date)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $formattedAnnouncements = $announcements->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'isi_pesan' => $item->isi_pesan,
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

    /**
     * Get detail karyawan dengan file/dokumen
     */
    public function getDetailApi($id)
    {
        try {
            $karyawan = User::with(['files'])->find($id);
            
            if (!$karyawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan tidak ditemukan'
                ], 404);
            }
            
            // Check if user is authorized to view this karyawan
            $authUser = Auth::user();
            if ($authUser->role === 'manager_divisi' && $karyawan->divisi_id !== $authUser->divisi_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke data karyawan ini'
                ], 403);
            }
            
            $data = [
                'id' => $karyawan->id,
                'nama' => $karyawan->name,
                'name' => $karyawan->name,
                'email' => $karyawan->email,
                'role' => $karyawan->role,
                'divisi' => $karyawan->divisi,
                'divisi_id' => $karyawan->divisi_id,
                'status_kerja' => $karyawan->status_kerja,
                'status_karyawan' => $karyawan->status_karyawan,
                'kontak' => $karyawan->kontak,
                'alamat' => $karyawan->alamat,
                'foto' => $karyawan->foto,
                'files' => []
            ];
            
            // Get files if relationship exists
            if ($karyawan->files && count($karyawan->files) > 0) {
                $data['files'] = $karyawan->files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'name' => $file->nama ?? $file->name ?? 'File',
                        'nama' => $file->nama ?? $file->name ?? 'File',
                        'url' => $file->url ?? '/storage/' . $file->path
                    ];
                })->toArray();
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting karyawan detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail karyawan'
            ], 500);
        }
    }

    /**
     * Get task acceptance status
     * NOTE: Dengan design baru, setiap task hanya punya 1 assignee
     * Method ini untuk backward compatibility dengan old multi-assign tasks
     */
    public function getAcceptanceStatus($taskId)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $task = Task::find($taskId);
            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }

            // Validasi bahwa user adalah yang ditugaskan
            $isAssigned = $task->assigned_to == $user->id || 
                         (is_array($task->assigned_to_ids) && in_array($user->id, $task->assigned_to_ids));
            
            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk melihat status penerimaan tugas ini'
                ], 403);
            }

            // Jika task hanya punya 1 assignee (new design), return simple response
            if (!$task->assigned_to_ids || !is_array($task->assigned_to_ids) || count($task->assigned_to_ids) <= 1) {
                return response()->json([
                    'success' => true,
                    'acceptance_status' => [
                        'total' => 1,
                        'accepted' => $task->status === 'proses' ? 1 : 0,
                        'pending' => $task->status === 'pending' ? 1 : 0,
                        'rejected' => 0,
                        'percentage' => $task->status === 'proses' ? 100 : 0,
                        'is_fully_accepted' => $task->status === 'proses',
                        'is_any_accepted' => $task->status === 'proses',
                        'is_any_rejected' => false
                    ],
                    'acceptance_details' => [
                        [
                            'user_id' => $task->assigned_to,
                            'user_name' => $task->assignee->name ?? 'Unknown',
                            'user_email' => $task->assignee->email ?? 'Unknown',
                            'status' => $task->status === 'proses' ? 'accepted' : 'pending',
                            'accepted_at' => $task->status === 'proses' ? now() : null,
                            'notes' => null
                        ]
                    ]
                ]);
            }

            // Untuk old multi-assign tasks: initialize jika belum ada
            $this->initializeTaskAcceptances($task);

            return response()->json([
                'success' => true,
                'acceptance_status' => $task->getAcceptanceStatus(),
                'acceptance_details' => $task->getAcceptanceDetails()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting acceptance status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan status penerimaan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accept task - mengubah status dari pending menjadi proses
     */
    public function acceptTask(Request $request, $taskId)
    {
        try {
            $user = Auth::user();
            
            // Validasi user
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            // Cari task
            $task = Task::find($taskId);
            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }

            // Validasi bahwa user adalah yang ditugaskan
            $isAssigned = $task->assigned_to == $user->id || 
                         (is_array($task->assigned_to_ids) && in_array($user->id, $task->assigned_to_ids));
            
            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menerima tugas ini'
                ], 403);
            }

            // NEW DESIGN: Setiap task hanya punya 1 assignee
            // Jadi langsung ubah status menjadi proses tanpa menunggu assignee lain
            $task->status = 'proses';
            $task->save();

            // OPTIONAL: Jika ada assigned_to_ids (old multi-assign task), track dengan task_acceptances
            // Untuk backward compatibility
            if ($task->assigned_to_ids && is_array($task->assigned_to_ids) && count($task->assigned_to_ids) > 1) {
                // Initialize jika belum ada
                if (!$task->acceptances()->exists()) {
                    $this->initializeTaskAcceptances($task);
                }

                // Update status karyawan ini
                $acceptance = TaskAcceptance::updateOrCreate(
                    [
                        'task_id' => $taskId,
                        'user_id' => $user->id
                    ],
                    [
                        'status' => 'accepted',
                        'accepted_at' => now(),
                        'notes' => $request->input('notes')
                    ]
                );

                // Check apakah semua sudah accept
                $acceptanceStatus = $task->getAcceptanceStatus();
                if (!$acceptanceStatus['is_fully_accepted']) {
                    // Update message jika belum semua accept
                    return response()->json([
                        'success' => true,
                        'message' => 'Tugas berhasil diterima. Status sudah berubah menjadi Dalam Proses',
                        'data' => [
                            'task_id' => $task->id,
                            'task_status' => $task->status,
                            'acceptance_status' => $acceptanceStatus,
                            'acceptance_details' => $task->getAcceptanceDetails()
                        ]
                    ]);
                }
            }

            Log::info('Task accepted by karyawan', [
                'task_id' => $taskId,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'new_status' => 'proses'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diterima. Status berubah menjadi Dalam Proses',
                'data' => [
                    'task_id' => $task->id,
                    'task_status' => $task->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error accepting task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menerima tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper method untuk inisialisasi task acceptances
    private function initializeTaskAcceptances($task)
    {
        // Cek apakah sudah ada acceptance records
        if ($task->acceptances()->exists()) {
            return;
        }

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