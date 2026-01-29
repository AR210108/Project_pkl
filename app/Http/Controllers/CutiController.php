<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            return $next($request);
        });
    }

    // ============================================
    // INDEX ROUTING
    // ============================================

    public function index(Request $request)
    {
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
                abort(403, 'Role tidak dikenali');
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
        $divisi = $this->currentDivisi;
        
        try {
            $cuti = Cuti::whereHas('user', function ($query) use ($divisi) {
                        $query->where('divisi', $divisi);
                    })
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            
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
                                ->get(['id', 'name', 'divisi', 'sisa_cuti']);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $cuti,
                    'stats' => [
                        'total' => $total,
                        'menunggu' => $menunggu,
                        'disetujui' => $disetujui,
                        'ditolak' => $ditolak
                    ]
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
            $cuti = Cuti::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $cuti->items()
                ]);
            }
            
            return view('general_manajer.acc_cuti', compact('cuti'));
        } catch (\Exception $e) {
            Log::error('GM Index Error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal memuat data GM'], 500);
            }
            abort(500, 'Terjadi kesalahan sistem');
        }
    }

    public function indexAdmin(Request $request) { return $this->indexGeneralManager($request); }
    public function indexOwner(Request $request) { return $this->indexGeneralManager($request); }

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
            $statuses = explode(',', $request->status);
            $query->whereIn('status', $statuses);
        }
        
        // 5. Search
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
        
        // 6. Filter Tanggal
        if ($request->has('start_date')) {
            $query->whereDate('tanggal_mulai', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('tanggal_selesai', '<=', $request->end_date);
        }
        
        // 7. Order & Paginate
        $query->orderBy('created_at', 'desc');
        $perPage = $request->get('per_page', 10);
        $cuti = $query->paginate($perPage);
        
        // 8. Formatting Data Response - FIX: Gunakan cara manual, bukan accessor
        $formattedData = $cuti->map(function ($item) {
            // === FIX: AMBIL DATA MANUAL DARI RELASI ===
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
            
            // === FIX: FORMAT TANGGAL MANUAL ===
            $tMulai = '-';
            $tSelesai = '-';
            $periode = '-';
            
            if ($item->tanggal_mulai) {
                try {
                    $tMulai = Carbon::parse($item->tanggal_mulai)->format('d F Y');
                } catch (\Exception $e) {
                    $tMulai = '-';
                }
            }
            
            if ($item->tanggal_selesai) {
                try {
                    $tSelesai = Carbon::parse($item->tanggal_selesai)->format('d F Y');
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
            
            // === FIX: JENIS CUTI MANUAL ===
            $jenisMap = [
                'tahunan' => 'Cuti Tahunan',
                'sakit' => 'Cuti Sakit',
                'penting' => 'Cuti Penting',
                'melahirkan' => 'Cuti Melahirkan',
                'lainnya' => 'Cuti Lainnya',
            ];
            $jenisText = $jenisMap[$item->jenis_cuti] ?? 'Cuti Lainnya';
            
            // === FIX: STATUS LABEL MANUAL ===
            $statusLabels = [
                'menunggu' => 'Menunggu Persetujuan',
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
            ];
            $statusLabel = $statusLabels[$item->status] ?? ucfirst($item->status);
            
            // === FIX: LOGIC BUSINESS MANUAL ===
            $dapatDisetujui = $item->status === 'menunggu';
            $dapatDiubah = $item->status === 'menunggu';
            $dapatDihapus = $item->status === 'menunggu';
            
            // === FIX: DISETUJUI OLEH ===
            $disetujuiOleh = null;
            $disetujuiPada = null;
            
            if ($item->disetujuiOleh) {
                $disetujuiOleh = $item->disetujuiOleh->name;
            }
            
            if ($item->disetujui_pada) {
                try {
                    $disetujuiPada = Carbon::parse($item->disetujui_pada)->format('Y-m-d H:i');
                } catch (\Exception $e) {
                    $disetujuiPada = null;
                }
            }
            
            // === FIX: CREATED AT ===
            $createdAt = '-';
            if ($item->created_at) {
                try {
                    $createdAt = Carbon::parse($item->created_at)->format('Y-m-d H:i');
                } catch (\Exception $e) {
                    $createdAt = '-';
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
                'status_badge' => $item->status,
                'sisa_cuti_karyawan' => $sisaCuti,
                'disetujui_oleh' => $disetujuiOleh,
                'disetujui_pada' => $disetujuiPada,
                'catatan_penolakan' => $item->catatan_penolakan ?? null,
                'created_at' => $createdAt,
                'dapat_disetujui' => $dapatDisetujui,
                'dapat_diubah' => $dapatDiubah,
                'dapat_dihapus' => $dapatDihapus,
                // DEBUG INFO (HAPUS SETELAH FIX)
                'debug_user_id' => $item->user_id,
                'debug_user_exists' => !is_null($item->user),
                'debug_user_name' => $item->user ? $item->user->name : 'NULL'
            ];
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
        Log::error('Error getData Cuti: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' Trace: ' . $e->getTraceAsString());
        
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
    // STATS
    // ============================================

   public function stats()
{
    try {
        $data = [];
        if (!$this->currentRole) return response()->json(['success' => false, 'message' => 'Role tidak terdeteksi'], 403);

        switch ($this->currentRole) {
            case 'karyawan':
                if (!$this->user) throw new \Exception('User tidak ditemukan');
                
                $userId = $this->user->id;
                $menunggu = Cuti::where('user_id', $userId)->where('status', 'menunggu')->count();
                $disetujui = Cuti::where('user_id', $userId)->where('status', 'disetujui')->count();
                $ditolak = Cuti::where('user_id', $userId)->where('status', 'ditolak')->count();
                $total = $menunggu + $disetujui + $ditolak;
                
                // Ambil dari kolom user
                $sisaCuti = (int)($this->user->sisa_cuti ?? 0);
                $cutiTahunan = 12; 
                $cutiTerpakai = $cutiTahunan - $sisaCuti;
                
                $data = [
                    'menunggu' => $menunggu,
                    'disetujui' => $disetujui,
                    'ditolak' => $ditolak,
                    'total' => $total,
                    'sisa_cuti' => $sisaCuti,
                    'cuti_terpakai' => $cutiTerpakai,
                    'total_cuti_tahunan' => $cutiTahunan
                ];
                break;
                
            case 'manager_divisi':
                $divisi = $this->currentDivisi;
                if (empty($divisi)) throw new \Exception('User tidak memiliki divisi');
                
                $baseQuery = Cuti::whereHas('user', function ($query) use ($divisi) {
                    $query->where('divisi', $divisi);
                });
                
                $total = (clone $baseQuery)->count();
                $menunggu = (clone $baseQuery)->where('status', 'menunggu')->count();
                $disetujui = (clone $baseQuery)->where('status', 'disetujui')->count();
                $ditolak = (clone $baseQuery)->where('status', 'ditolak')->count();
                
                $totalKaryawan = User::where('divisi', $divisi)->where('role', 'karyawan')->count();
                
                $avgSisaCuti = 0;
                if ($totalKaryawan > 0) {
                    $avgSisaCuti = User::where('divisi', $divisi)
                        ->where('role', 'karyawan')
                        ->avg('sisa_cuti') ?? 0;
                }
                
                $data = [
                    'total_pengajuan' => $total,
                    'menunggu' => $menunggu,
                    'disetujui' => $disetujui,
                    'ditolak' => $ditolak,
                    'total_karyawan' => $totalKaryawan,
                    'avg_sisa_cuti' => round($avgSisaCuti, 1)
                ];
                break;
                
            case 'general_manager':
            case 'admin':
            case 'owner':
                $menunggu = Cuti::where('status', 'menunggu')->count();
                $disetujui = Cuti::where('status', 'disetujui')->count();
                $ditolak = Cuti::where('status', 'ditolak')->count();
                $total = $menunggu + $disetujui + $ditolak;
                
                // Stats per divisi
                $statsPerDivisi = User::where('role', 'karyawan')
                    ->select('divisi', DB::raw('count(*) as total_karyawan'), DB::raw('avg(sisa_cuti) as avg_sisa_cuti'))
                    ->whereNotNull('divisi')
                    ->groupBy('divisi')
                    ->get();
                
                $data = [
                    'total' => $total,
                    'menunggu' => $menunggu,
                    'disetujui' => $disetujui,
                    'ditolak' => $ditolak,
                    'per_divisi' => $statsPerDivisi
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
        return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik'], 500);
    }
}

    // ============================================
    // STORE (CREATE)
    // ============================================

    public function store(Request $request)
    {
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
            
            // Validasi sisa cuti (Opsional, karena belum approve sisa tidak berkurang)
            // Tapi kita validasi agar user sadar
            if ($this->user->sisa_cuti < $validated['durasi']) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Sisa cuti tidak mencukupi. Sisa: ' . $this->user->sisa_cuti
                ], 400);
            }
            
            DB::beginTransaction();
            
            $cuti = Cuti::create([
                'user_id' => $this->user->id,
                'keterangan' => $validated['keterangan'],
                'jenis_cuti' => $validated['jenis_cuti'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'durasi' => $validated['durasi'],
                'status' => 'menunggu'
            ]);
            
            // Buat History (Cek dulu model ada/tidak)
            try {
                \App\Models\CutiHistory::create([
                    'cuti_id' => $cuti->id,
                    'action' => 'created',
                    'user_id' => $this->user->id,
                    'changes' => $validated,
                    'note' => 'Pengajuan cuti baru'
                ]);
            } catch (\Exception $eHistory) {
                Log::warning('Gagal create history: ' . $eHistory->getMessage());
                // Lanjut walau history gagal
            }

            DB::commit();
            
            // Hitung Stats Update
            $sisaCutiBaru = (int)$this->user->sisa_cuti;
            $cutiTahunan = 12; 
            $cutiTerpakaiBaru = $cutiTahunan - $sisaCutiBaru;
            $userId = $this->user->id;
            $menungguBaru = Cuti::where('user_id', $userId)->where('status', 'menunggu')->count();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil dikirim',
                'data' => $cuti,
                'updated_stats' => [
                    'total_cuti_tahunan' => $cutiTahunan,
                    'sisa_cuti' => $sisaCutiBaru,
                    'cuti_terpakai' => $cutiTerpakaiBaru,
                    'menunggu' => $menungguBaru
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengajukan cuti'], 500);
        }
    }

    // ============================================
    // APPROVE / REJECT / UPDATE / DELETE
    // ============================================

    public function approve(Request $request, $id)
    {
        try {
            $cuti = Cuti::with('user')->findOrFail($id);
            
            if ($this->currentRole === 'karyawan') {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            if ($this->currentRole === 'manager_divisi' && $cuti->user->divisi !== $this->currentDivisi) {
                return response()->json(['success' => false, 'message' => 'Beda divisi'], 403);
            }

            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Sudah diproses'], 400);
            }
            
            if ($cuti->user->sisa_cuti < $cuti->durasi) {
                 return response()->json([
                    'success' => false,
                    'message' => 'Sisa cuti karyawan tidak mencukupi. Sisa: ' . $cuti->user->sisa_cuti
                ], 400);
            }
            
            if ($cuti->isOverlapping()) {
                return response()->json(['success' => false, 'message' => 'Bertabrakan dengan cuti lain'], 400);
            }
            
            DB::beginTransaction();
            
            $cuti->status = 'disetujui';
            $cuti->disetujui_oleh = $this->user->id;
            $cuti->disetujui_pada = Carbon::now();
            $cuti->save();
            
            $cuti->user->sisa_cuti -= $cuti->durasi;
            $cuti->user->save();
            
            // History
            try {
                \App\Models\CutiHistory::create([
                    'cuti_id' => $cuti->id,
                    'action' => 'approved',
                    'user_id' => $this->user->id,
                    'changes' => ['status' => 'disetujui'],
                    'note' => 'Disetujui oleh ' . $this->currentRole
                ]);
            } catch (\Exception $e) { Log::warning('History error approve: ' . $e->getMessage()); }
            
            // Absensi
            try {
                Absensi::create([
                    'user_id' => $cuti->user_id,
                    'tanggal' => $cuti->tanggal_mulai,
                    'status' => 'cuti',
                    'keterangan' => 'Cuti disetujui: ' . $cuti->keterangan
                ]);
            } catch (\Exception $e) { Log::warning('Absensi error approve: ' . $e->getMessage()); }
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Cuti disetujui']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyetujui'], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $validated = $request->validate(['alasan_penolakan' => 'required|string|min:10']);
            
            $cuti = Cuti::with('user')->findOrFail($id);
            
            if ($this->currentRole === 'karyawan') return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            if ($this->currentRole === 'manager_divisi' && $cuti->user->divisi !== $this->currentDivisi) {
                return response()->json(['success' => false, 'message' => 'Beda divisi'], 403);
            }
            
            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Sudah diproses'], 400);
            }
            
            $cuti->status = 'ditolak';
            $cuti->disetujui_oleh = $this->user->id;
            $cuti->disetujui_pada = Carbon::now();
            $cuti->catatan_penolakan = $validated['alasan_penolakan'];
            $cuti->save();
            
            try {
                \App\Models\CutiHistory::create([
                    'cuti_id' => $cuti->id,
                    'action' => 'rejected',
                    'user_id' => $this->user->id,
                    'changes' => ['status' => 'ditolak'],
                    'note' => 'Ditolak oleh ' . $this->currentRole
                ]);
            } catch (\Exception $e) { Log::warning('History error reject: ' . $e->getMessage()); }
            
            return response()->json(['success' => true, 'message' => 'Cuti ditolak']);
            
        } catch (\Exception $e) {
            Log::error('Error rejecting cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menolak'], 500);
        }
    }

    public function update(Request $request, Cuti $cuti)
    {
        try {
            if ($cuti->user_id !== $this->user->id && !in_array($this->currentRole, ['admin', 'general_manager'])) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Tidak bisa diubah'], 400);
            }
            
            $validated = $request->validate([
                'keterangan' => 'required|string|max:255',
                'jenis_cuti' => 'required|in:tahunan,sakit,penting,melahirkan,lainnya',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'durasi' => 'required|integer|min:1'
            ]);
            
            $cuti->update($validated);
            
            try {
                \App\Models\CutiHistory::create([
                    'cuti_id' => $cuti->id,
                    'action' => 'updated',
                    'user_id' => $this->user->id,
                    'changes' => $validated,
                    'note' => 'Data diupdate'
                ]);
            } catch (\Exception $e) { Log::warning('History error update: ' . $e->getMessage()); }
            
            return response()->json(['success' => true, 'message' => 'Cuti diupdate', 'data' => $cuti]);
            
        } catch (\Exception $e) {
            Log::error('Error updating cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal update'], 500);
        }
    }

    public function destroy(Cuti $cuti)
    {
        try {
            if ($cuti->user_id !== $this->user->id && !in_array($this->currentRole, ['admin', 'general_manager'])) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            
            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Tidak bisa dihapus'], 400);
            }
            
            $cuti->delete();
            
            return response()->json(['success' => true, 'message' => 'Cuti dihapus']);
            
        } catch (\Exception $e) {
            Log::error('Error deleting cuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal hapus'], 500);
        }
    }

    // ============================================
    // UTILITIES
    // ============================================
    
    public function show(Cuti $cuti) {
        if ($this->currentRole === 'karyawan' && $cuti->user_id !== $this->user->id) abort(403);
        if ($this->currentRole === 'manager_divisi' && $cuti->user->divisi !== $this->currentDivisi) abort(403);
        return response()->json(['success' => true, 'data' => $cuti->load(['user', 'disetujuiOleh'])]);
    }

    public function edit(Cuti $cuti) {
        if ($cuti->user_id !== $this->user->id && !in_array($this->currentRole, ['admin', 'general_manager'])) abort(403);
        return view('karyawan.cuti_edit', compact('cuti'));
    }

    public function cancel(Cuti $cuti) {
        try {
            if ($cuti->user_id !== $this->user->id) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            if ($cuti->status !== 'menunggu') {
                return response()->json(['success' => false, 'message' => 'Sudah diproses'], 400);
            }
            $cuti->delete();
            return response()->json(['success' => true, 'message' => 'Cuti dibatalkan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal batalkan'], 500);
        }
    }

    public function calculateDuration(Request $request) {
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
                if ($current->dayOfWeek !== Carbon::SATURDAY && $current->dayOfWeek !== Carbon::SUNDAY) {
                    $totalDays++;
                }
                $current->addDay();
            }
            
            return response()->json(['success' => true, 'data' => ['jumlah_hari' => $totalDays]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal hitung durasi'], 500);
        }
    }
    
   public function getKaryawanByDivisi() {
    try {
        if ($this->currentRole !== 'manager_divisi') throw new \Exception('Akses ditolak');
        $karyawan = User::where('divisi', $this->currentDivisi)
                        ->where('role', 'karyawan')
                        ->select('id', 'name', 'divisi', 'sisa_cuti', 'email') // HAPUS jabatan
                        ->orderBy('name')
                        ->get();
        return response()->json(['success' => true, 'data' => $karyawan]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Gagal ambil data'], 500);
    }
}

    public function export(Request $request) {
        return response()->json(['success' => true, 'message' => 'Export placeholder']);
    }
}