<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
        
        // Mengambil data kehadiran (ada jam_masuk)
        $attendances = Absensi::with('user')
            ->whereNotNull('jam_masuk')
            ->orderBy('tanggal', 'desc')
            ->get();
            
        // Mengambil data ketidakhadiran (ada jenis_ketidakhadiran)
        $ketidakhadiran = Absensi::with('user')
            ->whereNotNull('jenis_ketidakhadiran')
            ->orderBy('tanggal', 'desc')
            ->get();
            
        // PERUBAHAN: Hanya mengambil user dengan role 'karyawan' untuk dropdown
        $users = User::where('role', 'karyawan')->get(); // HAPUS: ->where('status', 'active')
        
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
    $today = Carbon::today()->format('Y-m-d');
    $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
    $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
    
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
    public function rekapAbsensi()
    {
        // Mengambil semua data absensi dengan relasi user
        $absensis = Absensi::with('user')->get();
        
        // Mengambil data kehadiran (ada jam_masuk)
        $dataKehadiran = Absensi::with('user')
            ->whereNotNull('jam_masuk')
            ->orderBy('tanggal', 'desc')
            ->get();
            
        // Mengambil data ketidakhadiran (ada jenis_ketidakhadiran)
        $dataKetidakhadiran = Absensi::with('user')
            ->whereNotNull('jenis_ketidakhadiran')
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Menghitung statistik - REVISI: Tanpa menggunakan kolom 'status'
        $statistik = [
            'hadir' => Absensi::whereNotNull('jam_masuk')->count(),
            'tidak_hadir' => Absensi::whereNull('jam_masuk')->whereNull('jenis_ketidakhadiran')->count(),
            'cuti' => Absensi::where('jenis_ketidakhadiran', 'cuti')->count(),
            'sakit' => Absensi::where('jenis_ketidakhadiran', 'sakit')->count(),
            'izin' => Absensi::where('jenis_ketidakhadiran', 'izin')->count(),
            'dinas' => Absensi::where('jenis_ketidakhadiran', 'dinas-luar')->count(),
        ];
        
        return view('pemilik.rekap_absensi', compact('statistik', 'absensis', 'dataKehadiran', 'dataKetidakhadiran'));
    }

    /**
     * Menampilkan halaman kelola absensi untuk General Manager (versi baru)
     * 
     * @return \Illuminate\View\View
     */
    public function kelolaAbsensi()
    {
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
        // 1. Ambil SEMUA karyawan (Hanya yang role 'karyawan')
        $allEmployees = User::where('role', 'karyawan')->get(); // HAPUS: ->where('status', 'active')
        
        // 2. Ambil semua data absensi untuk tanggal yang ditentukan
        $dateAttendances = Absensi::whereDate('tanggal', $date)->get();
        
        // 3. Dapatkan ID karyawan yang sudah absen pada tanggal tersebut
        $checkedInEmployeeIds = $dateAttendances->pluck('user_id')->toArray();
        
        // 4. Cari karyawan yang belum absen
        $absentEmployees = $allEmployees->whereNotIn('id', $checkedInEmployeeIds);
        
        // 5. Untuk setiap karyawan yang tidak absen, buat record "Tidak Hadir"
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
                    'jenis_ketidakhadiran' => null, // Tidak hadir tanpa keterangan
                    'keterangan' => 'Tidak hadir tanpa keterangan',
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
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            $jenis = $request->get('jenis', ''); // 'hadir' atau 'terlambat'
            $startDate = $request->get('start_date', '');
            $endDate = $request->get('end_date', '');
            
            $query = Absensi::with(['user:id,name,email,jabatan'])
                ->where('approval_status', 'approved')
                ->whereNotNull('jam_masuk') // Hanya data kehadiran
                ->orderBy('tanggal', 'desc')
                ->orderBy('jam_masuk', 'desc');
            
            // Filter pencarian
            if ($search) {
                $query->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            // Filter jenis (hadir atau terlambat)
            if ($jenis && $jenis !== 'all') {
                if ($jenis === 'terlambat') {
                    // Menghitung terlambat berdasarkan jam masuk > 08:00
                    $query->whereTime('jam_masuk', '>', '08:00:00');
                } elseif ($jenis === 'hadir') {
                    $query->whereTime('jam_masuk', '<=', '08:00:00');
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
            
            $formattedData = $absensi->map(function($item) {
                // Hitung keterlambatan jika jam_masuk > 08:00
                $isTerlambat = false;
                $keterlambatan = 0;
                
                if ($item->jam_masuk) {
                    $jamMasuk = Carbon::parse($item->jam_masuk);
                    $jamBatas = Carbon::parse('08:00');
                    
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
            
            $query = Absensi::with(['user:id,name,email,jabatan', 'approver:id,name'])
                ->whereNotNull('jenis_ketidakhadiran')
                ->orderBy('tanggal', 'desc');
            
            // Filter pencarian
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas('user', function($userQuery) use ($search) {
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
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();
            
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
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $absensi->load('user')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
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
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'jenis_ketidakhadiran' => 'required|in:cuti',
            'keterangan' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();
            
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
            
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil dibuat dan menunggu persetujuan',
                'data' => $cuti->load('user')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
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
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();
            
            // Persiapkan data absensi
            $data = $this->prepareAbsensiData($data, $absensi);
            
            $absensi->update($data);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil diperbarui',
                'data' => $absensi->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
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
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();
            
            // Jika status persetujuan berubah, simpan informasi perubahan
            if (isset($data['approval_status']) && $data['approval_status'] !== 'pending') {
                $data['approved_by'] = auth()->user()->id;
                $data['approved_at'] = now();
            }
            
            $cuti->update($data);
            
            DB::commit();
            
            $statusText = $data['approval_status'] === 'approved' ? 'disetujui' : 'ditolak';
            $message = "Data cuti berhasil {$statusText}.";
                
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $cuti->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
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
            $recordType = $absensi->jenis_ketidakhadiran ? $this->getJenisKetidakhadiranLabel($absensi->jenis_ketidakhadiran) : 'Kehadiran';
            $absensi->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Data {$recordType} berhasil dihapus."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
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
        $validator = Validator::make($request->all(), [
            'approval_status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:approval_status,rejected|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
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
            
            // Pastikan hanya jenis ketidakhadiran yang bisa diverifikasi
            if (!$absensi->jenis_ketidakhadiran) {
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
    public function apiStatistics()
    {
        try {
            // Pastikan karyawan yang tidak hadir ditandai
            $today = Carbon::now()->format('Y-m-d');
            $this->markAbsentEmployees($today);
            
            $stats = [
                'total_hadir' => Absensi::whereNotNull('jam_masuk')->where('approval_status', 'approved')->count(),
                'total_tidak_hadir' => Absensi::whereNull('jam_masuk')
                    ->whereNull('jenis_ketidakhadiran')
                    ->where('approval_status', 'approved')
                    ->count(),
                'total_cuti' => Absensi::where('jenis_ketidakhadiran', 'cuti')->where('approval_status', 'approved')->count(),
                'total_sakit' => Absensi::where('jenis_ketidakhadiran', 'sakit')->where('approval_status', 'approved')->count(),
                'total_izin' => Absensi::where('jenis_ketidakhadiran', 'izin')->where('approval_status', 'approved')->count(),
                'total_dinas_luar' => Absensi::where('jenis_ketidakhadiran', 'dinas-luar')->where('approval_status', 'approved')->count(),
                'total_pending' => Absensi::where('approval_status', 'pending')->count(),
                'total_approved' => Absensi::where('approval_status', 'approved')->count(),
                'total_rejected' => Absensi::where('approval_status', 'rejected')->count(),
                // Statistik pending per kategori
                'total_cuti_pending' => Absensi::where('jenis_ketidakhadiran', 'cuti')->where('approval_status', 'pending')->count(),
                'total_sakit_pending' => Absensi::where('jenis_ketidakhadiran', 'sakit')->where('approval_status', 'pending')->count(),
                'total_izin_pending' => Absensi::where('jenis_ketidakhadiran', 'izin')->where('approval_status', 'pending')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik'
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
        return match($jenis) {
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
        return match($status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }
}