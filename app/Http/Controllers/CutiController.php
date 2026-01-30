<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use App\Models\Absensi;
use App\Models\CutiHistory;
use App\Models\CutiQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CutiController extends Controller
{
    protected $user;
    protected $currentRole;
    protected $currentDivisi;

    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->currentRole = $this->user->role ?? null;
            $this->currentDivisi = $this->user->divisi ?? null;
            
            if (!$this->user) {
                abort(401, 'User tidak terautentikasi');
            }
            
            return $next($request);
        });
    }

    // ============================================
    // INDEX ROUTING
    // ============================================

    public function index(Request $request)
    {
        try {
            Log::info('Cuti index accessed', ['role' => $this->currentRole]);

            if (!$this->currentRole) {
                return response()->json(['success' => false, 'message' => 'Role tidak ditemukan'], 403);
            }

            switch ($this->currentRole) {
                case 'karyawan':
                    return $this->indexKaryawan($request);
                case 'manager_divisi':
                    return $this->indexManagerDivisi($request);
                case 'general_manager':
                    return $this->indexGeneralManager($request);
                case 'admin':
                    return $this->indexAdmin($request);
                case 'owner':
                    return $this->indexOwner($request);
                default:
                    return response()->json(['success' => false, 'message' => 'Role tidak dikenali: ' . $this->currentRole], 403);
            }
        } catch (\Exception $e) {
            Log::error('Cuti index error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function indexKaryawan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return $this->getData($request);
        }
        return view('karyawan.cuti');
    }

    public function indexManagerDivisi(Request $request)
    {
        try {
            if (!$this->currentDivisi) {
                return response()->json(['success' => false, 'message' => 'User tidak memiliki divisi'], 400);
            }
            
            $divisi = $this->currentDivisi;
            
            // Untuk filter
            $statusFilter = $request->get('status', 'all');
            $search = $request->get('search', '');
            
            $query = Cuti::whereHas('user', function ($query) use ($divisi) {
                $query->where('divisi', $divisi);
            })->with('user:id,name,divisi');
            
            if ($statusFilter !== 'all') {
                $query->where('status', $statusFilter);
            }
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('keterangan', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      });
                });
            }
            
            $cuti = $query->orderBy('created_at', 'desc')->get();
            
            // Stats
            $total = Cuti::whereHas('user', function ($query) use ($divisi) {
                $query->where('divisi', $divisi);
            })->count();
            
            $menunggu = Cuti::whereHas('user', function ($query) use ($divisi) {
                $query->where('divisi', $divisi);
            })->where('status', 'menunggu')->count();
            
            $disetujui = Cuti::whereHas('user', function ($query) use ($divisi) {
                $query->where('divisi', $divisi);
            })->where('status', 'disetujui')->count();
            
            $ditolak = Cuti::whereHas('user', function ($query) use ($divisi) {
                $query->where('divisi', $divisi);
            })->where('status', 'ditolak')->count();
            
            $karyawanDivisi = User::where('divisi', $divisi)
                                ->where('role', 'karyawan')
                                ->orderBy('name')
                                ->get(['id', 'name', 'divisi', 'sisa_cuti', 'email']);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $cuti->map(function($item) {
                        return $this->formatCutiData($item);
                    }),
                    'stats' => [
                        'total' => $total,
                        'menunggu' => $menunggu,
                        'disetujui' => $disetujui,
                        'ditolak' => $ditolak
                    ],
                    'karyawan' => $karyawanDivisi
                ]);
            }
            
            return view('manager_divisi.cuti', compact(
                'cuti',
                'total',
                'menunggu',
                'disetujui',
                'ditolak',
                'divisi',
                'karyawanDivisi'
            ));
        } catch (\Exception $e) {
            Log::error('Manager Index Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat data manager'], 500);
        }
    }

    public function indexGeneralManager(Request $request)
    {
        try {
            // Untuk filter
            $statusFilter = $request->get('status', 'all');
            $divisiFilter = $request->get('divisi', 'all');
            $search = $request->get('search', '');
            
            $query = Cuti::with(['user:id,name,divisi,email', 'disetujuiOleh:id,name']);
            
            if ($statusFilter !== 'all') {
                $query->where('status', $statusFilter);
            }
            
            if ($divisiFilter !== 'all') {
                $query->whereHas('user', function($q) use ($divisiFilter) {
                    $q->where('divisi', $divisiFilter);
                });
            }
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('keterangan', 'like', "%{$search}%")
                      ->orWhere('jenis_cuti', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%")
                             ->orWhere('divisi', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            
            $cuti = $query->orderBy('created_at', 'desc')
                         ->paginate(10);
            
            // Divisi list untuk filter
            $divisiList = User::where('role', 'karyawan')
                            ->whereNotNull('divisi')
                            ->select('divisi')
                            ->distinct()
                            ->pluck('divisi');
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $cuti->map(function($item) {
                        return $this->formatCutiData($item);
                    }),
                    'pagination' => [
                        'current_page' => $cuti->currentPage(),
                        'last_page' => $cuti->lastPage(),
                        'per_page' => $cuti->perPage(),
                        'total' => $cuti->total(),
                        'from' => $cuti->firstItem(),
                        'to' => $cuti->lastItem()
                    ],
                    'filters' => [
                        'divisi_list' => $divisiList
                    ]
                ]);
            }
            
            return view('general_manajer.acc_cuti', compact('cuti', 'divisiList'));
        } catch (\Exception $e) {
            Log::error('GM Index Error: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memuat data GM'], 500);
            }
            abort(500, 'Terjadi kesalahan sistem');
        }
    }

    public function indexAdmin(Request $request) { 
        return $this->indexGeneralManager($request); 
    }
    
    public function indexOwner(Request $request) { 
        return $this->indexGeneralManager($request); 
    }

    // ============================================
    // GET DATA API
    // ============================================

    public function getData(Request $request)
    {
        try {
            // 1. Inisialisasi Query
            $query = Cuti::query();

            // 2. Eager Load dengan kolom yang lengkap
            $query->with([
                'user:id,name,divisi,sisa_cuti,email',
                'disetujuiOleh:id,name'
            ]);

            // 3. Filter Berdasarkan Role
            switch ($this->currentRole) {
                case 'karyawan':
                    $query->where('user_id', $this->user->id);
                    break;
                case 'manager_divisi':
                    if (!$this->currentDivisi) {
                        return response()->json(['success' => false, 'message' => 'User tidak memiliki divisi'], 400);
                    }
                    $query->whereHas('user', function ($q) {
                        $q->where('divisi', $this->currentDivisi);
                    });
                    break;
                case 'general_manager':
                case 'admin':
                case 'owner':
                    // Lihat semua
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Role tidak dikenali'], 403);
            }
            
            // 4. Filter Status
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
            
            // 5. Filter Jenis Cuti
            if ($request->has('jenis_cuti') && $request->jenis_cuti !== 'all') {
                $query->where('jenis_cuti', $request->jenis_cuti);
            }
            
            // 6. Filter Divisi (hanya untuk role tertentu)
            if (in_array($this->currentRole, ['general_manager', 'admin', 'owner']) && 
                $request->has('divisi') && $request->divisi !== 'all') {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('divisi', $request->divisi);
                });
            }
            
            // 7. Search
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('keterangan', 'like', "%{$search}%")
                      ->orWhere('jenis_cuti', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('divisi', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            
            // 8. Filter Tanggal
            if ($request->has('tanggal_mulai')) {
                $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
            }
            if ($request->has('tanggal_selesai')) {
                $query->whereDate('tanggal_selesai', '<=', $request->tanggal_selesai);
            }
            
            // 9. Order & Paginate
            $query->orderBy('created_at', 'desc');
            $perPage = $request->get('per_page', 10);
            $cuti = $query->paginate($perPage);
            
            // 10. Formatting Data Response
            $formattedData = $cuti->map(function ($item) {
                return $this->formatCutiData($item);
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'pagination' => [
                    'total' => $cuti->total(),
                    'per_page' => $cuti->perPage(),
                    'current_page' => $cuti->currentPage(),
                    'last_page' => $cuti->lastPage(),
                    'from' => $cuti->firstItem(),
                    'to' => $cuti->lastItem()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getData Cuti: ' . $e->getMessage() . ' Line: ' . $e->getLine());
            
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengambil data.',
                'error' => env('APP_DEBUG') ? [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }

    // ============================================
    // HELPER: FORMAT CUTI DATA
    // ============================================

    private function formatCutiData($item)
    {
        try {
            // Ambil data user
            $userName = 'Unknown';
            $userDivisi = '-';
            $userEmail = null;
            $sisaCuti = 0;
            
            if ($item->user) {
                $userName = $item->user->name ?? 'Unknown';
                $userDivisi = $item->user->divisi ?? '-';
                $userEmail = $item->user->email ?? null;
                $sisaCuti = (int)($item->user->sisa_cuti ?? 0);
            }
            
            // Format tanggal
            $tMulai = '-';
            $tSelesai = '-';
            $periode = '-';
            
            if ($item->tanggal_mulai) {
                try {
                    $tMulai = Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y');
                } catch (\Exception $e) {
                    $tMulai = '-';
                }
            }
            
            if ($item->tanggal_selesai) {
                try {
                    $tSelesai = Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y');
                } catch (\Exception $e) {
                    $tSelesai = '-';
                }
            }
            
            if ($item->tanggal_mulai && $item->tanggal_selesai) {
                try {
                    $start = Carbon::parse($item->tanggal_mulai)->format('d/m/Y');
                    $end = Carbon::parse($item->tanggal_selesai)->format('d/m/Y');
                    $periode = $start . ' - ' . $end;
                } catch (\Exception $e) {
                    $periode = '-';
                }
            }
            
            // Jenis cuti
            $jenisMap = [
                'tahunan' => 'Cuti Tahunan',
                'sakit' => 'Cuti Sakit',
                'penting' => 'Cuti Penting',
                'melahirkan' => 'Cuti Melahirkan',
                'lainnya' => 'Cuti Lainnya',
            ];
            $jenisText = $jenisMap[$item->jenis_cuti] ?? 'Cuti Lainnya';
            
            // Status label
            $statusLabels = [
                'menunggu' => 'Menunggu Persetujuan',
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
                'dibatalkan' => 'Dibatalkan',
            ];
            $statusLabel = $statusLabels[$item->status] ?? ucfirst($item->status);
            
            // Status badge color
            $statusColors = [
                'menunggu' => 'warning',
                'disetujui' => 'success',
                'ditolak' => 'danger',
                'dibatalkan' => 'secondary',
            ];
            $statusColor = $statusColors[$item->status] ?? 'secondary';
            
            // Disetujui oleh
            $disetujuiOleh = null;
            $disetujuiPada = null;
            
            if ($item->disetujuiOleh) {
                $disetujuiOleh = $item->disetujuiOleh->name;
            }
            
            if ($item->disetujui_pada) {
                try {
                    $disetujuiPada = Carbon::parse($item->disetujui_pada)->format('d F Y H:i');
                } catch (\Exception $e) {
                    $disetujuiPada = null;
                }
            }
            
            // Dibatalkan oleh
            $dibatalkanOleh = null;
            $dibatalkanPada = null;
            
            if ($item->dibatalkanOleh) {
                $dibatalkanOleh = $item->dibatalkanOleh->name;
            }
            
            if ($item->dibatalkan_pada) {
                try {
                    $dibatalkanPada = Carbon::parse($item->dibatalkan_pada)->format('d F Y H:i');
                } catch (\Exception $e) {
                    $dibatalkanPada = null;
                }
            }
            
            // Created at
            $createdAt = '-';
            if ($item->created_at) {
                try {
                    $createdAt = Carbon::parse($item->created_at)->format('d F Y H:i');
                } catch (\Exception $e) {
                    $createdAt = '-';
                }
            }
            
            // Business logic
            $dapatDisetujui = $item->status === 'menunggu';
            $dapatDiubah = $item->status === 'menunggu';
            $dapatDihapus = $item->status === 'menunggu';
            $dapatLihat = true;
            $dapatBatalkan = $item->status === 'disetujui' && 
                            ($this->currentRole !== 'karyawan' || $item->user_id === $this->user->id);
            
            // Cek overlap (Safe call)
            $isOverlapping = false;
            if (method_exists($item, 'isOverlapping')) {
                try {
                    $isOverlapping = $item->isOverlapping();
                } catch (\Exception $e) {
                    // Ignore error if method fails
                }
            }
            
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'nama' => $userName,
                'email' => $userEmail,
                'divisi' => $userDivisi,
                'keterangan' => $item->keterangan ?? '-',
                'jenis_cuti' => $jenisText,
                'jenis_cuti_kode' => $item->jenis_cuti,
                'tanggal_mulai' => $item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai)->format('Y-m-d') : '-',
                'tanggal_selesai' => $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->format('Y-m-d') : '-',
                'tanggal_mulai_formatted' => $tMulai,
                'tanggal_selesai_formatted' => $tSelesai,
                'periode' => $periode,
                'durasi' => $item->durasi ?? 0,
                'status' => $item->status,
                'status_label' => $statusLabel,
                'status_color' => $statusColor,
                'sisa_cuti_karyawan' => $sisaCuti,
                'sisa_cuti_sebelum' => $item->sisa_cuti_sebelum ?? null,
                'sisa_cuti_sesudah' => $item->sisa_cuti_sesudah ?? null,
                'disetujui_oleh' => $disetujuiOleh,
                'disetujui_pada' => $disetujuiPada,
                'dibatalkan_oleh' => $dibatalkanOleh,
                'dibatalkan_pada' => $dibatalkanPada,
                'catatan_penolakan' => $item->catatan_penolakan ?? null,
                'catatan_pembatalan' => $item->catatan_pembatalan ?? null,
                'created_at' => $createdAt,
                'dapat_disetujui' => $dapatDisetujui,
                'dapat_diubah' => $dapatDiubah,
                'dapat_dihapus' => $dapatDihapus,
                'dapat_lihat' => $dapatLihat,
                'dapat_batalkan' => $dapatBatalkan,
                'is_overlapping' => $isOverlapping,
                'overlap_warning' => $isOverlapping ? 'Cuti ini bertabrakan dengan cuti lain yang sudah disetujui' : null
            ];
        } catch (\Exception $e) {
            Log::error('Error formatCutiData: ' . $e->getMessage());
            
            // Return minimal data jika error
            return [
                'id' => $item->id ?? 0,
                'user_id' => $item->user_id ?? 0,
                'nama' => 'Error',
                'divisi' => '-',
                'keterangan' => 'Error loading data',
                'jenis_cuti' => 'Error',
                'status' => 'error',
                'status_label' => 'Error',
                'status_color' => 'secondary',
                'created_at' => '-',
                'dapat_disetujui' => false,
                'dapat_diubah' => false,
                'dapat_dihapus' => false,
                'dapat_lihat' => true,
                'dapat_batalkan' => false
            ];
        }
    }

    // ============================================
    // STATS
    // ============================================

    public function stats()
    {
        try {
            $data = [];
            if (!$this->user || !$this->currentRole) {
                return response()->json(['success' => false, 'message' => 'User tidak terautentikasi'], 401);
            }

            switch ($this->currentRole) {
                case 'karyawan':
                    if (!$this->user) {
                        throw new \Exception('User tidak ditemukan');
                    }
                    
                    $userId = $this->user->id;
                    
                    // Count cuti
                    $menunggu = Cuti::where('user_id', $userId)->where('status', 'menunggu')->count();
                    $disetujui = Cuti::where('user_id', $userId)->where('status', 'disetujui')->count();
                    $ditolak = Cuti::where('user_id', $userId)->where('status', 'ditolak')->count();
                    $dibatalkan = Cuti::where('user_id', $userId)->where('status', 'dibatalkan')->count();
                    $total = $menunggu + $disetujui + $ditolak + $dibatalkan;
                    
                    // Ambil dari kolom user
                    $sisaCuti = (int)($this->user->sisa_cuti ?? 0);
                    $cutiTahunan = 12; // Default
                    $cutiTerpakai = $cutiTahunan - $sisaCuti;
                    
                    // Ambil quota info
                    try {
                        $quota = CutiQuota::getUserQuota($userId, date('Y'));
                        $quotaInfo = [
                            'tahun' => $quota->tahun,
                            'quota_tahunan' => $quota->quota_tahunan,
                            'terpakai' => $quota->terpakai,
                            'sisa' => $quota->sisa,
                            'quota_khusus' => $quota->quota_khusus ?? 0,
                            'terpakai_khusus' => $quota->terpakai_khusus ?? 0
                        ];
                    } catch (\Exception $e) {
                        Log::warning('Failed to get quota info: ' . $e->getMessage());
                        $quotaInfo = [
                            'tahun' => date('Y'),
                            'quota_tahunan' => 12,
                            'terpakai' => $cutiTerpakai,
                            'sisa' => $sisaCuti,
                            'quota_khusus' => 0,
                            'terpakai_khusus' => 0
                        ];
                    }
                    
                    $data = [
                        'menunggu' => $menunggu,
                        'disetujui' => $disetujui,
                        'ditolak' => $ditolak,
                        'dibatalkan' => $dibatalkan,
                        'total' => $total,
                        'sisa_cuti' => $sisaCuti,
                        'cuti_terpakai' => $cutiTerpakai,
                        'total_cuti_tahunan' => $cutiTahunan,
                        'quota_info' => $quotaInfo
                    ];
                    break;
                    
                case 'manager_divisi':
                    if (!$this->currentDivisi) {
                        throw new \Exception('User tidak memiliki divisi');
                    }
                    
                    $divisi = $this->currentDivisi;
                    
                    $baseQuery = Cuti::whereHas('user', function ($query) use ($divisi) {
                        $query->where('divisi', $divisi);
                    });
                    
                    $total = (clone $baseQuery)->count();
                    $menunggu = (clone $baseQuery)->where('status', 'menunggu')->count();
                    $disetujui = (clone $baseQuery)->where('status', 'disetujui')->count();
                    $ditolak = (clone $baseQuery)->where('status', 'ditolak')->count();
                    $dibatalkan = (clone $baseQuery)->where('status', 'dibatalkan')->count();
                    
                    $totalKaryawan = User::where('divisi', $divisi)
                                        ->where('role', 'karyawan')
                                        ->count();
                    
                    $data = [
                        'total_pengajuan' => $total,
                        'menunggu' => $menunggu,
                        'disetujui' => $disetujui,
                        'ditolak' => $ditolak,
                        'total_karyawan' => $totalKaryawan
                    ];
                    break;
                    
                case 'general_manager':
                case 'admin':
                case 'owner':
                    $menunggu = Cuti::where('status', 'menunggu')->count();
                    $disetujui = Cuti::where('status', 'disetujui')->count();
                    $ditolak = Cuti::where('status', 'ditolak')->count();
                    $dibatalkan = Cuti::where('status', 'dibatalkan')->count();
                    $total = $menunggu + $disetujui + $ditolak + $dibatalkan;
                    
                    $data = [
                        'total' => $total,
                        'menunggu' => $menunggu,
                        'disetujui' => $disetujui,
                        'ditolak' => $ditolak,
                        'dibatalkan' => $dibatalkan
                    ];
                    break;
                    
                default:
                    return response()->json(['success' => false, 'message' => 'Role tidak didukung'], 403);
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting stats: ' . $e->getMessage() . ' Line: ' . $e->getLine());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengambil statistik',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    // ============================================
    // GET QUOTA INFO
    // ============================================

    public function getQuotaInfo(Request $request)
    {
        try {
            $userId = $request->get('user_id') ?? $this->user->id;
            $year = $request->get('year') ?? date('Y');
            
            // Authorization
            if ($this->currentRole === 'karyawan' && $userId !== $this->user->id) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            // Cek apakah user ada
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
            }
            
            $quota = CutiQuota::getUserQuota($userId, $year);
            
            // Hitung statistik berdasarkan jenis cuti
            $cutiTahunan = Cuti::where('user_id', $userId)
                ->where('jenis_cuti', 'tahunan')
                ->where('status', 'disetujui')
                ->whereYear('tanggal_mulai', $year)
                ->sum('durasi');
            
            $cutiSakit = Cuti::where('user_id', $userId)
                ->where('jenis_cuti', 'sakit')
                ->where('status', 'disetujui')
                ->whereYear('tanggal_mulai', $year)
                ->count();
            
            $cutiPenting = Cuti::where('user_id', $userId)
                ->where('jenis_cuti', 'penting')
                ->where('status', 'disetujui')
                ->whereYear('tanggal_mulai', $year)
                ->count();
            
            $cutiMelahirkan = Cuti::where('user_id', $userId)
                ->where('jenis_cuti', 'melahirkan')
                ->where('status', 'disetujui')
                ->whereYear('tanggal_mulai', $year)
                ->count();
            
            $cutiLainnya = Cuti::where('user_id', $userId)
                ->where('jenis_cuti', 'lainnya')
                ->where('status', 'disetujui')
                ->whereYear('tanggal_mulai', $year)
                ->count();
            
            $cutiMenunggu = Cuti::where('user_id', $userId)
                ->where('status', 'menunggu')
                ->whereYear('tanggal_mulai', $year)
                ->count();
            
            $data = [
                'quota' => [
                    'id' => $quota->id,
                    'tahun' => $quota->tahun,
                    'quota_tahunan' => $quota->quota_tahunan,
                    'terpakai' => $quota->terpakai,
                    'sisa' => $quota->sisa,
                    'quota_khusus' => $quota->quota_khusus ?? 0,
                    'terpakai_khusus' => $quota->terpakai_khusus ?? 0,
                    'sisa_khusus' => ($quota->quota_khusus ?? 0) - ($quota->terpakai_khusus ?? 0),
                    'total_terpakai' => $quota->terpakai + ($quota->terpakai_khusus ?? 0),
                    'persentase_penggunaan' => $quota->quota_tahunan > 0 
                        ? round(($quota->terpakai / $quota->quota_tahunan) * 100, 1) 
                        : 0,
                    'is_active' => $quota->is_active ?? true,
                    'is_reset' => $quota->is_reset ?? false,
                    'reset_at' => $quota->reset_at ? $quota->reset_at->format('d F Y H:i') : null,
                    'reset_by_name' => (isset($quota->resetBy) && $quota->resetBy) ? $quota->resetBy->name : null
                ],
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'divisi' => $user->divisi,
                    'sisa_cuti' => $user->sisa_cuti ?? 0,
                    'cuti_terpakai_tahun_ini' => $user->cuti_terpakai_tahun_ini ?? 0,
                    'cuti_reset_date' => $user->cuti_reset_date
                ],
                'statistics' => [
                    'cuti_tahunan_disetujui' => $cutiTahunan,
                    'cuti_sakit_disetujui' => $cutiSakit,
                    'cuti_penting_disetujui' => $cutiPenting,
                    'cuti_melahirkan_disetujui' => $cutiMelahirkan,
                    'cuti_lainnya_disetujui' => $cutiLainnya,
                    'cuti_menunggu' => $cutiMenunggu,
                    'total_disetujui' => $cutiTahunan + $cutiSakit + $cutiPenting + $cutiMelahirkan + $cutiLainnya,
                    'total_pengajuan' => $cutiTahunan + $cutiSakit + $cutiPenting + $cutiMelahirkan + $cutiLainnya + $cutiMenunggu
                ],
                'quota_usage_percentage' => $quota->quota_tahunan > 0 
                    ? round(($quota->terpakai / $quota->quota_tahunan) * 100, 1) 
                    : 0
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting quota info: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengambil informasi quota',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    // ============================================
    // STORE (CREATE)
    // ============================================

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'keterangan' => 'required|string|max:255',
                'jenis_cuti' => 'required|in:tahunan,sakit,penting,melahirkan,lainnya',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'durasi' => 'required|integer|min:1'
            ]);
            
            if (!$this->user) {
                return response()->json(['success' => false, 'message' => 'Anda harus login'], 401);
            }
            
            // Validasi tanggal tidak di masa lalu (untuk cuti non-sakit)
            $today = Carbon::today();
            $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
            
            if ($validated['jenis_cuti'] !== 'sakit' && $tanggalMulai->lt($today)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cuti tidak bisa diajukan untuk tanggal yang sudah lewat'
                ], 400);
            }
            
            // Validasi sisa cuti untuk cuti tahunan
            if ($validated['jenis_cuti'] === 'tahunan') {
                $currentYear = date('Y');
                $quota = CutiQuota::getUserQuota($this->user->id, $currentYear);
                
                if ($quota->sisa < $validated['durasi']) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Sisa cuti tidak mencukupi. Sisa: ' . $quota->sisa . ' hari'
                    ], 400);
                }
            }
            
            // Cek overlap dengan cuti yang sudah disetujui
            $overlapCuti = Cuti::where('user_id', $this->user->id)
                ->where('status', 'disetujui')
                ->where(function($query) use ($validated) {
                    $query->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                          ->orWhereBetween('tanggal_selesai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                                ->where('tanggal_selesai', '>=', $validated['tanggal_selesai']);
                          });
                })
                ->exists();
            
            if ($overlapCuti) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cuti ini bertabrakan dengan cuti lain yang sudah disetujui'
                ], 400);
            }
            
            // Buat cuti
            $cuti = Cuti::create([
                'user_id' => $this->user->id,
                'keterangan' => $validated['keterangan'],
                'jenis_cuti' => $validated['jenis_cuti'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'durasi' => $validated['durasi'],
                'status' => 'menunggu'
            ]);
            
            // Buat History
            CutiHistory::create([
                'cuti_id' => $cuti->id,
                'action' => 'created',
                'user_id' => $this->user->id,
                'changes' => json_encode($validated),
                'note' => 'Pengajuan cuti baru - ' . $validated['jenis_cuti']
            ]);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil dikirim',
                'data' => $this->formatCutiData($cuti)
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengajukan cuti: ' . $e->getMessage()], 500);
        }
    }

    // ============================================
    // APPROVE CUTI
    // ============================================

    public function approve(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $cuti = Cuti::with('user')->findOrFail($id);
            
            // Authorization
            if ($this->currentRole === 'karyawan') {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            if ($this->currentRole === 'manager_divisi' && (!$cuti->user || $cuti->user->divisi !== $this->currentDivisi)) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke divisi ini'], 403);
            }

            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Cuti sudah diproses'], 400);
            }
            
            // Dapatkan quota cuti user untuk tahun ini
            $currentYear = date('Y');
            $quota = CutiQuota::getUserQuota($cuti->user_id, $currentYear);
            
            // Untuk cuti tahunan, validasi sisa quota
            if ($cuti->jenis_cuti === 'tahunan') {
                if ($quota->sisa < $cuti->durasi) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sisa cuti tidak mencukupi. Sisa: ' . $quota->sisa . ' hari'
                    ], 400);
                }
                
                // Simpan sisa cuti sebelum
                $cuti->sisa_cuti_sebelum = $quota->sisa;
                
                // Tambah cuti terpakai di quota
                $quota->addTerpakai($cuti->durasi);
                
                // Simpan sisa cuti sesudah
                $cuti->sisa_cuti_sesudah = $quota->sisa;
            } else {
                // Untuk cuti non-tahunan, tambah ke quota khusus
                $quota->addTerpakaiKhusus($cuti->durasi);
            }
            
            // Cek overlap
            if (method_exists($cuti, 'isOverlapping') && $cuti->isOverlapping()) {
                return response()->json(['success' => false, 'message' => 'Terdapat bentrok tanggal dengan cuti yang sudah disetujui'], 400);
            }
            
            // Update status cuti
            $cuti->status = 'disetujui';
            $cuti->disetujui_oleh = $this->user->id;
            $cuti->disetujui_pada = Carbon::now();
            $cuti->save();
            
            // Create history
            CutiHistory::create([
                'cuti_id' => $cuti->id,
                'action' => 'approved',
                'user_id' => $this->user->id,
                'changes' => json_encode(['status' => 'disetujui']),
                'note' => 'Disetujui oleh ' . $this->user->name . ' (' . $this->currentRole . ')'
            ]);
            
            // Create absensi records for each day of leave
            $startDate = Carbon::parse($cuti->tanggal_mulai);
            $endDate = Carbon::parse($cuti->tanggal_selesai);
            
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Skip weekends
                if (!$date->isWeekend()) {
                    Absensi::updateOrCreate(
                        [
                            'user_id' => $cuti->user_id,
                            'tanggal' => $date->format('Y-m-d')
                        ],
                        [
                            'jenis_ketidakhadiran' => 'cuti',
                            'keterangan' => $cuti->keterangan . ' (Disetujui)',
                            'approval_status' => 'approved',
                            'approved_by' => $this->user->id,
                            'approved_at' => Carbon::now()
                        ]
                    );
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Cuti disetujui',
                'quota_info' => [
                    'sisa_sebelum' => $cuti->sisa_cuti_sebelum ?? null,
                    'sisa_sesudah' => $cuti->sisa_cuti_sesudah ?? null,
                    'durasi' => $cuti->durasi,
                    'jenis_cuti' => $cuti->jenis_cuti
                ],
                'data' => $this->formatCutiData($cuti)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyetujui'], 500);
        }
    }

    // ============================================
    // REJECT CUTI
    // ============================================

    public function reject(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate(['alasan_penolakan' => 'required|string|max:255']);
            $cuti = Cuti::with('user')->findOrFail($id);
            
            // Authorization
            if ($this->currentRole === 'karyawan') {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            if ($this->currentRole === 'manager_divisi' && (!$cuti->user || $cuti->user->divisi !== $this->currentDivisi)) {
                return response()->json(['success' => false, 'message' => 'Akses divisi ditolak'], 403);
            }
            
            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Status bukan menunggu'], 400);
            }

            $cuti->status = 'ditolak';
            $cuti->disetujui_oleh = $this->user->id; // Track who rejected
            $cuti->disetujui_pada = Carbon::now();
            $cuti->catatan_penolakan = $validated['alasan_penolakan'];
            $cuti->save();

            CutiHistory::create([
                'cuti_id' => $cuti->id,
                'action' => 'rejected',
                'user_id' => $this->user->id,
                'changes' => json_encode(['alasan' => $validated['alasan_penolakan']]),
                'note' => 'Ditolak oleh ' . $this->user->name
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Cuti ditolak', 'data' => $this->formatCutiData($cuti)]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menolak cuti'], 500);
        }
    }

    // ============================================
    // CANCEL CUTI (With Refund)
    // ============================================

    public function cancelWithRefund(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $cuti = Cuti::with('user')->findOrFail($id);
            
            // Authorization
            if ($this->currentRole === 'karyawan' && $cuti->user_id !== $this->user->id) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            if ($cuti->status !== 'disetujui') {
                return response()->json(['success' => false, 'message' => 'Hanya cuti yang sudah disetujui yang dapat dibatalkan'], 400);
            }
            
            // Logika Refund Quota
            $currentYear = date('Y');
            $quota = CutiQuota::getUserQuota($cuti->user_id, $currentYear);
            
            $refundAmount = $cuti->durasi;
            $refundType = 'none';

            // Kita kembalikan quota HANYA jika tanggal cuti hari ini atau masa depan
            $today = Carbon::today();
            $startDate = Carbon::parse($cuti->tanggal_mulai);

            if ($startDate->gte($today)) {
                // Cuti belum terjadi atau sedang berlangsung -> Kembalikan Full
                if ($cuti->jenis_cuti === 'tahunan') {
                    $quota->reduceTerpakai($refundAmount);
                    $refundType = 'tahunan';
                } else {
                    $quota->reduceTerpakaiKhusus($refundAmount);
                    $refundType = 'khusus';
                }
            } else {
                // Cuti sudah lewat ( tanggal_mulai < hari ini )
                // Tidak refund
            }

            // Update status cuti
            $cuti->status = 'dibatalkan';
            $cuti->catatan_pembatalan = $request->catatan_pembatalan ?? 'Dibatalkan';
            $cuti->dibatalkan_oleh = $this->user->id;
            $cuti->dibatalkan_pada = Carbon::now();
            $cuti->save();
            
            // Hapus Absensi Records
            Absensi::where('user_id', $cuti->user_id)
                ->whereBetween('tanggal', [$cuti->tanggal_mulai, $cuti->tanggal_selesai])
                ->where('jenis_ketidakhadiran', 'cuti')
                ->delete();

            // Create history
            CutiHistory::create([
                'cuti_id' => $cuti->id,
                'action' => 'cancelled',
                'user_id' => $this->user->id,
                'changes' => json_encode(['refund_type' => $refundType, 'amount' => $refundAmount]),
                'note' => 'Dibatalkan oleh ' . $this->user->name . '. Refund: ' . ($startDate->gte($today) ? 'Ya' : 'Tidak (Expired)')
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Cuti dibatalkan' . ($startDate->gte($today) ? ' dan quota dikembalikan' : ''),
                'data' => $this->formatCutiData($cuti)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling cuti with refund: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membatalkan cuti'], 500);
        }
    }

    // ============================================
    // UPDATE METHOD
    // ============================================

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $cuti = Cuti::findOrFail($id);
            
            // Authorization
            if ($cuti->user_id !== $this->user->id && !in_array($this->currentRole, ['admin', 'general_manager'])) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Cuti tidak dapat diubah karena sudah diproses'], 400);
            }
            
            $validated = $request->validate([
                'keterangan' => 'required|string|max:255',
                'jenis_cuti' => 'required|in:tahunan,sakit,penting,melahirkan,lainnya',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'durasi' => 'required|integer|min:1'
            ]);
            
            // Simpan data lama untuk history
            $oldData = $cuti->toArray();
            
            // Update cuti
            $cuti->update($validated);
            
            // Create history
            $changes = [];
            foreach ($validated as $key => $value) {
                if ($oldData[$key] != $value) {
                    $changes[$key] = [
                        'from' => $oldData[$key],
                        'to' => $value
                    ];
                }
            }
            
            if (!empty($changes)) {
                CutiHistory::create([
                    'cuti_id' => $cuti->id,
                    'action' => 'updated',
                    'user_id' => $this->user->id,
                    'changes' => json_encode($changes),
                    'note' => 'Data cuti diperbarui'
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Cuti berhasil diperbarui',
                'data' => $this->formatCutiData($cuti)
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui cuti'], 500);
        }
    }

    // ============================================
    // DELETE METHOD
    // ============================================

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $cuti = Cuti::findOrFail($id);
            
            // Authorization
            if ($cuti->user_id !== $this->user->id && !in_array($this->currentRole, ['admin', 'general_manager'])) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Cuti tidak dapat dihapus karena sudah diproses. Gunakan fitur pembatalan.'], 400);
            }
            
            // Soft delete
            // PERHATIAN: Jangan akses deleted_by jika tidak ada di database/migration
            // Hapus baris: $cuti->deleted_by = $this->user->id;
            // Hapus baris: $cuti->save();
            
            $cuti->delete();
            
            // Create history
            CutiHistory::create([
                'cuti_id' => $cuti->id,
                'action' => 'deleted',
                'user_id' => $this->user->id,
                'changes' => null,
                'note' => 'Cuti dihapus oleh ' . $this->user->name
            ]);
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Cuti dihapus']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus cuti'], 500);
        }
    }

    // ============================================
    // RESET QUOTA (ADMIN ONLY)
    // ============================================

    public function resetQuota(Request $request)
    {
        try {
            // Authorization - hanya admin dan general manager
            if (!in_array($this->currentRole, ['admin', 'general_manager', 'owner'])) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            $year = $request->get('year') ?? date('Y');
            $userId = $request->get('user_id');
            $quotaKhusus = $request->get('quota_khusus', 0);
            
            if ($userId) {
                // Reset quota untuk user tertentu
                $quota = CutiQuota::where('user_id', $userId)
                    ->where('tahun', $year)
                    ->first();
                
                if (!$quota) {
                    return response()->json(['success' => false, 'message' => 'Quota tidak ditemukan'], 404);
                }
                
                $oldTerpakai = $quota->terpakai;
                $oldSisa = $quota->sisa;
                $oldTerpakaiKhusus = $quota->terpakai_khusus;
                
                $quota->update([
                    'terpakai' => 0,
                    'sisa' => $quota->quota_tahunan,
                    'quota_khusus' => $quotaKhusus,
                    'terpakai_khusus' => 0,
                    'is_reset' => true,
                    'reset_at' => now(),
                    'reset_by' => $this->user->id
                ]);
                
                // Update user
                $user = User::find($userId);
                if ($user) {
                    $user->update([
                        'sisa_cuti' => $quota->quota_tahunan,
                        'cuti_terpakai_tahun_ini' => 0,
                        'cuti_reset_date' => now()
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Quota berhasil direset untuk user ' . $user->name,
                    'data' => [
                        'before' => [
                            'terpakai' => $oldTerpakai, 
                            'sisa' => $oldSisa,
                            'terpakai_khusus' => $oldTerpakaiKhusus
                        ],
                        'after' => [
                            'terpakai' => 0, 
                            'sisa' => $quota->quota_tahunan,
                            'quota_khusus' => $quotaKhusus,
                            'terpakai_khusus' => 0
                        ],
                        'reset_at' => now()->format('d F Y H:i'),
                        'reset_by' => $this->user->name
                    ]
                ]);
            } else {
                // Reset quota untuk semua user
                $quotas = CutiQuota::where('tahun', $year)->get();
                $resetCount = 0;
                
                foreach ($quotas as $quota) {
                    $quota->update([
                        'terpakai' => 0,
                        'sisa' => $quota->quota_tahunan,
                        'quota_khusus' => $quotaKhusus,
                        'terpakai_khusus' => 0,
                        'is_reset' => true,
                        'reset_at' => now(),
                        'reset_by' => $this->user->id
                    ]);
                    
                    $user = User::find($quota->user_id);
                    if ($user) {
                        $user->update([
                            'sisa_cuti' => $quota->quota_tahunan,
                            'cuti_terpakai_tahun_ini' => 0,
                            'cuti_reset_date' => now()
                        ]);
                    }
                    
                    $resetCount++;
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Quota berhasil direset untuk ' . $resetCount . ' user',
                    'data' => [
                        'total_reset' => $resetCount,
                        'year' => $year,
                        'quota_khusus' => $quotaKhusus,
                        'reset_at' => now()->format('d F Y H:i'),
                        'reset_by' => $this->user->name
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error resetting quota: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mereset quota'], 500);
        }
    }

    // ============================================
    // UTILITY METHODS
    // ============================================
    
    public function show($id) 
    {
        try {
            $cuti = Cuti::with(['user:id,name,divisi,email,sisa_cuti', 'disetujuiOleh:id,name', 'dibatalkanOleh:id,name', 'histories.user:id,name'])->findOrFail($id);
            
            // Authorization
            if ($this->currentRole === 'karyawan' && $cuti->user_id !== $this->user->id) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            if ($this->currentRole === 'manager_divisi' && (!$cuti->user || $cuti->user->divisi !== $this->currentDivisi)) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            $histories = $cuti->histories->map(function($history) {
                return [
                    'action' => $history->action,
                    'action_label' => method_exists($history, 'getActionLabelAttribute') ? $history->action_label : ucfirst($history->action),
                    'note' => $history->note,
                    'user_name' => $history->user ? $history->user->name : 'System',
                    'created_at' => $history->created_at->format('d F Y H:i')
                ];
            });
            
            return response()->json([
                'success' => true, 
                'data' => array_merge(
                    $this->formatCutiData($cuti),
                    [
                        'histories' => $histories
                    ]
                )
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data cuti'], 500);
        }
    }

    public function edit($id) 
    {
        try {
            $cuti = Cuti::findOrFail($id);
            
            if ($cuti->user_id !== $this->user->id && !in_array($this->currentRole, ['admin', 'general_manager'])) {
                abort(403, 'Akses ditolak');
            }
            
            if ($cuti->status !== 'menunggu') {
                abort(400, 'Cuti tidak dapat diedit karena sudah diproses');
            }
            
            return view('karyawan.cuti_edit', compact('cuti'));
        } catch (\Exception $e) {
            Log::error('Error edit cuti: ' . $e->getMessage());
            abort(500, 'Terjadi kesalahan sistem');
        }
    }

    public function cancel($id) 
    {
        DB::beginTransaction();
        try {
            $cuti = Cuti::findOrFail($id);
            
            if ($cuti->user_id !== $this->user->id) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Cuti tidak dapat dibatalkan karena sudah diproses'], 400);
            }
            
            // Soft delete
            // HAPUS BARRIS INI: $cuti->deleted_by = $this->user->id;
            // HAPUS BARRIS INI: $cuti->save();
            $cuti->delete();
            
            // Create history
            CutiHistory::create([
                'cuti_id' => $cuti->id,
                'action' => 'cancelled',
                'user_id' => $this->user->id,
                'changes' => null,
                'note' => 'Cuti dibatalkan oleh karyawan'
            ]);
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Cuti berhasil dibatalkan']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membatalkan cuti'], 500);
        }
    }

    public function calculateDuration(Request $request) 
    {
        try {
            $validated = $request->validate([
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
            ]);
            
            $start = Carbon::parse($validated['tanggal_mulai']);
            $end = Carbon::parse($validated['tanggal_selesai']);
            
            $totalDays = 0;
            $current = $start->copy();
            
            while ($current->lte($end)) {
                // Exclude weekends
                if (!$current->isWeekend()) {
                    $totalDays++;
                }
                $current->addDay();
            }
            
            return response()->json([
                'success' => true, 
                'data' => [
                    'jumlah_hari' => $totalDays,
                    'tanggal_mulai' => $start->format('d F Y'),
                    'tanggal_selesai' => $end->format('d F Y'),
                    'hari_kerja' => $totalDays . ' hari kerja'
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error calculating duration: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghitung durasi'], 500);
        }
    }
    
    public function getKaryawanByDivisi() 
    {
        try {
            if ($this->currentRole !== 'manager_divisi') {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            if (!$this->currentDivisi) {
                return response()->json(['success' => false, 'message' => 'User tidak memiliki divisi'], 400);
            }
            
            $karyawan = User::where('divisi', $this->currentDivisi)
                            ->where('role', 'karyawan')
                            ->select('id', 'name', 'divisi', 'sisa_cuti', 'email')
                            ->orderBy('name')
                            ->get()
                            ->map(function($user) {
                                $quotaInfo = [];
                                try {
                                    $quotaInfo = CutiQuota::getUserQuota($user->id, date('Y'));
                                } catch (\Exception $e) {
                                    Log::warning('Failed to get quota for user ' . $user->id . ': ' . $e->getMessage());
                                }
                                
                                return [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                    'divisi' => $user->divisi,
                                    'sisa_cuti' => (int)$user->sisa_cuti,
                                    'email' => $user->email,
                                    'cuti_terpakai' => 12 - (int)$user->sisa_cuti,
                                    'quota_info' => $quotaInfo
                                ];
                            });
            
            return response()->json(['success' => true, 'data' => $karyawan]);
            
        } catch (\Exception $e) {
            Log::error('Error getting karyawan by divisi: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data karyawan'], 500);
        }
    }

    public function checkDatabase()
    {
        try {
            // Test database connection
            DB::connection()->getPdo();
            
            // Test models
            $userCount = User::count();
            $cutiCount = Cuti::count();
            $quotaCount = CutiQuota::count();
            
            return response()->json([
                'success' => true,
                'database' => 'Connected',
                'counts' => [
                    'users' => $userCount,
                    'cuti' => $cutiCount,
                    'quota' => $quotaCount
                ],
                'user_info' => $this->user ? [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'role' => $this->user->role,
                    'divisi' => $this->user->divisi
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}