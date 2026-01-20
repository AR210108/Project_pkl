<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman utama absensi dengan statistik dan data
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Tentukan tanggal yang ingin ditampilkan (hari ini)
        $today = Carbon::now()->format('Y-m-d');

        // 2. Identifikasi karyawan yang tidak hadir dan tambahkan ke database
        $this->markAbsentEmployees($today);

        // Mengambil statistik dari API
        $statsResponse = $this->apiStatistics();
        $stats = $statsResponse->getData(true)['data'];

        // Mengambil data kehadiran (tanpa status Tidak Masuk)
        $attendances = Absensi::with('user')
            ->where('status', '!=', 'Tidak Masuk')
            ->whereNotIn('status', ['Cuti', 'Sakit', 'Izin'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Mengambil data ketidakhadiran (termasuk Tidak Masuk)
        $ketidakhadiran = Absensi::with('user')
            ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Tidak Masuk'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // PERUBAHAN: Hanya mengambil user dengan role 'karyawan' untuk dropdown
        $users = User::where('role', 'karyawan')->get();

        return view('admin.absensi', compact('stats', 'attendances', 'ketidakhadiran', 'users'));
    }

    /**
     * Menampilkan halaman kelola absensi
     * 
     * @return \Illuminate\View\View
     */
    public function kelolaAbsen()
    {
        // Mengambil statistik dari API
        $statsResponse = $this->apiStatistics();
        $stats = $statsResponse->getData(true)['data'];

        // Mengambil semua user dengan role 'karyawan' untuk dropdown
        $users = User::where('role', 'karyawan')->get();

        return view('general_manajer.kelola_absen', compact('stats', 'users'));
    }

// Di app/Http/Controllers/AbsensiController.php

public function rekapAbsensi(Request $request)
{
    $today = Carbon::now()->format('Y-m-d');
    $this->markAbsentEmployees($today);
    
    // Mengambil statistik dari API
    $statsResponse = $this->apiStatistics($request);
    $stats = $statsResponse->getData(true)['data'];
    
    // Ambil nilai filter dari request, dengan default ke HARI INI
    $tanggalMulai = $request->get('tanggal_mulai', $today); 
    $tanggalAkhir = $request->get('tanggal_akhir', $today);
    $divisiFilter = $request->get('divisi');
    
    // ... kode query untuk $attendances dan $ketidakhadiran tetap sama ...
    
    $attendancesQuery = Absensi::with('user')
        ->where('status', '!=', 'Tidak Masuk')
        ->whereNotIn('status', ['Cuti', 'Sakit', 'Izin'])
        ->whereDate('tanggal', '>=', $tanggalMulai)
        ->whereDate('tanggal', '<=', $tanggalAkhir);

    if ($divisiFilter) {
        $attendancesQuery->whereHas('user', function($query) use ($divisiFilter) {
            $query->where('divisi', $divisiFilter);
        });
    }
    
    $attendances = $attendancesQuery->orderBy('tanggal', 'desc')->get();
        
    $ketidakhadiranQuery = Absensi::with('user')
        ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Tidak Masuk'])
        ->whereDate('tanggal', '>=', $tanggalMulai)
        ->whereDate('tanggal', '<=', $tanggalAkhir);

    if ($divisiFilter) {
        $ketidakhadiranQuery->whereHas('user', function($query) use ($divisiFilter) {
            $query->where('divisi', $divisiFilter);
        });
    }

    $ketidakhadiran = $ketidakhadiranQuery->orderBy('tanggal', 'desc')->get();
        
    $users = User::where('role', 'karyawan')->get();
    $divisions = User::whereNotNull('divisi')->distinct()->pluck('divisi');
    
    // =======================================================
    // TAMBAHKAN PERHITUNGAN PERSENTEKE KEHADIRAN DI SINI
    // =======================================================
    
    // Menghitung total karyawan yang hadir (Tepat Waktu + Terlambat)
    $totalHadir = $stats['total_tepat_waktu'] + $stats['total_terlambat'];
    
    // Menghitung total semua karyawan yang memiliki catatan absensi
    $totalKaryawan = $totalHadir 
                    + $stats['total_tidak_masuk'] 
                    + $stats['total_izin'] 
                    + $stats['total_cuti'] 
                    + $stats['total_sakit'] 
                    + $stats['total_dinas_luar'];
    
    // Menghitung persentase kehadiran, dengan pemeriksaan untuk menghindari pembagian dengan nol
    $persentaseKehadiran = 0;
    if ($totalKaryawan > 0) {
        $persentaseKehadiran = round(($totalHadir / $totalKaryawan) * 100);
    }
    
    // =======================================================
    // AKHIR DARI PERUBAHAN
    // =======================================================
    
    return view('pemilik.rekap_absensi', compact(
        'stats', 
        'attendances', 
        'ketidakhadiran', 
        'users', 
        'divisions',
        'tanggalMulai',
        'tanggalAkhir',
        'divisiFilter',
        'persentaseKehadiran' // <-- TAMBAHKAN VARIABEL INI KE COMPACT
    ));
}

// Fungsi markAbsentEmployees tidak perlu diubah, biarkan seperti ini
private function markAbsentEmployees($date)
{
    // 1. Ambil SEMUA karyawan (PERBAIKAN: Hanya yang role 'karyawan')
    $allEmployees = User::where('role', 'karyawan')->get();
    
    // 2. Ambil semua data absensi untuk tanggal yang ditentukan
    $dateAttendances = Absensi::whereDate('tanggal', $date)->get();
    
    // 3. Dapatkan ID karyawan yang sudah absen pada tanggal tersebut
    $checkedInEmployeeIds = $dateAttendances->pluck('user_id')->toArray();
    
    // 4. Cari karyawan yang belum absen
    $absentEmployees = $allEmployees->whereNotIn('id', $checkedInEmployeeIds);
    
    // 5. Untuk setiap karyawan yang tidak absen, buat record "Tidak Hadir"
    foreach ($absentEmployees as $employee) {
        // Opsional: Cek dulu agar tidak duplikat jika script dijalankan berkali-kali
        $alreadyMarkedAbsent = Absensi::where('user_id', $employee->id)
                                ->whereDate('tanggal', $date)
                                ->where('status', 'Tidak Masuk')
                                ->exists();
        
        if (!$alreadyMarkedAbsent) {
            Absensi::create([
                'user_id' => $employee->id,
                'name' => $employee->name,
                'tanggal' => $date,
                'status' => 'Tidak Masuk',
                'status_type' => 'no-show',
                'jam_masuk' => null,
                'jam_pulang' => null,
                'approval_status' => 'approved', // Otomatis disetujui untuk ketidakhadiran
                'approved_by' => auth()->check() ? auth()->user()->id : null,
                'approved_at' => now(),
            ]);
        }
    }
}

    /**
     * API untuk menampilkan data absensi dengan filter
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex(Request $request)
    {
        // Pastikan karyawan yang tidak hadir ditandai
        $date = $request->tanggal ?? Carbon::now()->format('Y-m-d');

        $query = Absensi::with('user')
            ->where('status', '!=', 'Tidak Masuk')
            ->whereNotIn('status', ['Cuti', 'Sakit', 'Izin']);

        // Filter berdasarkan pencarian (nama user atau tanggal)
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('tanggal', 'like', "%{$search}%");
        }

        // Filter berdasarkan tanggal
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter berdasarkan status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $absensis = $query->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $absensis
        ]);
    }

    /**
     * API untuk menampilkan data ketidakhadiran (cuti, sakit, izin, tidak masuk)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndexKetidakhadiran(Request $request)
    {
        // Pastikan karyawan yang tidak hadir ditandai
        $date = $request->tanggal ?? Carbon::now()->format('Y-m-d');

        $query = Absensi::with('user')
            ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Tidak Masuk']);

        // Filter berdasarkan pencarian (nama user, tanggal, atau tanggal akhir)
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
                ->orWhere('tanggal', 'like', "%{$search}%")
                ->orWhere('tanggal_akhir', 'like', "%{$search}%");
        }

        // Filter berdasarkan status persetujuan
        if ($request->approval_status) {
            $query->where('approval_status', $request->approval_status);
        }

        $ketidakhadiran = $query->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $ketidakhadiran
        ]);
    }

    /**
     * API untuk menyimpan data absensi baru (termasuk sakit dan izin)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'is_early_checkout' => 'nullable|boolean',
            'early_checkout_reason' => 'nullable|string',
            'status' => 'required|in:Tepat Waktu,Terlambat,Tidak Masuk,Sakit,Izin,Dinas Luar',
            'status_type' => 'required|in:on-time,late,no-show,absent',
            'late_minutes' => 'nullable|integer|min:0',
            'reason' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:255',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal', // Untuk sakit/izin multi-hari
        ]);

        try {
            // PERBAIKAN: Ambil nama user untuk ditambahkan ke data
            $user = User::find($validated['user_id']);
            $validated['name'] = $user->name;

            $data = $this->prepareAbsensiData($validated);
            $absensi = Absensi::create($data);

            $message = 'Data absensi berhasil ditambahkan';
            if (in_array($data['status'], ['Sakit', 'Izin'])) {
                $message = 'Pengajuan ' . strtolower($data['status']) . ' berhasil dibuat dan menunggu persetujuan admin';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $absensi->load('user')
            ], 201);
        } catch (\Exception $e) {
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
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'jenis_cuti' => 'required|string|max:255',
            'alasan_cuti' => 'required|string',
        ]);

        try {
            // PERBAIKAN: Ambil nama user untuk ditambahkan ke data
            $user = User::find($validated['user_id']);
            $validated['name'] = $user->name;

            $data = $this->prepareCutiData($validated);
            $cuti = Absensi::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil dibuat dan menunggu persetujuan admin',
                'data' => $cuti->load('user')
            ], 201);
        } catch (\Exception $e) {
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
            $absensi = Absensi::with('user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $absensi
            ]);
        } catch (\Exception $e) {
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
        $absensi = Absensi::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'is_early_checkout' => 'nullable|boolean',
            'early_checkout_reason' => 'nullable|string',
            'status' => 'required|in:Tepat Waktu,Terlambat,Tidak Masuk,Sakit,Izin,Dinas Luar',
            'status_type' => 'required|in:on-time,late,no-show,absent',
            'late_minutes' => 'nullable|integer|min:0',
            'reason' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:255',
        ]);

        try {
            // PERBAIKAN: Ambil nama user untuk ditambahkan ke data
            $user = User::find($validated['user_id']);
            $validated['name'] = $user->name;

            $data = $this->prepareAbsensiData($validated);
            $absensi->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil diperbarui',
                'data' => $absensi->load('user')
            ]);
        } catch (\Exception $e) {
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
        $cuti = Absensi::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'jenis_cuti' => 'required|string|max:255',
            'alasan_cuti' => 'required|string',
            'approval_status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'required_if:approval_status,rejected|string|max:255',
        ]);

        try {
            // PERBAIKAN: Ambil nama user untuk ditambahkan ke data
            $user = User::find($validated['user_id']);
            $validated['name'] = $user->name;

            $data = $this->prepareCutiData($validated);

            // Jika status persetujuan berubah, simpan informasi perubahan
            if (isset($validated['approval_status'])) {
                $data['approval_status'] = $validated['approval_status'];
                $data['rejection_reason'] = $validated['approval_status'] === 'rejected'
                    ? $validated['rejection_reason']
                    : null;

                // Jika status berubah dari pending ke approved/rejected, simpan informasi approver
                if ($cuti->approval_status === 'pending' && $validated['approval_status'] !== 'pending') {
                    $data['approved_by'] = Auth::user()->id;
                    $data['approved_at'] = now();
                }
            }

            $cuti->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data cuti berhasil diperbarui',
                'data' => $cuti->load('user')
            ]);
        } catch (\Exception $e) {
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
        DB::beginTransaction();
        try {
            $absensi = Absensi::findOrFail($id);
            $recordType = $absensi->status;
            $absensi->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Data dengan status '{$recordType}' berhasil dihapus."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data'
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
        // PERBAIKAN: Gunakan format aturan validasi yang benar
        $validated = $request->validate([
            'approval_status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:approval_status,rejected|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $absensi = Absensi::findOrFail($id);

            // Pastikan hanya bisa verifikasi yang statusnya pending
            if ($absensi->approval_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya dapat memverifikasi pengajuan dengan status pending.'
                ], 400);
            }

            $absensi->update([
                'approval_status' => $validated['approval_status'],
                'rejection_reason' => $validated['approval_status'] === 'rejected'
                    ? $validated['rejection_reason']
                    : null,
                'approved_by' => Auth::user()->id,
                'approved_at' => now(),
            ]);

            DB::commit();

            $statusText = $validated['approval_status'] === 'approved' ? 'disetujui' : 'ditolak';
            $message = "Pengajuan {$absensi->status} berhasil {$statusText}.";

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $absensi->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi data. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan statistik absensi
     * 
     * @return \Illuminate\Http\JsonResponse
     */
// Di app/Http/Controllers/AbsensiController.php

// Di app/Http/Controllers/AbsensiController.php

public function apiStatistics(Request $request)
{
    try {
        $today = Carbon::now()->format('Y-m-d');
        
        // --- PERUBAHAN DI SINI ---
        // Default ke HARI INI jika tidak ada filter
        $startDate = $request->get('tanggal_mulai') ?? $today;
        $endDate = $request->get('tanggal_akhir') ?? $today;
        $divisiFilter = $request->get('divisi');
        
        $query = Absensi::whereDate('tanggal', '>=', $startDate)
                       ->whereDate('tanggal', '<=', $endDate);

        if ($divisiFilter) {
            $query->whereHas('user', function($q) use ($divisiFilter) {
                $q->where('divisi', $divisiFilter);
            });
        }
        
        // ... kode statistik tetap sama ...
        $stats = [
            'total_tepat_waktu' => (clone $query)->where('status', 'Tepat Waktu')->count(),
            'total_terlambat' => (clone $query)->where('status', 'Terlambat')->count(),
            'total_tidak_masuk' => (clone $query)->where('status', 'Tidak Masuk')->count(),
            'total_cuti' => (clone $query)->where('status', 'Cuti')->count(),
            'total_sakit' => (clone $query)->where('status', 'Sakit')->count(),
            'total_izin' => (clone $query)->where('status', 'Izin')->count(),
            'total_dinas_luar' => (clone $query)->where('status', 'Dinas Luar')->count(),
            // ... dst
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat statistik: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Menyiapkan data absensi sebelum disimpan
     * 
     * @param array $data
     * @return array
     */
    private function prepareAbsensiData(array $data): array
    {
        // Jika status adalah Sakit atau Izin, atur tipe dan status persetujuan
        if (in_array($data['status'], ['Sakit', 'Izin'])) {
            $data['status_type'] = 'absent';
            $data['approval_status'] = 'pending'; // Menunggu verifikasi admin
        } else {
            // Reset field yang tidak relevan untuk absensi biasa
            $data['tanggal_akhir'] = null;
            $data['jenis_cuti'] = null;
            $data['alasan_cuti'] = null;
            $data['approval_status'] = 'approved'; // Otomatis disetujui untuk kehadiran biasa
        }

        $data['rejection_reason'] = null;

        return $data;
    }

    /**
     * Menyiapkan data cuti sebelum disimpan
     * 
     * @param array $data
     * @return array
     */
    private function prepareCutiData(array $data): array
    {
        // Set field khusus untuk data cuti
        $data['status'] = 'Cuti';
        $data['status_type'] = 'absent';
        $data['approval_status'] = 'pending'; // Menunggu verifikasi admin
        $data['jam_masuk'] = null;
        $data['jam_pulang'] = null;
        $data['is_early_checkout'] = false;
        $data['late_minutes'] = 0;
        $data['reason'] = null;
        $data['location'] = null;
        $data['purpose'] = null;
        $data['rejection_reason'] = null;

        return $data;
    }
    public function apiKehadiranPerDivisi(Request $request)
{
    try {
        $tanggal = $request->get('tanggal', Carbon::now()->format('Y-m-d'));
        
        // Ambil semua divisi unik dari karyawan
        $divisions = User::where('role', 'karyawan')
                         ->whereNotNull('divisi')
                         ->distinct()
                         ->pluck('divisi');

        $result = [];
        $totalAllEmployees = 0;
        $totalAllPresent = 0;

        foreach ($divisions as $division) {
            $employeesInDivision = User::where('role', 'karyawan')->where('divisi', $division)->get();
            $totalUsersInDivision = $employeesInDivision->count();
            
            if ($totalUsersInDivision === 0) {
                continue; // Lewati jika tidak ada karyawan di divisi ini
            }

            $presentCount = 0;
            foreach ($employeesInDivision as $employee) {
                $absensi = Absensi::where('user_id', $employee->id)
                                  ->whereDate('tanggal', $tanggal)
                                  ->first();
                
                if ($absensi && in_array($absensi->status, ['Tepat Waktu', 'Terlambat'])) {
                    $presentCount++;
                }
            }

            $percentage = round(($presentCount / $totalUsersInDivision) * 100);
            
            $result[] = [
                'division' => ucfirst($division),
                'present' => $presentCount,
                'total' => $totalUsersInDivision,
                'percentage' => $percentage,
            ];

            // Akumulasikan untuk total keseluruhan
            $totalAllEmployees += $totalUsersInDivision;
            $totalAllPresent += $presentCount;
        }

        // Hitung persentase keseluruhan
        $overallPercentage = $totalAllEmployees > 0 ? round(($totalAllPresent / $totalAllEmployees) * 100) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'overall_percentage' => $overallPercentage,
                'divisions' => $result
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data: ' . $e->getMessage()
        ], 500);
    }
}
} 
/**
 * API untuk mendapatkan persentase kehadiran per divisi
 * 
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
