<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    // Konstanta untuk batas waktu
    private const LIMIT_HOUR = 9;
    private const LIMIT_MINUTE = 5;
    private const LIMIT_TIME = '09:05';

    /* =====================================================
     |  ADMIN VIEW
     ===================================================== */
    public function index()
    {
        // PERBAIKAN: Validasi tanggal sebelum diproses
        $today = Carbon::now()->format('Y-m-d'); // Gunakan now() bukan today()

        // Debug logging untuk memastikan tanggal benar
        \Log::info("Admin index accessed. Today date: {$today}");

        // Hanya jalankan markAbsentEmployees jika tanggal valid
        if ($this->isValidDate($today)) {
            $this->markAbsentEmployees($today);
        } else {
            \Log::warning("Tanggal tidak valid untuk markAbsentEmployees: {$today}");
        }

        // Mengambil statistik dari API
        $statsResponse = $this->apiStatistics();
        $stats = $statsResponse->getData(true)['data'];

        // Mengambil data kehadiran (ada jam_masuk)
        $attendances = Absensi::with('user')
            ->whereNotNull('jam_masuk')
            ->orderBy('tanggal', 'desc')
            ->limit(100) // Batasi untuk performa
            ->get();

        // Mengambil data ketidakhadiran (ada jenis_ketidakhadiran)
        $ketidakhadiran = Absensi::with('user')
            ->whereNotNull('jenis_ketidakhadiran')
            ->orderBy('tanggal', 'desc')
            ->limit(100)
            ->get();

        // Hanya mengambil user dengan role 'karyawan' untuk dropdown
        $users = User::where('role', 'karyawan')->get();

        return view('admin.absensi', compact('stats', 'attendances', 'ketidakhadiran', 'users'));
    }

    /**
     * Menampilkan halaman kelola absensi untuk General Manager
     * 
     * @return \Illuminate\View\View
     */
    public function kelolaAbsen()
    {
        // Pastikan metode ini ada di AbsensiController
        $today = Carbon::now()->format('Y-m-d');
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        // Debug logging
        \Log::info("General Manager kelolaAbsen accessed. Date: {$today}");

        // Total karyawan
        $total_karyawan = User::where('role', 'karyawan')->count();

        // Logika statistik dengan query yang benar
        $stats = [
            'total_hadir' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereNotNull('jam_masuk')
                ->where('approval_status', 'approved')
                ->count(),

            'total_izin' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('jenis_ketidakhadiran', 'izin')
                ->where('approval_status', 'approved')
                ->count(),

            'total_cuti' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('jenis_ketidakhadiran', 'cuti')
                ->where('approval_status', 'approved')
                ->count(),

            'total_dinas_luar' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('jenis_ketidakhadiran', 'dinas-luar')
                ->where('approval_status', 'approved')
                ->count(),

            'total_sakit' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('jenis_ketidakhadiran', 'sakit')
                ->where('approval_status', 'approved')
                ->count(),

            'total_tidak_hadir' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereNull('jam_masuk')
                ->whereNull('jenis_ketidakhadiran')
                ->where('approval_status', 'approved')
                ->count(),

            'total_karyawan' => $total_karyawan,
            'periode' => Carbon::now()->translatedFormat('F Y'),
        ];

        // Debug: log ke file
        \Log::info('General Manager kelolaAbsen stats:', $stats);

        $users = User::where('role', 'karyawan')->get();

        return view('general_manajer.kelola_absen', compact('stats', 'users'));
    }

    /**
     * Menampilkan halaman rekap absensi untuk Pemilik
     * 
     * @return \Illuminate\View\View
     */
    public function rekapAbsensi(Request $request)
    {
        // Ambil input dari form filter
        $tanggalMulai = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->get('tanggal_akhir', Carbon::now()->format('Y-m-d'));
        $divisiFilter = $request->get('divisi');

        \Log::info("Pemilik rekapAbsensi accessed. Filter: {$tanggalMulai} to {$tanggalAkhir}, Divisi: {$divisiFilter}");

        // Query dasar untuk statistik dan data
        $baseQuery = Absensi::whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);

        // Filter berdasarkan divisi jika dipilih
        if ($divisiFilter) {
            $baseQuery->whereHas('user', function ($q) use ($divisiFilter) {
                $q->where('divisi', $divisiFilter);
            });
        }

        // --- 1. Hitung Statistik ---
        // Sesuaikan dengan key yang diharapkan oleh view
        $stats = [
            'total_tepat_waktu' => (clone $baseQuery)->whereNotNull('jam_masuk')
                ->whereTime('jam_masuk', '<=', self::LIMIT_TIME . ':00')
                ->count(),
            'total_terlambat' => (clone $baseQuery)->whereNotNull('jam_masuk')
                ->whereTime('jam_masuk', '>', self::LIMIT_TIME . ':00')
                ->count(),
            'total_tidak_masuk' => (clone $baseQuery)->whereNull('jam_masuk')
                ->whereNull('jenis_ketidakhadiran')
                ->count(),
            'total_izin' => (clone $baseQuery)->where('jenis_ketidakhadiran', 'izin')->count(),
            'total_cuti' => (clone $baseQuery)->where('jenis_ketidakhadiran', 'cuti')->count(),
            'total_sakit' => (clone $baseQuery)->where('jenis_ketidakhadiran', 'sakit')->count(),
            'total_dinas_luar' => (clone $baseQuery)->where('jenis_ketidakhadiran', 'dinas-luar')->count(),
        ];

        // --- 2. Ambil Data untuk Tabel ---
        // Data Kehadiran (ada jam_masuk)
        $attendances = (clone $baseQuery)
            ->with('user:id,name,divisi')
            ->whereNotNull('jam_masuk')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Data Ketidakhadiran (ada jenis_ketidakhadiran)
        $ketidakhadiran = (clone $baseQuery)
            ->with('user:id,name,divisi')
            ->whereNotNull('jenis_ketidakhadiran')
            ->orderBy('tanggal', 'desc')
            ->get();

        // --- 3. Ambil Data untuk Filter Dropdown ---
        $divisions = User::select('divisi')
            ->whereNotNull('divisi')
            ->where('role', 'karyawan')
            ->distinct()
            ->pluck('divisi');

        \Log::info("Rekap Data: Attendances=" . $attendances->count() . ", Ketidakhadiran=" . $ketidakhadiran->count());

        // Kirim semua data ke view
        return view('pemilik.rekap_absensi', compact(
            'stats',
            'attendances',
            'ketidakhadiran',
            'divisions',
            'tanggalMulai',
            'tanggalAkhir',
            'divisiFilter'
        ));
    }


    /**
     * Menampilkan halaman kelola absensi untuk General Manager (versi baru)
     * 
     * @return \Illuminate\View\View
     */
    public function kelolaAbsensi()
    {
        // Debug logging
        \Log::info("General Manager kelolaAbsensi accessed. Date: " . Carbon::now()->format('Y-m-d'));

        // Hitung statistik bulan ini dengan query yang lebih efisien
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $statsQuery = Absensi::select(
            DB::raw('COUNT(CASE WHEN jam_masuk IS NOT NULL AND approval_status = "approved" THEN 1 END) as total_hadir'),
            DB::raw('COUNT(CASE WHEN jenis_ketidakhadiran = "izin" AND approval_status = "approved" THEN 1 END) as total_izin'),
            DB::raw('COUNT(CASE WHEN jenis_ketidakhadiran = "cuti" AND approval_status = "approved" THEN 1 END) as total_cuti'),
            DB::raw('COUNT(CASE WHEN jenis_ketidakhadiran = "dinas-luar" AND approval_status = "approved" THEN 1 END) as total_dinas_luar'),
            DB::raw('COUNT(CASE WHEN jenis_ketidakhadiran = "sakit" AND approval_status = "approved" THEN 1 END) as total_sakit'),
            DB::raw('COUNT(CASE WHEN jam_masuk IS NULL AND jenis_ketidakhadiran IS NULL AND approval_status = "approved" THEN 1 END) as total_tidak_hadir')
        )
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->first();

        // Total karyawan - PERBAIKAN: HAPUS FILTER STATUS
        $total_karyawan = User::where('role', 'karyawan')->count();

        $stats = [
            'total_hadir' => $statsQuery->total_hadir ?? 0,
            'total_izin' => $statsQuery->total_izin ?? 0,
            'total_cuti' => $statsQuery->total_cuti ?? 0,
            'total_dinas_luar' => $statsQuery->total_dinas_luar ?? 0,
            'total_sakit' => $statsQuery->total_sakit ?? 0,
            'total_tidak_hadir' => $statsQuery->total_tidak_hadir ?? 0,
            'total_karyawan' => $total_karyawan,
            'periode' => Carbon::now()->translatedFormat('F Y'),
        ];

        return view('general_manajer.kelola_absensi', compact('stats'));
    }

    /**
     * Menandai karyawan yang tidak hadir pada tanggal tertentu
     * 
     * @param string $date
     * @return void
     */
    private function markAbsentEmployees($date)
    {
        // VALIDASI TANGGAL: Cek apakah tanggal valid
        if (!$this->isValidDate($date)) {
            \Log::error("markAbsentEmployees: Tanggal tidak valid - {$date}");
            return;
        }

        \Log::info("markAbsentEmployees dimulai untuk tanggal: {$date}");

        // 1. Ambil SEMUA karyawan (Hanya yang role 'karyawan')
        $allEmployees = User::where('role', 'karyawan')->get();
        \Log::info("Total karyawan ditemukan: " . $allEmployees->count());

        // 2. Ambil semua data absensi untuk tanggal yang ditentukan
        $dateAttendances = Absensi::whereDate('tanggal', $date)->get();
        \Log::info("Absensi pada tanggal {$date}: " . $dateAttendances->count());

        // 3. Dapatkan ID karyawan yang sudah absen pada tanggal tersebut
        $checkedInEmployeeIds = $dateAttendances->pluck('user_id')->toArray();

        // 4. Cari karyawan yang belum absen
        $absentEmployees = $allEmployees->whereNotIn('id', $checkedInEmployeeIds);
        \Log::info("Karyawan belum absen: " . $absentEmployees->count());

        // 5. Untuk setiap karyawan yang tidak absen, buat record "Tidak Hadir"
        $createdCount = 0;
        foreach ($absentEmployees as $employee) {
            // Cek dulu agar tidak duplikat
            $alreadyMarkedAbsent = Absensi::where('user_id', $employee->id)
                ->whereDate('tanggal', $date)
                ->whereNull('jam_masuk')
                ->whereNull('jenis_ketidakhadiran')
                ->exists();

            if (!$alreadyMarkedAbsent) {
                Absensi::create([
                    'user_id' => $employee->id,
                    'tanggal' => $date,
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'approval_status' => 'approved',
                    'approved_by' => auth()->check() ? auth()->user()->id : null,
                    'approved_at' => now(),
                    'jenis_ketidakhadiran' => null,
                    'keterangan' => 'Tidak hadir tanpa keterangan',
                ]);
                $createdCount++;
            }
        }

        \Log::info("markAbsentEmployees selesai. {$createdCount} record dibuat.");
    }

    /**
     * Validasi apakah tanggal valid (tidak di masa depan jauh)
     * 
     * @param string $date
     * @return bool
     */
    private function isValidDate($date)
    {
        try {
            $dateObj = Carbon::parse($date);
            $now = Carbon::now();

            // Validasi: tanggal tidak boleh lebih dari 1 bulan di masa depan
            $maxAllowedDate = $now->copy()->addMonth();

            if ($dateObj->gt($maxAllowedDate)) {
                \Log::warning("Tanggal {$date} lebih dari 1 bulan di masa depan");
                return false;
            }

            // Validasi: tanggal tidak boleh lebih dari 1 tahun di masa lalu
            $minAllowedDate = $now->copy()->subYear();
            if ($dateObj->lt($minAllowedDate)) {
                \Log::warning("Tanggal {$date} lebih dari 1 tahun di masa lalu");
                return false;
            }

            return true;
        } catch (\Exception $e) {
            \Log::error("Error parsing date {$date}: " . $e->getMessage());
            return false;
        }
    }

    /* =====================================================
     |  API KARYAWAN
     ===================================================== */

    /**
     * API: Status absensi hari ini untuk karyawan
     */
    public function apiTodayStatus()
    {
        try {
            $user = Auth::user();
            $today = Carbon::now()->format('Y-m-d'); // Gunakan now()

            \Log::info("API TodayStatus diakses oleh user {$user->id} untuk tanggal: {$today}");

            // Cek absensi hari ini
            $attendance = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            // Hitung keterlambatan jika ada - DIUBAH MENJADI 09:05
            $lateMinutes = 0;
            $isTerlambat = false;

            if ($attendance && $attendance->jam_masuk) {
                $jamMasuk = Carbon::parse($attendance->jam_masuk);
                $jamBatas = Carbon::parse(self::LIMIT_TIME); // DIUBAH: gunakan konstanta

                if ($jamMasuk->gt($jamBatas)) {
                    $isTerlambat = true;
                    $lateMinutes = $jamMasuk->diffInMinutes($jamBatas);
                    \Log::info("Karyawan {$user->id} terlambat {$lateMinutes} menit dari batas " . self::LIMIT_TIME);
                }
            }

            // Format response
            $response = [
                'success' => true,
                'data' => $attendance ? [
                    'id' => $attendance->id,
                    'tanggal' => $attendance->tanggal->format('Y-m-d'),
                    'jam_masuk' => $attendance->jam_masuk,
                    'jam_pulang' => $attendance->jam_pulang,
                    'late_minutes' => $lateMinutes,
                    'is_terlambat' => $isTerlambat,
                    'jenis_ketidakhadiran' => $attendance->jenis_ketidakhadiran,
                    'jenis_ketidakhadiran_label' => $attendance->getJenisKetidakhadiranLabelAttribute(),
                    'approval_status' => $attendance->approval_status,
                    'keterangan' => $attendance->keterangan,
                    'is_early_checkout' => $attendance->is_early_checkout,
                    'early_checkout_reason' => $attendance->early_checkout_reason,
                ] : null
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error("Error apiTodayStatus: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status hari ini: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Riwayat absensi karyawan
     */
    public function apiHistory(Request $request)
    {
        try {
            $user = Auth::user();
            $filter = $request->get('filter', 'month');

            \Log::info("API History diakses oleh user {$user->id} dengan filter: {$filter}");

            // Query dasar
            $query = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', '<=', Carbon::now()->addMonth()) // Batasi data masa depan
                ->orderBy('tanggal', 'desc');

            // Filter periode
            $now = Carbon::now();

            if ($filter === 'week') {
                $startOfWeek = $now->copy()->startOfWeek();
                $query->whereDate('tanggal', '>=', $startOfWeek);
            } elseif ($filter === 'month') {
                $startOfMonth = $now->copy()->startOfMonth();
                $query->whereDate('tanggal', '>=', $startOfMonth);
            } elseif ($filter === 'year') {
                $startOfYear = $now->copy()->startOfYear();
                $query->whereDate('tanggal', '>=', $startOfYear);
            }

            $attendanceData = $query->get()->map(function ($item) {
                // Hitung keterlambatan - DIUBAH MENJADI 09:05
                $lateMinutes = 0;
                $isTerlambat = false;

                if ($item->jam_masuk) {
                    $jamMasuk = Carbon::parse($item->jam_masuk);
                    $jamBatas = Carbon::parse(self::LIMIT_TIME); // DIUBAH: gunakan konstanta

                    if ($jamMasuk->gt($jamBatas)) {
                        $isTerlambat = true;
                        $lateMinutes = $jamMasuk->diffInMinutes($jamBatas);
                    }
                }

                return [
                    'id' => $item->id,
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                    'jam_masuk' => $item->jam_masuk,
                    'jam_pulang' => $item->jam_pulang,
                    'late_minutes' => $lateMinutes,
                    'is_terlambat' => $isTerlambat,
                    'jenis_ketidakhadiran' => $item->jenis_ketidakhadiran,
                    'jenis_ketidakhadiran_label' => $item->getJenisKetidakhadiranLabelAttribute(),
                    'approval_status' => $item->approval_status,
                    'keterangan' => $item->keterangan,
                    'is_early_checkout' => $item->is_early_checkout,
                    'early_checkout_reason' => $item->early_checkout_reason,
                ];
            });

            \Log::info("API History: " . count($attendanceData) . " record ditemukan");

            return response()->json([
                'success' => true,
                'data' => $attendanceData
            ]);

        } catch (\Exception $e) {
            \Log::error("Error apiHistory: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil riwayat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Absen masuk karyawan
     */
    public function apiAbsenMasuk(Request $request)
    {
        try {
            $user = Auth::user();
            $today = Carbon::now()->format('Y-m-d');

            \Log::info("API AbsenMasuk diakses oleh user {$user->id} untuk tanggal: {$today}");

            // Cek apakah sudah absen hari ini
            $existing = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            if ($existing) {
                // Cek apakah sudah absen masuk
                if ($existing->jam_masuk) {
                    \Log::warning("User {$user->id} sudah absen masuk hari ini");
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah melakukan absen masuk hari ini'
                    ], 400);
                }

                // Cek apakah ada jenis ketidakhadiran
                if ($existing->jenis_ketidakhadiran) {
                    \Log::warning("User {$user->id} sudah mengajukan {$existing->jenis_ketidakhadiran}");
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah mengajukan ' . $existing->getJenisKetidakhadiranLabelAttribute() . ' hari ini'
                    ], 400);
                }
            }

            // Cek apakah ada pengajuan yang disetujui untuk rentang tanggal
            $approvedAbsence = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', '<=', $today)
                ->whereDate('tanggal_akhir', '>=', $today)
                ->whereNotNull('jenis_ketidakhadiran')
                ->where('approval_status', 'approved')
                ->first();

            if ($approvedAbsence) {
                \Log::warning("User {$user->id} memiliki pengajuan {$approvedAbsence->jenis_ketidakhadiran} yang disetujui");
                return response()->json([
                    'success' => false,
                    'message' => 'Anda memiliki pengajuan ' . $approvedAbsence->getJenisKetidakhadiranLabelAttribute() . ' yang disetujui untuk hari ini'
                ], 400);
            }

            // Hitung keterlambatan - DIUBAH MENJADI 09:05
            $currentTime = Carbon::now();
            $jamMasuk = $currentTime->format('H:i:s');
            $jamBatas = Carbon::parse(self::LIMIT_TIME); // DIUBAH: gunakan konstanta

            $lateMinutes = 0;
            $keterangan = null;

            if ($currentTime->gt($jamBatas)) {
                $lateMinutes = $currentTime->diffInMinutes($jamBatas);
                $keterangan = 'Terlambat ' . $lateMinutes . ' menit';
                \Log::info("User {$user->id} terlambat {$lateMinutes} menit dari batas " . self::LIMIT_TIME);
            }

            // Buat atau update record
            if ($existing) {
                $attendance = $existing;
                \Log::info("Update existing attendance for user {$user->id}");
            } else {
                $attendance = new Absensi();
                $attendance->user_id = $user->id;
                $attendance->tanggal = $today;
                \Log::info("Create new attendance for user {$user->id}");
            }

            $attendance->jam_masuk = $jamMasuk;
            if ($keterangan) {
                $attendance->keterangan = $keterangan;
            }
            $attendance->approval_status = 'approved';
            $attendance->save();

            // Hitung ulang untuk response
            $isTerlambat = $lateMinutes > 0;

            \Log::info("AbsenMasuk berhasil untuk user {$user->id}, jam: {$jamMasuk}");

            return response()->json([
                'success' => true,
                'message' => 'Absen masuk berhasil' . ($isTerlambat ? ' (Terlambat)' : ''),
                'data' => [
                    'id' => $attendance->id,
                    'tanggal' => $attendance->tanggal->format('Y-m-d'),
                    'jam_masuk' => $attendance->jam_masuk,
                    'jam_pulang' => $attendance->jam_pulang,
                    'late_minutes' => $lateMinutes,
                    'is_terlambat' => $isTerlambat,
                    'jenis_ketidakhadiran' => null,
                    'approval_status' => 'approved',
                    'keterangan' => $keterangan
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error apiAbsenMasuk: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal absen masuk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Absen pulang karyawan
     */
    public function apiAbsenPulang(Request $request)
    {
        try {
            $user = Auth::user();
            $today = Carbon::now()->format('Y-m-d');

            \Log::info("API AbsenPulang diakses oleh user {$user->id} untuk tanggal: {$today}");

            // Validasi input
            $validator = Validator::make($request->all(), [
                'early_checkout_reason' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                \Log::warning("Validasi gagal untuk user {$user->id}: " . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cek attendance
            $attendance = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            if (!$attendance) {
                \Log::warning("User {$user->id} belum absen masuk");
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum melakukan absen masuk hari ini'
                ], 400);
            }

            if ($attendance->jam_pulang) {
                \Log::warning("User {$user->id} sudah absen pulang");
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absen pulang hari ini'
                ], 400);
            }

            // Cek apakah ada jenis ketidakhadiran
            if ($attendance->jenis_ketidakhadiran) {
                \Log::warning("User {$user->id} memiliki pengajuan {$attendance->jenis_ketidakhadiran}");
                return response()->json([
                    'success' => false,
                    'message' => 'Anda memiliki pengajuan ' . $attendance->getJenisKetidakhadiranLabelAttribute() . ' hari ini'
                ], 400);
            }

            // Update jam pulang
            $currentTime = Carbon::now();
            $jamPulang = $currentTime->format('H:i:s');

            $attendance->jam_pulang = $jamPulang;

            // Cek apakah pulang lebih awal (sebelum jam 17:00)
            if ($currentTime->hour < 17) {
                $attendance->is_early_checkout = true;
                if ($request->has('early_checkout_reason')) {
                    $attendance->early_checkout_reason = $request->early_checkout_reason;
                } else {
                    // Default reason jika tidak diisi
                    $attendance->early_checkout_reason = 'Pulang lebih awal';
                }
                \Log::info("User {$user->id} pulang lebih awal: " . $attendance->early_checkout_reason);
            }

            $attendance->save();

            \Log::info("AbsenPulang berhasil untuk user {$user->id}, jam: {$jamPulang}");

            return response()->json([
                'success' => true,
                'message' => 'Absen pulang berhasil',
                'data' => [
                    'id' => $attendance->id,
                    'tanggal' => $attendance->tanggal->format('Y-m-d'),
                    'jam_masuk' => $attendance->jam_masuk,
                    'jam_pulang' => $attendance->jam_pulang,
                    'is_early_checkout' => $attendance->is_early_checkout,
                    'early_checkout_reason' => $attendance->early_checkout_reason,
                    'jenis_ketidakhadiran' => null,
                    'approval_status' => 'approved'
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error apiAbsenPulang: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal absen pulang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Submit izin/sakit karyawan
     */
    public function apiSubmitIzin(Request $request)
    {
        try {
            $user = Auth::user();

            \Log::info("API SubmitIzin diakses oleh user {$user->id}");

            // Validasi input
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
                'keterangan' => 'required|string|max:1000',
                'jenis' => 'required|in:sakit,izin'
            ]);

            if ($validator->fails()) {
                \Log::warning("Validasi gagal untuk user {$user->id}: " . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $startDate = $data['tanggal'];
            $endDate = $data['tanggal_akhir'];
            $jenis = $data['jenis'];

            // Validasi tanggal tidak di masa depan jauh
            if (!$this->isValidDate($startDate) || !$this->isValidDate($endDate)) {
                \Log::warning("User {$user->id} mencoba submit dengan tanggal tidak valid: {$startDate} - {$endDate}");
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal tidak valid. Maksimal 1 bulan di masa depan.'
                ], 400);
            }

            // Cek tanggal tidak boleh di masa lalu untuk hari pertama
            $today = Carbon::now()->format('Y-m-d');
            if ($startDate < $today && $jenis === 'sakit') {
                \Log::warning("User {$user->id} mencoba submit sakit untuk tanggal lewat: {$startDate}");
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan sakit tidak boleh untuk tanggal yang sudah lewat'
                ], 400);
            }

            // Cek apakah sudah ada attendance di rentang tanggal
            $existingAttendance = Absensi::where('user_id', $user->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->whereNotNull('jam_masuk')
                ->exists();

            if ($existingAttendance) {
                \Log::warning("User {$user->id} sudah absen pada rentang tanggal {$startDate} - {$endDate}");
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absen pada rentang tanggal tersebut'
                ], 400);
            }

            // Cek apakah sudah ada pengajuan yang overlap
            $existingRequest = Absensi::where('user_id', $user->id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->orWhereBetween('tanggal_akhir', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('tanggal', '<=', $startDate)
                                ->where('tanggal_akhir', '>=', $endDate);
                        });
                })
                ->whereNotNull('jenis_ketidakhadiran')
                ->whereIn('approval_status', ['pending', 'approved'])
                ->first();

            if ($existingRequest) {
                \Log::warning("User {$user->id} sudah memiliki pengajuan {$existingRequest->jenis_ketidakhadiran} pada rentang tersebut");
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki pengajuan ' . $existingRequest->jenis_ketidakhadiran . ' pada rentang tanggal tersebut'
                ], 400);
            }

            // Buat pengajuan untuk setiap hari dalam rentang
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            $createdRecords = [];

            \Log::info("User {$user->id} membuat pengajuan {$jenis} dari {$startDate} sampai {$endDate}");

            DB::beginTransaction();

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $record = Absensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $date->format('Y-m-d'),
                    'tanggal_akhir' => $end->format('Y-m-d'),
                    'jenis_ketidakhadiran' => $jenis,
                    'keterangan' => $data['keterangan'],
                    'approval_status' => 'pending',
                ]);

                $createdRecords[] = $record;
            }

            DB::commit();

            // Return data untuk hari pertama
            $firstRecord = $createdRecords[0];

            // Hitung late_minutes untuk konsistensi (selalu 0 untuk pengajuan)
            $lateMinutes = 0;

            \Log::info("Pengajuan {$jenis} berhasil dibuat untuk user {$user->id}, " . count($createdRecords) . " record");

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan ' . $jenis . ' berhasil dikirim',
                'data' => [
                    'tanggal' => $firstRecord->tanggal->format('Y-m-d'),
                    'tanggal_akhir' => $firstRecord->tanggal_akhir->format('Y-m-d'),
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'late_minutes' => $lateMinutes,
                    'is_terlambat' => false,
                    'jenis_ketidakhadiran' => $firstRecord->jenis_ketidakhadiran,
                    'jenis_ketidakhadiran_label' => $firstRecord->getJenisKetidakhadiranLabelAttribute(),
                    'approval_status' => $firstRecord->approval_status,
                    'keterangan' => $firstRecord->keterangan
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error apiSubmitIzin: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    /* =====================================================
     |  API ADMIN
     ===================================================== */
    public function apiIndex(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            $jenis = $request->get('jenis', '');
            $startDate = $request->get('start_date', '');
            $endDate = $request->get('end_date', '');

            \Log::info("API Index absensi diakses dengan parameter: " . json_encode($request->all()));

            $query = Absensi::with(['user:id,name,email,jabatan'])
                ->where('approval_status', 'approved')
                ->whereNotNull('jam_masuk')
                ->whereDate('tanggal', '<=', Carbon::now()->addMonth()) // Batasi data masa depan
                ->orderBy('tanggal', 'desc')
                ->orderBy('jam_masuk', 'desc');

            // Filter pencarian
            if ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filter jenis (hadir atau terlambat) - DIUBAH MENJADI 09:05
            if ($jenis && $jenis !== 'all') {
                if ($jenis === 'terlambat') {
                    $query->whereTime('jam_masuk', '>', self::LIMIT_TIME . ':00');
                } elseif ($jenis === 'hadir') {
                    $query->whereTime('jam_masuk', '<=', self::LIMIT_TIME . ':00');
                }
            }

            // Filter tanggal
            if ($startDate) {
                $query->whereDate('tanggal', '>=', $startDate);
            }

            if ($endDate) {
                $query->whereDate('tanggal', '<=', $endDate);
            }

            // Pagination
            $absensi = $query->paginate($perPage, ['*'], 'page', $page);

            $formattedData = $absensi->map(function ($item) {
                // Hitung keterlambatan jika jam_masuk > 09:05 - DIUBAH
                $isTerlambat = false;
                $keterlambatan = 0;

                if ($item->jam_masuk) {
                    $jamMasuk = Carbon::parse($item->jam_masuk);
                    $jamBatas = Carbon::parse(self::LIMIT_TIME); // DIUBAH

                    if ($jamMasuk->gt($jamBatas)) {
                        $isTerlambat = true;
                        $keterlambatan = $jamMasuk->diffInMinutes($jamBatas);
                    }
                }

                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'name' => $item->user ? $item->user->name : 'Tidak diketahui',
                    'email' => $item->user ? $item->user->email : '',
                    'jabatan' => $item->user ? $item->user->jabatan : '',
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                    'tanggal_formatted' => $item->tanggal->translatedFormat('d F Y'),
                    'jam_masuk' => $item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : '-',
                    'jam_pulang' => $item->jam_pulang ? Carbon::parse($item->jam_pulang)->format('H:i') : '-',
                    'is_terlambat' => $isTerlambat,
                    'keterlambatan' => $keterlambatan,
                    'is_early_checkout' => $item->is_early_checkout,
                    'early_checkout_reason' => $item->early_checkout_reason,
                    'jenis_ketidakhadiran' => $item->jenis_ketidakhadiran,
                    'jenis_ketidakhadiran_label' => $item->jenis_ketidakhadiran_label ?? $this->getJenisKetidakhadiranLabel($item->jenis_ketidakhadiran),
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ];
            });

            \Log::info("API Index: " . $absensi->total() . " record ditemukan");

            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'pagination' => [
                    'current_page' => $absensi->currentPage(),
                    'last_page' => $absensi->lastPage(),
                    'per_page' => $absensi->perPage(),
                    'total' => $absensi->total(),
                    'from' => $absensi->firstItem(),
                    'to' => $absensi->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error apiIndex: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data absensi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk menampilkan data ketidakhadiran (cuti, sakit, izin, tidak masuk)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndexKetidakhadiran(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            $jenis = $request->get('jenis', '');
            $approvalStatus = $request->get('approval_status', '');
            $startDate = $request->get('start_date', '');
            $endDate = $request->get('end_date', '');

            \Log::info("API IndexKetidakhadiran diakses dengan parameter: " . json_encode($request->all()));

            $query = Absensi::with(['user:id,name,email,jabatan', 'approver:id,name'])
                ->whereNotNull('jenis_ketidakhadiran')
                ->whereDate('tanggal', '<=', Carbon::now()->addMonth()) // Batasi data masa depan
                ->orderBy('tanggal', 'desc');

            // Filter pencarian
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhere('reason', 'like', "%{$search}%");
                });
            }

            // Filter jenis ketidakhadiran
            if ($jenis && $jenis !== 'all') {
                $query->where('jenis_ketidakhadiran', $jenis);
            }

            // Filter status persetujuan
            if ($approvalStatus && $approvalStatus !== 'all') {
                $query->where('approval_status', $approvalStatus);
            }

            // Filter tanggal
            if ($startDate) {
                $query->whereDate('tanggal', '>=', $startDate);
            }

            if ($endDate) {
                $query->whereDate('tanggal', '<=', $endDate);
            }

            // Pagination
            $ketidakhadiran = $query->paginate($perPage, ['*'], 'page', $page);

            $formattedData = $ketidakhadiran->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'name' => $item->user ? $item->user->name : 'Tidak diketahui',
                    'email' => $item->user ? $item->user->email : '',
                    'jabatan' => $item->user ? $item->user->jabatan : '',
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                    'tanggal_formatted' => $item->tanggal->translatedFormat('d F Y'),
                    'tanggal_akhir' => $item->tanggal_akhir ? $item->tanggal_akhir->format('Y-m-d') : $item->tanggal->format('Y-m-d'),
                    'tanggal_akhir_formatted' => $item->tanggal_akhir ? $item->tanggal_akhir->translatedFormat('d F Y') : $item->tanggal->translatedFormat('d F Y'),
                    'jenis_ketidakhadiran' => $item->jenis_ketidakhadiran,
                    'jenis_ketidakhadiran_label' => $item->jenis_ketidakhadiran_label ?? $this->getJenisKetidakhadiranLabel($item->jenis_ketidakhadiran),
                    'keterangan' => $item->keterangan,
                    'reason' => $item->reason,
                    'location' => $item->location,
                    'purpose' => $item->purpose,
                    'approval_status' => $item->approval_status,
                    'approval_status_label' => $item->approval_status_label ?? $this->getApprovalStatusLabel($item->approval_status),
                    'rejection_reason' => $item->rejection_reason,
                    'approved_by_name' => $item->approver ? $item->approver->name : null,
                    'approved_at' => $item->approved_at ? $item->approved_at->format('d/m/Y H:i') : null,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ];
            });

            \Log::info("API IndexKetidakhadiran: " . $ketidakhadiran->total() . " record ditemukan");

            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'pagination' => [
                    'current_page' => $ketidakhadiran->currentPage(),
                    'last_page' => $ketidakhadiran->lastPage(),
                    'per_page' => $ketidakhadiran->perPage(),
                    'total' => $ketidakhadiran->total(),
                    'from' => $ketidakhadiran->firstItem(),
                    'to' => $ketidakhadiran->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error apiIndexKetidakhadiran: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data ketidakhadiran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk menyimpan data absensi baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        \Log::info("API Store absensi diakses dengan data: " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i|after:jam_masuk',
            'is_early_checkout' => 'nullable|boolean',
            'early_checkout_reason' => 'nullable|string',
            'jenis_ketidakhadiran' => 'nullable|in:cuti,sakit,izin,dinas-luar,lainnya',
            'reason' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:255',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::warning("Validasi gagal: " . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Validasi tanggal tidak di masa depan jauh
            if (!$this->isValidDate($data['tanggal'])) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }

            // Persiapkan data absensi
            $data = $this->prepareAbsensiData($data);

            // Cek duplikasi
            $existing = Absensi::where('user_id', $data['user_id'])
                ->whereDate('tanggal', $data['tanggal'])
                ->exists();

            if ($existing) {
                throw new \Exception('Sudah ada data absensi untuk karyawan ini pada tanggal tersebut.');
            }

            $absensi = Absensi::create($data);

            DB::commit();

            $message = 'Data absensi berhasil ditambahkan';
            if ($data['jenis_ketidakhadiran']) {
                $jenisLabel = $this->getJenisKetidakhadiranLabel($data['jenis_ketidakhadiran']);
                if ($data['approval_status'] === 'pending') {
                    $message = 'Pengajuan ' . strtolower($jenisLabel) . ' berhasil dibuat dan menunggu persetujuan';
                } else {
                    $message = 'Data ' . strtolower($jenisLabel) . ' berhasil ditambahkan';
                }
            }

            \Log::info("Store berhasil: {$message} untuk user {$data['user_id']}");

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $absensi->load('user')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error apiStore: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah data. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk menyimpan data cuti baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStoreCuti(Request $request)
    {
        \Log::info("API StoreCuti diakses dengan data: " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'jenis_ketidakhadiran' => 'required|in:cuti',
            'keterangan' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::warning("Validasi gagal: " . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Validasi tanggal
            if (!$this->isValidDate($data['tanggal']) || !$this->isValidDate($data['tanggal_akhir'])) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }

            // Set data khusus untuk cuti
            $data['approval_status'] = 'pending';
            $data['jam_masuk'] = null;
            $data['jam_pulang'] = null;

            // Cek duplikasi
            $existing = Absensi::where('user_id', $data['user_id'])
                ->whereDate('tanggal', $data['tanggal'])
                ->where('jenis_ketidakhadiran', 'cuti')
                ->exists();

            if ($existing) {
                throw new \Exception('Sudah ada data cuti untuk karyawan ini pada tanggal tersebut.');
            }

            $cuti = Absensi::create($data);

            DB::commit();

            \Log::info("StoreCuti berhasil untuk user {$data['user_id']} dari {$data['tanggal']} sampai {$data['tanggal_akhir']}");

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil dibuat dan menunggu persetujuan',
                'data' => $cuti->load('user')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error apiStoreCuti: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah data cuti. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk menampilkan detail absensi
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow($id)
    {
        try {
            $absensi = Absensi::with(['user:id,name,email,jabatan', 'approver:id,name'])->findOrFail($id);

            \Log::info("API Show absensi id: {$id}");

            return response()->json([
                'success' => true,
                'data' => $absensi
            ]);
        } catch (\Exception $e) {
            \Log::error("Error apiShow id {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * API untuk memperbarui data absensi
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request, $id)
    {
        \Log::info("API Update absensi id {$id} dengan data: " . json_encode($request->all()));

        $absensi = Absensi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i|after:jam_masuk',
            'is_early_checkout' => 'nullable|boolean',
            'early_checkout_reason' => 'nullable|string',
            'jenis_ketidakhadiran' => 'nullable|in:cuti,sakit,izin,dinas-luar,lainnya',
            'reason' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:255',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal',
            'keterangan' => 'nullable|string',
            'approval_status' => 'nullable|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            \Log::warning("Validasi gagal id {$id}: " . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Validasi tanggal
            if (!$this->isValidDate($data['tanggal'])) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }

            // Persiapkan data absensi
            $data = $this->prepareAbsensiData($data, $absensi);

            $absensi->update($data);

            DB::commit();

            \Log::info("Update berhasil untuk absensi id: {$id}");

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil diperbarui',
                'data' => $absensi->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error apiUpdate id {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk memperbarui data cuti
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdateCuti(Request $request, $id)
    {
        \Log::info("API UpdateCuti id {$id} dengan data: " . json_encode($request->all()));

        $cuti = Absensi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'jenis_ketidakhadiran' => 'required|in:cuti',
            'keterangan' => 'required|string',
            'reason' => 'nullable|string',
            'approval_status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'required_if:approval_status,rejected|string|max:255',
        ]);

        if ($validator->fails()) {
            \Log::warning("Validasi gagal UpdateCuti id {$id}: " . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Validasi tanggal
            if (!$this->isValidDate($data['tanggal']) || !$this->isValidDate($data['tanggal_akhir'])) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }

            // Jika status persetujuan berubah, simpan informasi perubahan
            if (isset($data['approval_status']) && $data['approval_status'] !== 'pending') {
                $data['approved_by'] = auth()->user()->id;
                $data['approved_at'] = now();
            }

            $cuti->update($data);

            DB::commit();

            $statusText = $data['approval_status'] === 'approved' ? 'disetujui' : 'ditolak';
            $message = "Data cuti berhasil {$statusText}.";

            \Log::info("UpdateCuti berhasil id {$id}: {$statusText}");

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $cuti->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error apiUpdateCuti id {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data cuti. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk menghapus data absensi
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        \Log::info("API Destroy absensi id: {$id}");

        DB::beginTransaction();
        try {
            $absensi = Absensi::findOrFail($id);
            $recordType = $absensi->jenis_ketidakhadiran ? $this->getJenisKetidakhadiranLabel($absensi->jenis_ketidakhadiran) : 'Kehadiran';
            $absensi->delete();

            DB::commit();

            \Log::info("Destroy berhasil id {$id}: {$recordType}");

            return response()->json([
                'success' => true,
                'message' => "Data {$recordType} berhasil dihapus."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error apiDestroy id {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk verifikasi pengajuan (Cuti, Sakit, Izin)
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiVerify(Request $request, $id)
    {
        \Log::info("API Verify id {$id} dengan data: " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'approval_status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:approval_status,rejected|string|max:255',
        ]);

        if ($validator->fails()) {
            \Log::warning("Validasi gagal Verify id {$id}: " . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $absensi = Absensi::findOrFail($id);

            // Pastikan hanya jenis ketidakhadiran yang bisa diverifikasi
            if (!$absensi->jenis_ketidakhadiran) {
                \Log::warning("Verify gagal id {$id}: Bukan data ketidakhadiran");
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya data ketidakhadiran yang dapat diverifikasi.'
                ], 400);
            }

            $data = $validator->validated();
            $data['approved_by'] = auth()->user()->id;
            $data['approved_at'] = now();

            $absensi->update($data);

            DB::commit();

            $jenisLabel = $this->getJenisKetidakhadiranLabel($absensi->jenis_ketidakhadiran);
            $statusText = $data['approval_status'] === 'approved' ? 'disetujui' : 'ditolak';
            $message = "Pengajuan {$jenisLabel} berhasil {$statusText}.";

            \Log::info("Verify berhasil id {$id}: {$jenisLabel} {$statusText}");

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $absensi->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error apiVerify id {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiStatistics(Request $request = null)
    {
        try {
            $today = Carbon::now()->format('Y-m-d');

            $startDate = $request?->get('tanggal_mulai') ?? $today;
            $endDate = $request?->get('tanggal_akhir') ?? $today;
            $divisi = $request?->get('divisi');

            \Log::info("API Statistics: {$startDate} sampai {$endDate}, divisi: {$divisi}");

            $query = Absensi::whereBetween('tanggal', [$startDate, $endDate])
                ->whereDate('tanggal', '<=', Carbon::now()->addMonth()); // Batasi data masa depan

            if ($divisi) {
                $query->whereHas('user', function ($q) use ($divisi) {
                    $q->where('divisi', $divisi);
                });
            }

            $stats = [
                // DIUBAH MENJADI 09:05
                'total_tepat_waktu' => (clone $query)->whereTime('jam_masuk', '<=', self::LIMIT_TIME . ':00')->count(),
                'total_terlambat' => (clone $query)->whereTime('jam_masuk', '>', self::LIMIT_TIME . ':00')->count(),
                'total_tidak_masuk' => (clone $query)->whereNull('jam_masuk')->whereNull('jenis_ketidakhadiran')->count(),
                'total_cuti' => (clone $query)->where('jenis_ketidakhadiran', 'cuti')->count(),
                'total_sakit' => (clone $query)->where('jenis_ketidakhadiran', 'sakit')->count(),
                'total_izin' => (clone $query)->where('jenis_ketidakhadiran', 'izin')->count(),
                'total_dinas_luar' => (clone $query)->where('jenis_ketidakhadiran', 'dinas-luar')->count(),
            ];

            \Log::info("API Statistics result: " . json_encode($stats));

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Throwable $e) {
            \Log::error("Error apiStatistics: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function apiKehadiranPerDivisi(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', Carbon::now()->format('Y-m-d'));

            \Log::info("API KehadiranPerDivisi untuk tanggal: {$tanggal}");

            // Validasi tanggal
            if (!$this->isValidDate($tanggal)) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }

            // Ambil semua divisi yang ada
            $divisions = User::select('divisi')
                ->whereNotNull('divisi')
                ->where('role', 'karyawan')
                ->distinct()
                ->pluck('divisi');

            $result = [];

            foreach ($divisions as $division) {
                // Total karyawan di divisi ini
                $totalUsersInDivision = User::where('divisi', $division)
                    ->where('role', 'karyawan')
                    ->count();

                if ($totalUsersInDivision === 0) {
                    continue;
                }

                // Karyawan yang hadir di divisi ini
                $presentCount = Absensi::whereHas('user', function ($q) use ($division) {
                    $q->where('divisi', $division);
                })
                    ->whereDate('tanggal', $tanggal)
                    ->whereNotNull('jam_masuk')
                    ->count();

                $percentage = round(($presentCount / $totalUsersInDivision) * 100);

                $result[] = [
                    'divisi' => $division,
                    'total_karyawan' => $totalUsersInDivision,
                    'hadir' => $presentCount,
                    'tidak_hadir' => $totalUsersInDivision - $presentCount,
                    'persentase_hadir' => $percentage
                ];
            }

            \Log::info("API KehadiranPerDivisi: " . count($result) . " divisi ditemukan");

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            \Log::error("Error apiKehadiranPerDivisi: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kehadiran per divisi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyiapkan data absensi sebelum disimpan
     * 
     * @param array $data
     * @param Absensi|null $existingData
     * @return array
     */
    private function prepareAbsensiData(array $data, $existingData = null): array
    {
        // Tentukan approval_status berdasarkan jenis_ketidakhadiran
        if (isset($data['jenis_ketidakhadiran']) && in_array($data['jenis_ketidakhadiran'], ['cuti', 'sakit', 'izin'])) {
            // Untuk cuti, sakit, izin - default pending jika tidak ada nilai
            if (!isset($data['approval_status'])) {
                $data['approval_status'] = 'pending';
            }
        } else {
            // Untuk kehadiran atau dinas luar - default approved
            $data['approval_status'] = 'approved';
        }

        // Jika ada jam_masuk, pastikan tidak ada jenis_ketidakhadiran
        if (isset($data['jam_masuk']) && $data['jam_masuk']) {
            $data['jenis_ketidakhadiran'] = null;
        }

        // Set tanggal_akhir jika tidak diisi
        if (!isset($data['tanggal_akhir']) || !$data['tanggal_akhir']) {
            $data['tanggal_akhir'] = $data['tanggal'];
        }

        return $data;
    }

    /**
     * Helper untuk mendapatkan label jenis ketidakhadiran
     * 
     * @param string|null $jenis
     * @return string
     */
    private function getJenisKetidakhadiranLabel($jenis): string
    {
        return match ($jenis) {
            'cuti' => 'Cuti',
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'dinas-luar' => 'Dinas Luar',
            'lainnya' => 'Lainnya',
            default => 'Kehadiran',
        };
    }

    /**
     * Helper untuk mendapatkan label approval status
     * 
     * @param string $status
     * @return string
     */
    private function getApprovalStatusLabel($status): string
    {
        return match ($status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Method untuk membersihkan data dengan tanggal di masa depan yang salah
     * (Hanya untuk development/testing)
     */
    public function cleanupFutureData()
    {
        try {
            $maxAllowedDate = Carbon::now()->addMonth()->format('Y-m-d');
            $deletedCount = Absensi::whereDate('tanggal', '>', $maxAllowedDate)->delete();

            \Log::warning("Cleanup: {$deletedCount} data dengan tanggal > {$maxAllowedDate} dihapus");

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} data dengan tanggal di masa depan dihapus"
            ]);
        } catch (\Exception $e) {
            \Log::error("Error cleanupFutureData: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kelolaAbsensiManagerDivisi()
    {
        // Debug logging
        \Log::info("Manager Divisi kelolaAbsensi accessed. Date: " . Carbon::now()->format('Y-m-d'));

        // Mendapatkan divisi manager yang sedang login
        $divisiManager = Auth::user()->divisi;

        // Hitung statistik bulan ini dengan query yang lebih efisien
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        // Query untuk statistik kehadiran berdasarkan divisi manager
        $statsQuery = Absensi::select(
            DB::raw('COUNT(CASE WHEN jam_masuk IS NOT NULL AND approval_status = "approved" THEN 1 END) as total_tepat_waktu'),
            DB::raw('COUNT(CASE WHEN jam_masuk IS NOT NULL AND approval_status = "approved" AND TIME(jam_masuk) > "' . self::LIMIT_TIME . ':00" THEN 1 END) as total_terlambat'),
            DB::raw('COUNT(CASE WHEN jenis_ketidakhadiran = "izin" AND approval_status = "approved" THEN 1 END) as total_izin'),
            DB::raw('COUNT(CASE WHEN jenis_ketidakhadiran = "cuti" AND approval_status = "approved" THEN 1 END) as total_cuti'),
            DB::raw('COUNT(CASE WHEN jenis_ketidakhadiran = "dinas-luar" AND approval_status = "approved" THEN 1 END) as total_dinas_luar'),
            DB::raw('COUNT(CASE WHEN jenis_ketidakhadiran = "sakit" AND approval_status = "approved" THEN 1 END) as total_sakit'),
            DB::raw('COUNT(CASE WHEN jam_masuk IS NULL AND jenis_ketidakhadiran IS NULL AND approval_status = "approved" THEN 1 END) as total_tidak_masuk')
        )
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereHas('user', function ($query) use ($divisiManager) {
                $query->where('divisi', $divisiManager);
            })
            ->first();

        // Total karyawan di divisi manager
        $total_karyawan = User::where('role', 'karyawan')
            ->where('divisi', $divisiManager)
            ->count();

        $stats = [
            'total_tepat_waktu' => $statsQuery->total_tepat_waktu ?? 0,
            'total_terlambat' => $statsQuery->total_terlambat ?? 0,
            'total_izin' => $statsQuery->total_izin ?? 0,
            'total_cuti' => $statsQuery->total_cuti ?? 0,
            'total_dinas_luar' => $statsQuery->total_dinas_luar ?? 0,
            'total_sakit' => $statsQuery->total_sakit ?? 0,
            'total_tidak_masuk' => $statsQuery->total_tidak_masuk ?? 0,
            'total_karyawan' => $total_karyawan,
            'periode' => Carbon::now()->translatedFormat('F Y'),
        ];

        // Mengambil data kehadiran (ada jam_masuk) berdasarkan divisi manager
        $attendances = Absensi::with('user')
            ->whereNotNull('jam_masuk')
            ->whereHas('user', function ($query) use ($divisiManager) {
                $query->where('divisi', $divisiManager);
            })
            ->orderBy('tanggal', 'desc')
            ->limit(100)
            ->get();

        // Mengambil data ketidakhadiran (ada jenis_ketidakhadiran) berdasarkan divisi manager
        $ketidakhadiran = Absensi::with('user')
            ->whereNotNull('jenis_ketidakhadiran')
            ->whereHas('user', function ($query) use ($divisiManager) {
                $query->where('divisi', $divisiManager);
            })
            ->orderBy('tanggal', 'desc')
            ->limit(100)
            ->get();

        // Mengambil semua data absensi untuk pagination
        $allAbsensis = Absensi::with('user')
            ->whereHas('user', function ($query) use ($divisiManager) {
                $query->where('divisi', $divisiManager);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('manager_divisi.kelola_absensi', compact('stats', 'attendances', 'ketidakhadiran', 'allAbsensis'));
    }
}
