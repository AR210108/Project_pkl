<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Carbon\Carbon;

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
        $today = Carbon::now()->format('Y-m-d');
        
        \Log::info("Admin index accessed. Today date: {$today}");
        
        if ($this->isValidDate($today)) {
            $this->markAbsentEmployees($today);
        } else {
            \Log::warning("Tanggal tidak valid untuk markAbsentEmployees: {$today}");
        }
        
        $statsResponse = $this->apiStatistics();
        $stats = $statsResponse->getData(true)['data'];
        
        $attendances = Absensi::with('user')
            ->whereNotNull('jam_masuk')
            ->orderBy('tanggal', 'desc')
            ->limit(100)
            ->get();
            
        $ketidakhadiran = Absensi::with('user')
            ->whereNotNull('jenis_ketidakhadiran')
            ->orderBy('tanggal', 'desc')
            ->limit(100)
            ->get();
            
        $users = User::where('role', 'karyawan')->get();

        return view('admin.absensi', compact('stats', 'attendances', 'ketidakhadiran', 'users'));
    }

/**
 * Menampilkan halaman kelola absensi untuk General Manajer (Versi Sederhana)
 * 
 * @return \Illuminate\View\View
 */


    /**
     * Method untuk General Manager
     */
public function kelolaAbsenManajer()
{
    try {
        // 1. AMBIL FILTER DARI URL
        $startDate = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $selectedDivision = request('division');
        $statusFilter = request('status');

        // 2. INISIALISASI SEMUA VARIABLE YANG AKAN DIKIRIM KE VIEW
        $stats = [];
        $formattedAbsensi = collect();
        $divisions = collect();
        $absensiPaginator = null;

        // 3. QUERY UNTUK DATA ABSENSI (DENGAN PAGINATION)
        $query = Absensi::with(['user:id,name,divisi', 'approver:id,name'])
            ->join('users', 'absensis.user_id', '=', 'users.id')
            ->whereBetween('absensis.tanggal', [$startDate, $endDate])
            ->select('absensis.*', 'users.name as user_name', 'users.divisi as user_divisi')
            ->orderBy('absensis.tanggal', 'desc')
            ->orderBy('absensis.created_at', 'desc');

        // Filter divisi jika dipilih
        if ($selectedDivision && $selectedDivision !== 'semua') {
            $query->where('users.divisi', $selectedDivision);
        }

        // Filter status jika dipilih
        if ($statusFilter && $statusFilter !== 'semua') {
            if ($statusFilter === 'hadir') {
                $query->whereNotNull('absensis.jam_masuk')
                      ->whereNull('absensis.jenis_ketidakhadiran');
            } elseif ($statusFilter === 'izin') {
                $query->where('absensis.jenis_ketidakhadiran', 'izin');
            } elseif ($statusFilter === 'sakit') {
                $query->where('absensis.jenis_ketidakhadiran', 'sakit');
            } elseif ($statusFilter === 'tidak-hadir') {
                $query->whereNull('absensis.jam_masuk')
                      ->whereNull('absensis.jenis_ketidakhadiran');
            } elseif ($statusFilter === 'pending') {
                $query->where('absensis.approval_status', 'pending');
            } elseif ($statusFilter === 'approved') {
                $query->where('absensis.approval_status', 'approved');
            } elseif ($statusFilter === 'rejected') {
                $query->where('absensis.approval_status', 'rejected');
            }
        }

        // Ambil data dengan pagination
        $absensiPaginator = $query->paginate(15);
        $absensiPaginator->appends([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'division' => $selectedDivision,
            'status' => $statusFilter
        ]);

        // 4. HITUNG STATISTIK (TANPA RAW QUERY YANG KOMPLEKS)
        $statsQuery = Absensi::join('users', 'absensis.user_id', '=', 'users.id')
            ->whereBetween('absensis.tanggal', [$startDate, $endDate]);

        if ($selectedDivision && $selectedDivision !== 'semua') {
            $statsQuery->where('users.divisi', $selectedDivision);
        }

        // Inisialisasi semua statistik dengan nilai default 0
        $stats = [
            'total_tepat_waktu' => 0,
            'total_terlambat' => 0,
            'total_izin' => 0,
            'total_sakit' => 0,
            'total_tidak_masuk' => 0,
            'total_semua' => $absensiPaginator->total(),
            'periode' => Carbon::parse($startDate)->translatedFormat('d M Y') . 
                        ' - ' . 
                        Carbon::parse($endDate)->translatedFormat('d M Y'),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        // Hitung statistik berdasarkan data yang sudah diambil
        foreach ($absensiPaginator as $absen) {
            // Hitung berdasarkan jenis ketidakhadiran
            if ($absen->jenis_ketidakhadiran == 'izin') {
                $stats['total_izin']++;
            } elseif ($absen->jenis_ketidakhadiran == 'sakit') {
                $stats['total_sakit']++;
            }
            
            // Hitung tepat waktu vs terlambat (hanya untuk yang hadir)
            if ($absen->jam_masuk && !$absen->jenis_ketidakhadiran) {
                $jamMasuk = strtotime($absen->jam_masuk);
                $batasTerlambat = strtotime('09:00:00');
                
                if ($jamMasuk <= $batasTerlambat) {
                    $stats['total_tepat_waktu']++;
                } else {
                    $stats['total_terlambat']++;
                }
            }
            
            // Hitung tidak masuk (tanpa jam masuk dan tanpa keterangan)
            if (!$absen->jam_masuk && !$absen->jenis_ketidakhadiran) {
                $stats['total_tidak_masuk']++;
            }
        }

        // 5. DATA DIVISI UNTUK DROPDOWN
        $divisions = User::select('divisi')
            ->whereNotNull('divisi')
            ->where('divisi', '!=', '')
            ->distinct()
            ->orderBy('divisi')
            ->pluck('divisi');

        // 6. FORMAT DATA ABSENSI dengan tambahan 'type' dan 'alasan'
        $formattedAbsensi = $absensiPaginator->map(function($absen) {
            $status = $this->getStatusKehadiran($absen);
            
            // Tentukan apakah ini data absensi atau ketidakhadiran
            $type = 'absensi';
            $alasan = null;
            
            if (in_array($absen->jenis_ketidakhadiran, ['izin', 'sakit'])) {
                $type = 'ketidakhadiran';
                $alasan = $absen->keterangan ?? $absen->reason;
            }
            
            return [
                'id' => $absen->id,
                'user_name' => $absen->user->name ?? $absen->user_name,
                'divisi' => $absen->user->divisi ?? $absen->user_divisi,
                'tanggal' => $absen->tanggal,
                'jam_masuk' => $absen->jam_masuk ? substr($absen->jam_masuk, 0, 5) : '-',
                'jam_pulang' => $absen->jam_pulang ? substr($absen->jam_pulang, 0, 5) : '-',
                'jenis_ketidakhadiran' => $absen->jenis_ketidakhadiran,
                'keterangan' => $absen->keterangan ?? $absen->reason,
                'alasan' => $alasan,
                'type' => $type,
                'approval_status' => $absen->approval_status,
                'rejection_reason' => $absen->rejection_reason,
                'approved_by_name' => $absen->approver->name ?? null,
                'status_kehadiran' => $status['label'],
                'status_class' => $status['class'],
                'tanggal_akhir' => $absen->tanggal_akhir,
                'created_at' => $absen->created_at,
            ];
        });

        // 7. HITUNG JUMLAH DATA KETIDAKHADIRAN UNTUK TAB
        $absenceCount = $formattedAbsensi->where('type', 'ketidakhadiran')->count();

    } catch (\Exception $e) {
        \Log::error('Error in kelolaAbsenManajer: ' . $e->getMessage());
        
        // Set default values jika terjadi error
        $stats = [
            'total_tepat_waktu' => 0,
            'total_terlambat' => 0,
            'total_izin' => 0,
            'total_sakit' => 0,
            'total_tidak_masuk' => 0,
            'total_semua' => 0,
            'periode' => Carbon::now()->translatedFormat('F Y'),
            'start_date' => $startDate ?? Carbon::now()->startOfMonth()->format('Y-m-d'),
            'end_date' => $endDate ?? Carbon::now()->endOfMonth()->format('Y-m-d'),
        ];
        
        $formattedAbsensi = collect();
        $divisions = collect();
        $selectedDivision = $selectedDivision ?? null;
        $statusFilter = $statusFilter ?? null;
        $absensiPaginator = null;
        $absenceCount = 0;
    }

    // 8. KIRIM KE VIEW (PASTIKAN SEMUA VARIABLE TERDEFINISI)
    return view('general_manajer.kelola_absen', compact(
        'stats',
        'formattedAbsensi',
        'absensiPaginator',
        'divisions',
        'selectedDivision',
        'statusFilter',
        'startDate',
        'endDate',
        'absenceCount'
    ));
}

/**
 * Helper function untuk menentukan status kehadiran
 */
private function getStatusKehadiran($absen)
{
    if ($absen->jenis_ketidakhadiran) {
        switch ($absen->jenis_ketidakhadiran) {
            case 'izin':
                return ['label' => 'Izin', 'class' => 'status-izin'];
            case 'sakit':
                return ['label' => 'Sakit', 'class' => 'status-sakit'];
            default:
                return ['label' => 'Lainnya', 'class' => 'bg-gray-100 text-gray-800'];
        }
    }
    
    if ($absen->jam_masuk) {
        $jamMasuk = strtotime($absen->jam_masuk);
        $batasTerlambat = strtotime('09:00:00');
        
        if ($jamMasuk <= $batasTerlambat) {
            return ['label' => 'Tepat Waktu', 'class' => 'status-hadir'];
        } else {
            return ['label' => 'Terlambat', 'class' => 'status-terlambat'];
        }
    }
    
    return ['label' => 'Tidak Hadir', 'class' => 'status-tidak-hadir'];
}

    public function approve($id)
    {
        try {
            DB::table('attendances')
                ->where('id', $id)
                ->update([
                    'approval_status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => Carbon::now()
                ]);
            
            return back()->with('success', 'Permohonan berhasil di-approve.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal approve: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            DB::table('attendances')
                ->where('id', $id)
                ->update([
                    'approval_status' => 'rejected',
                    'rejection_reason' => $request->rejection_reason
                ]);
            
            return back()->with('success', 'Permohonan berhasil di-reject.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal reject: ' . $e->getMessage());
        }
    }


   /**
 * Menampilkan halaman rekap absensi untuk General Manajer
 * 
 * @return \Illuminate\View\View
 */
    private function markAbsentEmployees($date)
    {
        if (!$this->isValidDate($date)) {
            \Log::error("markAbsentEmployees: Tanggal tidak valid - {$date}");
            return;
        }

        \Log::info("markAbsentEmployees dimulai untuk tanggal: {$date}");
        
        $allEmployees = User::where('role', 'karyawan')->get();
        \Log::info("Total karyawan ditemukan: " . $allEmployees->count());
        
        $dateAttendances = Absensi::whereDate('tanggal', $date)->get();
        \Log::info("Absensi pada tanggal {$date}: " . $dateAttendances->count());
        
        $checkedInEmployeeIds = $dateAttendances->pluck('user_id')->toArray();
        
        $absentEmployees = $allEmployees->whereNotIn('id', $checkedInEmployeeIds);
        \Log::info("Karyawan belum absen: " . $absentEmployees->count());
        
        $createdCount = 0;
        foreach ($absentEmployees as $employee) {
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
    
    private function isValidDate($date)
    {
        try {
            $dateObj = Carbon::parse($date);
            $now = Carbon::now();
            
            $maxAllowedDate = $now->copy()->addMonth();

            if ($dateObj->gt($maxAllowedDate)) {
                \Log::warning("Tanggal {$date} lebih dari 1 bulan di masa depan");
                return false;
            }
            
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
     |  API KARYAWAN - DENGAN VALIDASI CUTI
     ===================================================== */

    public function apiCheckLeaveStatus()
    {
        try {
            $user = Auth::user();
            $today = Carbon::now()->format('Y-m-d');
            
            \Log::info("API CheckLeaveStatus diakses oleh user {$user->id} untuk tanggal: {$today}");
            
            $onLeave = Cuti::where('user_id', $user->id)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->first();
            
            if ($onLeave) {
                $response = [
                    'success' => true,
                    'data' => [
                        'is_on_leave' => true,
                        'leave_type' => $onLeave->jenis_cuti,
                        'leave_type_label' => $this->getCutiTypeLabel($onLeave->jenis_cuti),
                        'dates' => [
                            'start' => $onLeave->tanggal_mulai->format('Y-m-d'),
                            'end' => $onLeave->tanggal_selesai->format('Y-m-d')
                        ],
                        'reason' => $onLeave->keterangan,
                        'duration_days' => $onLeave->durasi
                    ]
                ];
                \Log::info("User {$user->id} sedang cuti: {$onLeave->jenis_cuti} dari {$onLeave->tanggal_mulai} sampai {$onLeave->tanggal_selesai}");
            } else {
                $response = [
                    'success' => true,
                    'data' => [
                        'is_on_leave' => false
                    ]
                ];
                \Log::info("User {$user->id} tidak sedang cuti");
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error("Error apiCheckLeaveStatus: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status cuti: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiTodayStatus()
    {
        try {
            $user = Auth::user();
            $today = Carbon::now()->format('Y-m-d');
            
            \Log::info("API TodayStatus diakses oleh user {$user->id} untuk tanggal: {$today}");
            
            $leaveStatus = $this->checkLeaveStatus($user->id, $today);
            
            $attendance = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();
            
            $lateMinutes = 0;
            $isTerlambat = false;

            if ($attendance && $attendance->jam_masuk) {
                $jamMasuk = Carbon::parse($attendance->jam_masuk);
                $jamBatas = Carbon::parse(self::LIMIT_TIME);
                
                if ($jamMasuk->gt($jamBatas)) {
                    $isTerlambat = true;
                    $lateMinutes = $jamMasuk->diffInMinutes($jamBatas);
                    \Log::info("Karyawan {$user->id} terlambat {$lateMinutes} menit dari batas " . self::LIMIT_TIME);
                }
            }
            
            $responseData = $attendance ? [
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
            ] : null;
            
            if ($leaveStatus['is_on_leave']) {
                if ($responseData === null) {
                    $responseData = [
                        'is_on_leave' => true,
                        'leave_type' => $leaveStatus['leave_type'],
                        'leave_type_label' => $leaveStatus['leave_type_label'],
                        'leave_dates' => $leaveStatus['dates'],
                        'leave_reason' => $leaveStatus['reason']
                    ];
                } else {
                    $responseData['is_on_leave'] = true;
                    $responseData['leave_type'] = $leaveStatus['leave_type'];
                    $responseData['leave_type_label'] = $leaveStatus['leave_type_label'];
                    $responseData['leave_dates'] = $leaveStatus['dates'];
                    $responseData['leave_reason'] = $leaveStatus['reason'];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $responseData
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error apiTodayStatus: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status hari ini: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiHistory(Request $request)
    {
        try {
            $user = Auth::user();
            $filter = $request->get('filter', 'month');

            \Log::info("API History diakses oleh user {$user->id} dengan filter: {$filter}");
            
            $query = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', '<=', Carbon::now()->addMonth())
                ->orderBy('tanggal', 'desc');
            
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
                $lateMinutes = 0;
                $isTerlambat = false;

                if ($item->jam_masuk) {
                    $jamMasuk = Carbon::parse($item->jam_masuk);
                    $jamBatas = Carbon::parse(self::LIMIT_TIME);
                    
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
                    'jenis_ketidakhadiran_label' => $item->jenis_ketidakhadiran ? $this->getJenisKetidakhadiranLabel($item->jenis_ketidakhadiran) : null,
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

    public function apiAbsenMasuk(Request $request)
    {
        try {
            $user = Auth::user();
            $today = Carbon::now()->format('Y-m-d');

            \Log::info("API AbsenMasuk diakses oleh user {$user->id} untuk tanggal: {$today}");
            
            $leaveStatus = $this->checkLeaveStatus($user->id, $today);
            if ($leaveStatus['is_on_leave']) {
                \Log::warning("User {$user->id} mencoba absen masuk saat cuti: {$leaveStatus['leave_type_label']}");
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sedang dalam status cuti (' . $leaveStatus['leave_type_label'] . '). Tidak dapat melakukan absensi selama periode cuti.'
                ], 403);
            }
            
            $existing = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            if ($existing) {
                if ($existing->jam_masuk) {
                    \Log::warning("User {$user->id} sudah absen masuk hari ini");
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah melakukan absen masuk hari ini'
                    ], 400);
                }
                
                if ($existing->jenis_ketidakhadiran) {
                    \Log::warning("User {$user->id} sudah mengajukan {$existing->jenis_ketidakhadiran}");
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah mengajukan ' . $this->getJenisKetidakhadiranLabel($existing->jenis_ketidakhadiran) . ' hari ini'
                    ], 400);
                }
            }
            
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
                    'message' => 'Anda memiliki pengajuan ' . $this->getJenisKetidakhadiranLabel($approvedAbsence->jenis_ketidakhadiran) . ' yang disetujui untuk hari ini'
                ], 400);
            }
            
            $currentTime = Carbon::now();
            $jamMasuk = $currentTime->format('H:i:s');
            $jamBatas = Carbon::parse(self::LIMIT_TIME);
            
            $lateMinutes = 0;
            $keterangan = null;

            if ($currentTime->gt($jamBatas)) {
                $lateMinutes = $currentTime->diffInMinutes($jamBatas);
                $keterangan = 'Terlambat ' . $lateMinutes . ' menit';
                \Log::info("User {$user->id} terlambat {$lateMinutes} menit dari batas " . self::LIMIT_TIME);
            }
            
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

    public function apiAbsenPulang(Request $request)
    {
        try {
            $user = Auth::user();
            $today = Carbon::now()->format('Y-m-d');

            \Log::info("API AbsenPulang diakses oleh user {$user->id} untuk tanggal: {$today}");
            
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
            
            if ($attendance->jenis_ketidakhadiran) {
                \Log::warning("User {$user->id} memiliki pengajuan {$attendance->jenis_ketidakhadiran}");
                return response()->json([
                    'success' => false,
                    'message' => 'Anda memiliki pengajuan ' . $this->getJenisKetidakhadiranLabel($attendance->jenis_ketidakhadiran) . ' hari ini'
                ], 400);
            }
            
            $currentTime = Carbon::now();
            $jamPulang = $currentTime->format('H:i:s');

            $attendance->jam_pulang = $jamPulang;
            
            if ($currentTime->hour < 17) {
                $attendance->is_early_checkout = true;
                if ($request->has('early_checkout_reason')) {
                    $attendance->early_checkout_reason = $request->early_checkout_reason;
                } else {
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

    public function apiSubmitIzin(Request $request)
    {
        try {
            $user = Auth::user();

            \Log::info("API SubmitIzin diakses oleh user {$user->id}");
            
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
            
            if (!$this->isValidDate($startDate) || !$this->isValidDate($endDate)) {
                \Log::warning("User {$user->id} mencoba submit dengan tanggal tidak valid: {$startDate} - {$endDate}");
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal tidak valid. Maksimal 1 bulan di masa depan.'
                ], 400);
            }
            
            $leaveCheck = $this->checkLeaveInRange($user->id, $startDate, $endDate);
            if ($leaveCheck['has_leave']) {
                \Log::warning("User {$user->id} mencoba submit {$jenis} saat ada cuti pada: " . $leaveCheck['conflict_dates']);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda memiliki cuti yang disetujui pada tanggal ' . $leaveCheck['conflict_dates'] . '. Tidak dapat mengajukan ' . $jenis . '.'
                ], 400);
            }
            
            $today = Carbon::now()->format('Y-m-d');
            if ($startDate < $today && $jenis === 'sakit') {
                \Log::warning("User {$user->id} mencoba submit sakit untuk tanggal lewat: {$startDate}");
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan sakit tidak boleh untuk tanggal yang sudah lewat'
                ], 400);
            }
            
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
            
            $firstRecord = $createdRecords[0];
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
                    'jenis_ketidakhadiran_label' => $this->getJenisKetidakhadiranLabel($firstRecord->jenis_ketidakhadiran),
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
     |  HELPER FUNCTIONS UNTUK CUTI
     ===================================================== */

    private function checkLeaveStatus($userId, $date)
    {
        try {
            $onLeave = Cuti::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $date)
                ->whereDate('tanggal_selesai', '>=', $date)
                ->first();
            
            if ($onLeave) {
                return [
                    'is_on_leave' => true,
                    'leave_type' => $onLeave->jenis_cuti,
                    'leave_type_label' => $this->getCutiTypeLabel($onLeave->jenis_cuti),
                    'dates' => [
                        'start' => $onLeave->tanggal_mulai->format('Y-m-d'),
                        'end' => $onLeave->tanggal_selesai->format('Y-m-d')
                    ],
                    'reason' => $onLeave->keterangan,
                    'duration_days' => $onLeave->durasi
                ];
            }
            
            return [
                'is_on_leave' => false
            ];
        } catch (\Exception $e) {
            \Log::error("Error checkLeaveStatus for user {$userId} on {$date}: " . $e->getMessage());
            return ['is_on_leave' => false];
        }
    }

    private function checkLeaveInRange($userId, $startDate, $endDate)
    {
        try {
            $leavesInRange = Cuti::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                        ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('tanggal_mulai', '<=', $startDate)
                                ->where('tanggal_selesai', '>=', $endDate);
                        });
                })
                ->get();
            
            if ($leavesInRange->isNotEmpty()) {
                $conflictDates = $leavesInRange->map(function ($leave) {
                    return Carbon::parse($leave->tanggal_mulai)->format('d/m/Y') . ' - ' . 
                           Carbon::parse($leave->tanggal_selesai)->format('d/m/Y');
                })->implode(', ');
                
                return [
                    'has_leave' => true,
                    'conflict_dates' => $conflictDates,
                    'leaves' => $leavesInRange
                ];
            }
            
            return [
                'has_leave' => false,
                'conflict_dates' => '',
                'leaves' => collect()
            ];
        } catch (\Exception $e) {
            \Log::error("Error checkLeaveInRange for user {$userId}: " . $e->getMessage());
            return [
                'has_leave' => false,
                'conflict_dates' => '',
                'leaves' => collect()
            ];
        }
    }

    private function getCutiTypeLabel($type)
    {
        return match($type) {
            'tahunan' => 'Tahunan',
            'sakit' => 'Sakit',
            'melahirkan' => 'Melahirkan',
            'penting' => 'Penting',
            'lainnya' => 'Lainnya',
            default => 'Umum',
        };
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
                ->whereDate('tanggal', '<=', Carbon::now()->addMonth())
                ->orderBy('tanggal', 'desc')
                ->orderBy('jam_masuk', 'desc');
            
            if ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            if ($jenis && $jenis !== 'all') {
                if ($jenis === 'terlambat') {
                    $query->whereTime('jam_masuk', '>', self::LIMIT_TIME . ':00');
                } elseif ($jenis === 'hadir') {
                    $query->whereTime('jam_masuk', '<=', self::LIMIT_TIME . ':00');
                }
            }
            
            if ($startDate) {
                $query->whereDate('tanggal', '>=', $startDate);
            }

            if ($endDate) {
                $query->whereDate('tanggal', '<=', $endDate);
            }
            
            $absensi = $query->paginate($perPage, ['*'], 'page', $page);
            
            $formattedData = $absensi->map(function($item) {
                $isTerlambat = false;
                $keterlambatan = 0;

                if ($item->jam_masuk) {
                    $jamMasuk = Carbon::parse($item->jam_masuk);
                    $jamBatas = Carbon::parse(self::LIMIT_TIME);
                    
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
                    'jenis_ketidakhadiran_label' => $item->jenis_ketidakhadiran ? $this->getJenisKetidakhadiranLabel($item->jenis_ketidakhadiran) : '',
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
            ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Tidak Masuk']) // PERBAIKAN 1: Gunakan 'status'
            ->orderBy('tanggal', 'desc');
        
        // PERBAIKAN 4: Gunakan when() untuk filter yang lebih bersih
        $query->when($search, function ($q, $search) {
            $q->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('keterangan', 'like', "%{$search}%")
                ->orWhere('reason', 'like', "%{$search}%");
            });
        })
        ->when($jenis && $jenis !== 'all', function ($q, $jenis) {
            $q->where('status', $jenis); // PERBAIKAN 1: Gunakan 'status'
        })
        ->when($approvalStatus && $approvalStatus !== 'all', function ($q, $approvalStatus) {
            $q->where('approval_status', $approvalStatus);
        })
        ->when($startDate, function ($q, $startDate) {
            $q->whereDate('tanggal', '>=', $startDate);
        })
        ->when($endDate, function ($q, $endDate) {
            $q->whereDate('tanggal', '<=', $endDate);
        });
        
        $ketidakhadiran = $query->paginate($perPage, ['*'], 'page', $page);
        
        $formattedData = $ketidakhadiran->map(function($item) {
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
                'status' => $item->status, // PERBAIKAN 1: Gunakan 'status'
                'status_label' => $this->getStatusLabel($item->status), // PERBAIKAN 3: Panggil helper yang benar
                'keterangan' => $item->keterangan,
                'reason' => $item->reason,
                'location' => $item->location,
                'purpose' => $item->purpose,
                'approval_status' => $item->approval_status,
                'approval_status_label' => $this->getApprovalStatusLabel($item->approval_status), // PERBAIKAN 3
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

// Jangan lupa tambahkan helper method ini di controller Anda
private function getStatusLabel($status) { /* ... */ }

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
            
            if (!$this->isValidDate($data['tanggal'])) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }
            
            $data = $this->prepareAbsensiData($data);
            
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
            
            if (!$this->isValidDate($data['tanggal']) || !$this->isValidDate($data['tanggal_akhir'])) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }
            
            $data['approval_status'] = 'pending';
            $data['jam_masuk'] = null;
            $data['jam_pulang'] = null;
            
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
            
            if (!$this->isValidDate($data['tanggal'])) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }
            
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
            
            if (!$this->isValidDate($data['tanggal']) || !$this->isValidDate($data['tanggal_akhir'])) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }
            
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
                ->whereDate('tanggal', '<=', Carbon::now()->addMonth());

            if ($divisi) {
                $query->whereHas('user', function ($q) use ($divisi) {
                    $q->where('divisi', $divisi);
                });
            }

            $stats = [
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
            
            if (!$this->isValidDate($tanggal)) {
                throw new \Exception('Tanggal tidak valid. Maksimal 1 bulan di masa depan.');
            }
            
            $divisions = User::select('divisi')
                ->whereNotNull('divisi')
                ->where('role', 'karyawan')
                ->distinct()
                ->pluck('divisi');

            $result = [];

            foreach ($divisions as $division) {
                $totalUsersInDivision = User::where('divisi', $division)
                    ->where('role', 'karyawan')
                    ->count();

                if ($totalUsersInDivision === 0) {
                    continue;
                }
                
                $presentCount = Absensi::whereHas('user', function($q) use ($division) {
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

    /* =====================================================
     |  HELPER METHODS
     ===================================================== */

    private function prepareAbsensiData(array $data, $existingData = null): array
    {
        if (isset($data['jenis_ketidakhadiran']) && in_array($data['jenis_ketidakhadiran'], ['cuti', 'sakit', 'izin'])) {
            if (!isset($data['approval_status'])) {
                $data['approval_status'] = 'pending';
            }
        } else {
            $data['approval_status'] = 'approved';
        }
        
        if (isset($data['jam_masuk']) && $data['jam_masuk']) {
            $data['jenis_ketidakhadiran'] = null;
        }
        
        if (!isset($data['tanggal_akhir']) || !$data['tanggal_akhir']) {
            $data['tanggal_akhir'] = $data['tanggal'];
        }

        return $data;
    }

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

    private function getApprovalStatusLabel($status): string
    {
        return match ($status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    public function isUserOnLeave($userId = null, $date = null)
    {
        $userId = $userId ?? Auth::id();
        $date = $date ?? Carbon::now()->format('Y-m-d');
        
        return Cuti::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $date)
            ->whereDate('tanggal_selesai', '>=', $date)
            ->exists();
    }

    public function validateAbsensiPermission($userId = null, $date = null)
    {
        $userId = $userId ?? Auth::id();
        $date = $date ?? Carbon::now()->format('Y-m-d');
        
        if ($this->isUserOnLeave($userId, $date)) {
            $leave = Cuti::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $date)
                ->whereDate('tanggal_selesai', '>=', $date)
                ->first();
            
            return [
                'allowed' => false,
                'message' => 'Anda sedang dalam status cuti (' . $this->getCutiTypeLabel($leave->jenis_cuti) . '). Tidak dapat melakukan absensi selama periode cuti.',
                'leave_type' => $leave->jenis_cuti,
                'dates' => [
                    'start' => $leave->tanggal_mulai,
                    'end' => $leave->tanggal_selesai
                ]
            ];
        }
        
        return [
            'allowed' => true,
            'message' => 'Boleh absen'
        ];
    }

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
}
