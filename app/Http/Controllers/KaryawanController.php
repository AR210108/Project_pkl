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
            $attendanceStatus = $absenToday->status;
        }

        // Hitung Jumlah Ketidakhadiran (Sakit, Izin, Cuti yang disetujui)
        $ketidakhadiranCount = Absensi::where('user_id', $userId)
            ->where('approval_status', 'approved')
            ->whereIn('status', ['Cuti', 'Sakit', 'Izin'])
            ->count();

        // Hitung Jumlah Tugas dari tabel tasks
        $userDivisi = $user->divisi;
        $tugasCount = Task::where(function ($query) use ($userId, $userDivisi) {
            // Tugas yang ditugaskan langsung ke user
            $query->where('assigned_to', $userId)
                // ATAU tugas yang ditugaskan ke divisi user
                ->orWhere(function ($q) use ($userDivisi) {
                    $q->where('target_type', 'divisi')
                        ->where('target_divisi', $userDivisi);
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

            // PERBAIKAN UTAMA: Query yang lebih komprehensif
            $tasks = Task::where(function ($query) use ($userId, $userDivisi) {
                // 1. Tugas untuk divisi (yang paling penting!)
                $query->where('target_type', 'divisi')
                    ->where('target_divisi', $userDivisi);
            })
                ->orWhere(function ($query) use ($userId) {
                    // 2. Tugas yang ditugaskan langsung ke user
                    $query->where('assigned_to', $userId)
                        ->where('target_type', 'karyawan');
                })
                ->orWhere(function ($query) use ($userId) {
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
        $tugasCount = Task::where(function ($query) use ($userId, $userDivisi) {
            $query->where('assigned_to', $userId)
                ->orWhere(function ($q) use ($userDivisi) {
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

            $tasks = Task::where(function ($query) use ($userId, $userDivisi) {
                $query->where('target_type', 'divisi')
                    ->where('target_divisi', $userDivisi);
            })
                ->orWhere(function ($query) use ($userId) {
                    $query->where('assigned_to', $userId)
                        ->where('target_type', 'karyawan');
                })
                ->orWhere(function ($query) use ($userId) {
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
            $transformedTasks = $tasks->map(function ($task) use ($userId, $userDivisi) {
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

            return response()->json($transformedTasks);

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
}