<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CutiController extends Controller
{
    /**
     * Display a listing of the resource (HTML View atau JSON Data)
     */
    public function index(Request $request)
    {
        Log::info('CutiController@index called', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'none',
            'ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'has_params' => $request->has(['page', 'per_page', 'search', 'status'])
        ]);
        
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            Log::warning('User tidak memiliki data karyawan', ['user_id' => $user->id]);
            
            // Jika request AJAX, kembalikan JSON
            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data profil karyawan tidak ditemukan. Silakan lengkapi profil terlebih dahulu.',
                    'redirect' => route('karyawan.profile')
                ], 404);
            }
            
            // Jika request biasa (browser), kembalikan view dengan error
            return view('karyawan.cuti', [
                'error' => 'Data profil karyawan tidak ditemukan. Silakan lengkapi profil terlebih dahulu.',
                'showProfileLink' => true
            ]);
        }
        
        $karyawan = $user->karyawan;
        Log::info('Karyawan ditemukan', ['karyawan_id' => $karyawan->id, 'karyawan_nama' => $karyawan->nama]);
        
        // Jika request AJAX/JSON untuk datatable (menggunakan GET dengan parameter pagination)
        if ($request->ajax() || $request->expectsJson() || $request->wantsJson() || 
            $request->has('page') || $request->has('per_page') || $request->has('search') || $request->has('status')) {
            
            Log::info('Mengarahkan ke getData()');
            return $this->getData($request);
        }

        // Untuk request biasa (non-AJAX) - tampilkan halaman HTML
        Log::info('Menampilkan view karyawan.cuti');
        return view('karyawan.cuti', [
            'karyawan' => $karyawan
        ]);
    }

    /**
     * Get data for datatable (JSON response)
     */
    public function getData(Request $request)
    {
        Log::info('CutiController@getData called', [
            'user_id' => Auth::id(),
            'params' => $request->all()
        ]);
        
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            Log::error('User tidak memiliki data karyawan di getData', ['user_id' => $user->id]);
            return response()->json([
                'success' => false,
                'message' => 'Data profil karyawan tidak ditemukan. Silakan lengkapi profil terlebih dahulu.',
                'redirect' => route('karyawan.profile')
            ], 404);
        }
        
        $karyawan = $user->karyawan;
        Log::info('Mengambil data cuti untuk karyawan', [
            'karyawan_id' => $karyawan->id,
            'karyawan_nama' => $karyawan->nama
        ]);

        try {
            $query = Cuti::where('karyawan_id', $karyawan->id)
                ->latest();

            // Filter berdasarkan status
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan pencarian
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('keterangan', 'like', "%{$search}%")
                        ->orWhere('jenis_cuti', 'like', "%{$search}%")
                        ->orWhere('tanggal_mulai', 'like', "%{$search}%")
                        ->orWhere('tanggal_selesai', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->per_page ?? 10;
            $cuti = $query->paginate($perPage);
            
            Log::info('Data cuti berhasil diambil', [
                'total' => $cuti->total(),
                'count' => $cuti->count(),
                'current_page' => $cuti->currentPage()
            ]);

            return response()->json([
                'success' => true,
                'data' => $cuti->items(),
                'pagination' => [
                    'current_page' => $cuti->currentPage(),
                    'last_page' => $cuti->lastPage(),
                    'per_page' => $cuti->perPage(),
                    'total' => $cuti->total(),
                    'from' => $cuti->firstItem(),
                    'to' => $cuti->lastItem(),
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error dalam getData: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data cuti: ' . $e->getMessage(),
                'debug' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Get data for datatable - alias untuk kompatibilitas
     */
    public function getDataTable(Request $request)
    {
        return $this->getData($request);
    }

    /**
     * Get cuti statistics for the logged-in employee
     */
    public function getStats(Request $request)
    {
        Log::info('CutiController@getStats called', ['user_id' => Auth::id()]);
        
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            Log::error('User tidak memiliki data karyawan di getStats', ['user_id' => $user->id]);
            return response()->json([
                'success' => false,
                'message' => 'Data profil karyawan tidak ditemukan. Silakan lengkapi profil terlebih dahulu.',
                'redirect' => route('karyawan.profile')
            ], 404);
        }
        
        $karyawan = $user->karyawan;
        Log::info('Menghitung stats untuk karyawan', ['karyawan_id' => $karyawan->id]);

        try {
            // Hitung statistik cuti
            $totalCutiTahunan = 12; // Default 12 hari cuti tahunan
            $cutiTerpakai = $karyawan->cuti()
                ->where('jenis_cuti', 'tahunan')
                ->where('status', 'disetujui')
                ->sum('durasi');
            
            $sisaCuti = max(0, $totalCutiTahunan - $cutiTerpakai);
            
            // Hitung statistik berdasarkan status
            $totalMenunggu = $karyawan->cuti()->where('status', 'menunggu')->count();
            $totalDisetujui = $karyawan->cuti()->where('status', 'disetujui')->count();
            $totalDitolak = $karyawan->cuti()->where('status', 'ditolak')->count();

            Log::info('Stats berhasil dihitung', [
                'cuti_terpakai' => $cutiTerpakai,
                'sisa_cuti' => $sisaCuti,
                'menunggu' => $totalMenunggu,
                'disetujui' => $totalDisetujui,
                'ditolak' => $totalDitolak
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_cuti_tahunan' => $totalCutiTahunan,
                    'cuti_terpakai' => $cutiTerpakai,
                    'sisa_cuti' => $sisaCuti,
                    'total_menunggu' => $totalMenunggu,
                    'total_disetujui' => $totalDisetujui,
                    'total_ditolak' => $totalDitolak,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error dalam getStats: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghitung statistik',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Check available annual leave days
     */
    public function checkAvailableDays(Request $request)
    {
        Log::info('CutiController@checkAvailableDays called', ['user_id' => Auth::id()]);
        
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data profil karyawan tidak ditemukan. Silakan lengkapi profil terlebih dahulu.',
                'redirect' => route('karyawan.profile')
            ], 404);
        }
        
        $karyawan = $user->karyawan;

        $totalCutiTahunan = 12; // Default 12 hari cuti tahunan
        
        // Hitung berdasarkan tahun berjalan
        $currentYear = date('Y');
        $cutiTerpakai = $karyawan->cuti()
            ->where('jenis_cuti', 'tahunan')
            ->where('status', 'disetujui')
            ->whereYear('tanggal_mulai', $currentYear)
            ->sum('durasi');
        
        $availableDays = max(0, $totalCutiTahunan - $cutiTerpakai);

        return response()->json([
            'success' => true,
            'available_days' => $availableDays,
            'quota_per_year' => $totalCutiTahunan,
            'taken_days' => $cutiTerpakai,
            'current_year' => $currentYear
        ]);
    }

    /**
     * Get available leave days (API for blade template)
     */
    public function getAvailableDays(Request $request)
    {
        Log::info('CutiController@getAvailableDays called', ['user_id' => Auth::id()]);
        
        $user = Auth::user();
        
        if (!$user->karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data profil karyawan tidak ditemukan.',
                'available_days' => 0
            ], 404);
        }
        
        $karyawan = $user->karyawan;
        
        // Hitung cuti tahunan yang sudah diambil
        $currentYear = date('Y');
        $totalTaken = $karyawan->cuti()
            ->where('jenis_cuti', 'tahunan')
            ->where('status', 'disetujui')
            ->whereYear('tanggal_mulai', $currentYear)
            ->sum('durasi');
        
        // Default quota cuti tahunan
        $quotaPerYear = 12;
        $availableDays = max(0, $quotaPerYear - $totalTaken);
        
        return response()->json([
            'success' => true,
            'available_days' => $availableDays,
            'quota_per_year' => $quotaPerYear,
            'taken_days' => $totalTaken,
            'current_year' => $currentYear
        ]);
    }

    /**
     * Calculate duration between dates
     */
    public function calculateDuration(Request $request)
    {
        Log::info('CutiController@calculateDuration called', $request->all());
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);
        
        // Hitung perbedaan hari (termasuk hari pertama)
        $interval = $startDate->diff($endDate);
        $duration = $interval->days + 1; // +1 untuk menghitung hari pertama

        return response()->json([
            'success' => true,
            'duration' => $duration,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
    }

    /**
     * Get dashboard stats for karyawan
     */
    public function dashboardStats(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data profil karyawan tidak ditemukan.'
            ], 404);
        }
        
        $karyawan = $user->karyawan;
        
        $stats = $this->calculateStats($karyawan);
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Helper function to calculate stats
     */
    private function calculateStats($karyawan)
    {
        $totalCutiTahunan = 12;
        
        // Hitung berdasarkan tahun berjalan
        $currentYear = date('Y');
        $cutiTerpakai = $karyawan->cuti()
            ->where('jenis_cuti', 'tahunan')
            ->where('status', 'disetujui')
            ->whereYear('tanggal_mulai', $currentYear)
            ->sum('durasi');
        
        $sisaCuti = max(0, $totalCutiTahunan - $cutiTerpakai);
        
        return [
            'total_cuti_tahunan' => $totalCutiTahunan,
            'cuti_terpakai' => $cutiTerpakai,
            'sisa_cuti' => $sisaCuti,
            'total_menunggu' => $karyawan->cuti()->where('status', 'menunggu')->count(),
            'total_disetujui' => $karyawan->cuti()->where('status', 'disetujui')->count(),
            'total_ditolak' => $karyawan->cuti()->where('status', 'ditolak')->count(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            return redirect()->route('karyawan.profile')
                ->with('error', 'Silakan lengkapi data profil terlebih dahulu sebelum mengajukan cuti.');
        }
        
        return view('karyawan.cuti.create', [
            'karyawan' => $user->karyawan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data profil karyawan tidak ditemukan. Silakan lengkapi profil terlebih dahulu.',
                    'redirect' => route('karyawan.profile')
                ], 404);
            }
            
            return redirect()->route('karyawan.profile')
                ->with('error', 'Silakan lengkapi data profil terlebih dahulu sebelum mengajukan cuti.');
        }
        
        $karyawan = $user->karyawan;

        // Validasi data
        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'durasi' => 'required|integer|min:1|max:30',
            'keterangan' => 'required|string|min:10|max:500',
            'jenis_cuti' => 'required|in:tahunan,sakit,penting,melahirkan,lainnya',
        ], [
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'keterangan.min' => 'Keterangan minimal 10 karakter.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
            'durasi.max' => 'Durasi cuti maksimal 30 hari.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek cuti tahunan jika jenis cuti adalah tahunan
        if ($request->jenis_cuti === 'tahunan') {
            // Hitung berdasarkan tahun berjalan
            $currentYear = date('Y');
            $cutiTerpakai = $karyawan->cuti()
                ->where('jenis_cuti', 'tahunan')
                ->where('status', 'disetujui')
                ->whereYear('tanggal_mulai', $currentYear)
                ->sum('durasi');
            
            $totalCutiTahunan = 12;
            $sisaCuti = max(0, $totalCutiTahunan - $cutiTerpakai);
            
            if ($request->durasi > $sisaCuti) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Durasi cuti melebihi sisa cuti tahunan Anda. Sisa cuti tahunan: {$sisaCuti} hari"
                    ], 422);
                }
                
                return redirect()->back()
                    ->with('error', "Durasi cuti melebihi sisa cuti tahunan Anda. Sisa cuti tahunan: {$sisaCuti} hari")
                    ->withInput();
            }
        }

        // Cek apakah ada cuti yang tumpang tindih
        $overlappingCuti = Cuti::where('karyawan_id', $karyawan->id)
            ->where('status', 'disetujui')
            ->where(function ($query) use ($request) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('tanggal_mulai', '<=', $request->tanggal_mulai)
                            ->where('tanggal_selesai', '>=', $request->tanggal_selesai);
                    });
            })
            ->exists();

        if ($overlappingCuti) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal cuti bertabrakan dengan cuti lain yang sudah disetujui.'
                ], 422);
            }
            
            return redirect()->back()
                ->with('error', 'Tanggal cuti bertabrakan dengan cuti lain yang sudah disetujui.')
                ->withInput();
        }

        // Simpan data cuti
        try {
            $cuti = Cuti::create([
                'karyawan_id' => $karyawan->id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'durasi' => $request->durasi,
                'keterangan' => $request->keterangan,
                'jenis_cuti' => $request->jenis_cuti,
                'status' => 'menunggu',
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan cuti berhasil dikirim.',
                    'data' => $cuti
                ]);
            }

            return redirect()->route('karyawan.cuti.index')
                ->with('success', 'Pengajuan cuti berhasil dikirim.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengirim pengajuan cuti.'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim pengajuan cuti.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            return redirect()->route('karyawan.profile')
                ->with('error', 'Silakan lengkapi data profil terlebih dahulu.');
        }
        
        $karyawan = $user->karyawan;
        
        $cuti = Cuti::where('karyawan_id', $karyawan->id)
            ->findOrFail($id);

        return view('karyawan.cuti.show', compact('cuti'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data profil karyawan tidak ditemukan. Silakan lengkapi profil terlebih dahulu.',
                    'redirect' => route('karyawan.profile')
                ], 404);
            }
            
            return redirect()->route('karyawan.profile')
                ->with('error', 'Silakan lengkapi data profil terlebih dahulu.');
        }
        
        $karyawan = $user->karyawan;
        
        $cuti = Cuti::where('karyawan_id', $karyawan->id)
            ->where('status', 'menunggu') // Hanya bisa edit cuti yang masih menunggu
            ->findOrFail($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $cuti
            ]);
        }

        return view('karyawan.cuti.edit', compact('cuti'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data profil karyawan tidak ditemukan. Silakan lengkapi profil terlebih dahulu.',
                    'redirect' => route('karyawan.profile')
                ], 404);
            }
            
            return redirect()->route('karyawan.profile')
                ->with('error', 'Silakan lengkapi data profil terlebih dahulu.');
        }
        
        $karyawan = $user->karyawan;

        // Cari cuti yang dimiliki oleh karyawan ini dan masih menunggu
        $cuti = Cuti::where('karyawan_id', $karyawan->id)
            ->where('status', 'menunggu')
            ->findOrFail($id);

        // Validasi data
        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'durasi' => 'required|integer|min:1|max:30',
            'keterangan' => 'required|string|min:10|max:500',
            'jenis_cuti' => 'required|in:tahunan,sakit,penting,melahirkan,lainnya',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek cuti tahunan jika jenis cuti adalah tahunan
        if ($request->jenis_cuti === 'tahunan') {
            // Hitung berdasarkan tahun berjalan
            $currentYear = date('Y');
            $cutiTerpakai = $karyawan->cuti()
                ->where('jenis_cuti', 'tahunan')
                ->where('status', 'disetujui')
                ->where('id', '!=', $cuti->id) // Kecualikan cuti yang sedang diedit
                ->whereYear('tanggal_mulai', $currentYear)
                ->sum('durasi');
            
            $totalCutiTahunan = 12;
            $sisaCuti = max(0, $totalCutiTahunan - $cutiTerpakai);
            
            if ($request->durasi > $sisaCuti) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Durasi cuti melebihi sisa cuti tahunan Anda. Sisa cuti tahunan: {$sisaCuti} hari"
                    ], 422);
                }
                
                return redirect()->back()
                    ->with('error', "Durasi cuti melebihi sisa cuti tahunan Anda. Sisa cuti tahunan: {$sisaCuti} hari")
                    ->withInput();
            }
        }

        // Cek apakah ada cuti lain yang tumpang tindih (kecuali cuti ini)
        $overlappingCuti = Cuti::where('karyawan_id', $karyawan->id)
            ->where('status', 'disetujui')
            ->where('id', '!=', $cuti->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('tanggal_mulai', '<=', $request->tanggal_mulai)
                            ->where('tanggal_selesai', '>=', $request->tanggal_selesai);
                    });
            })
            ->exists();

        if ($overlappingCuti) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal cuti bertabrakan dengan cuti lain yang sudah disetujui.'
                ], 422);
            }
            
            return redirect()->back()
                ->with('error', 'Tanggal cuti bertabrakan dengan cuti lain yang sudah disetujui.')
                ->withInput();
        }

        // Update data cuti
        try {
            $cuti->update([
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'durasi' => $request->durasi,
                'keterangan' => $request->keterangan,
                'jenis_cuti' => $request->jenis_cuti,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data cuti berhasil diperbarui.',
                    'data' => $cuti
                ]);
            }

            return redirect()->route('karyawan.cuti.index')
                ->with('success', 'Data cuti berhasil diperbarui.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data cuti.'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data cuti.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki data karyawan
        if (!$user->karyawan) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data profil karyawan tidak ditemukan. Silakan lengkapi profil terlebih dahulu.',
                    'redirect' => route('karyawan.profile')
                ], 404);
            }
            
            return redirect()->route('karyawan.profile')
                ->with('error', 'Silakan lengkapi data profil terlebih dahulu.');
        }
        
        $karyawan = $user->karyawan;

        // Cari cuti yang dimiliki oleh karyawan ini dan masih menunggu
        $cuti = Cuti::where('karyawan_id', $karyawan->id)
            ->where('status', 'menunggu') // Hanya bisa hapus cuti yang masih menunggu
            ->findOrFail($id);

        try {
            $cuti->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan cuti berhasil dihapus.'
                ]);
            }

            return redirect()->route('karyawan.cuti.index')
                ->with('success', 'Pengajuan cuti berhasil dihapus.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus pengajuan cuti.'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus pengajuan cuti.');
        }
    }

    /**
     * Cancel cuti request
     */
    public function cancel(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->karyawan) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data profil karyawan tidak ditemukan.'
                ], 404);
            }
            
            return redirect()->route('karyawan.profile')
                ->with('error', 'Silakan lengkapi data profil terlebih dahulu.');
        }
        
        $karyawan = $user->karyawan;
        
        $cuti = Cuti::where('karyawan_id', $karyawan->id)
            ->where('status', 'menunggu')
            ->findOrFail($id);
            
        $cuti->update(['status' => 'dibatalkan']);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil dibatalkan.'
            ]);
        }
        
        return redirect()->route('karyawan.cuti.index')
            ->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }

    /**
     * Export cuti data
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->karyawan) {
            return redirect()->route('karyawan.profile')
                ->with('error', 'Silakan lengkapi data profil terlebih dahulu.');
        }
        
        $karyawan = $user->karyawan;
        
        $cuti = Cuti::where('karyawan_id', $karyawan->id)
            ->latest()
            ->get();
        
        // Implement export logic here (PDF, Excel, etc.)
        return response()->json([
            'success' => true,
            'message' => 'Export functionality to be implemented'
        ]);
    }

    /**
     * Index for Admin - view all employee cuti requests
     */
    public function indexAdmin(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }
        
        $cuti = Cuti::with(['karyawan', 'disetujuiOleh'])
            ->latest()
            ->paginate(10);
            
        return view('admin.cuti.index', compact('cuti'));
    }

    /**
     * Index for General Manager
     */
    public function indexGeneralManager(Request $request)
    {
        if (!Auth::user()->hasRole('general_manager')) {
            abort(403);
        }
        
        $cuti = Cuti::with(['karyawan', 'disetujuiOleh'])
            ->latest()
            ->paginate(10);
            
        return view('general_manager.cuti.index', compact('cuti'));
    }

    /**
     * Index for Manager Divisi
     */
    public function indexManagerDivisi(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('manager_divisi')) {
            abort(403);
        }
        
        // Get karyawan in the same division
        $karyawanIds = Karyawan::where('divisi', $user->divisi)
            ->pluck('id');
            
        $cuti = Cuti::with(['karyawan', 'disetujuiOleh'])
            ->whereIn('karyawan_id', $karyawanIds)
            ->latest()
            ->paginate(10);
            
        return view('manager_divisi.cuti.index', compact('cuti'));
    }

    /**
     * Index for Owner
     */
    public function indexOwner(Request $request)
    {
        if (!Auth::user()->hasRole('owner')) {
            abort(403);
        }
        
        $cuti = Cuti::with(['karyawan', 'disetujuiOleh'])
            ->latest()
            ->paginate(10);
            
        return view('owner.cuti.index', compact('cuti'));
    }

    /**
     * Approve cuti request
     */
    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->hasAnyRole(['admin', 'general_manager', 'manager_divisi'])) {
            abort(403);
        }
        
        $cuti = Cuti::findOrFail($id);
        
        // Check if user has permission to approve
        if ($user->hasRole('manager_divisi')) {
            $karyawan = $cuti->karyawan;
            if ($karyawan->divisi !== $user->divisi) {
                abort(403);
            }
        }
        
        $cuti->update([
            'status' => 'disetujui',
            'disetujui_oleh' => $user->id,
            'tanggal_disetujui' => now()
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cuti berhasil disetujui.'
            ]);
        }
        
        return back()->with('success', 'Cuti berhasil disetujui.');
    }

    /**
     * Reject cuti request
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->hasAnyRole(['admin', 'general_manager', 'manager_divisi'])) {
            abort(403);
        }
        
        $cuti = Cuti::findOrFail($id);
        
        // Check if user has permission to reject
        if ($user->hasRole('manager_divisi')) {
            $karyawan = $cuti->karyawan;
            if ($karyawan->divisi !== $user->divisi) {
                abort(403);
            }
        }
        
        $request->validate([
            'alasan_penolakan' => 'required|string|min:5|max:500'
        ]);
        
        $cuti->update([
            'status' => 'ditolak',
            'disetujui_oleh' => $user->id,
            'tanggal_disetujui' => now(),
            'alasan_penolakan' => $request->alasan_penolakan
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cuti berhasil ditolak.'
            ]);
        }
        
        return back()->with('success', 'Cuti berhasil ditolak.');
    }

    /**
     * Generate cuti report
     */
    public function report(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasAnyRole(['admin', 'general_manager', 'owner'])) {
            abort(403);
        }
        
        $startDate = $request->get('start_date', Carbon::now()->startOfYear());
        $endDate = $request->get('end_date', Carbon::now()->endOfYear());
        $divisi = $request->get('divisi', 'all');
        
        $query = Cuti::with(['karyawan', 'disetujuiOleh'])
            ->whereBetween('created_at', [$startDate, $endDate]);
            
        if ($divisi !== 'all') {
            $karyawanIds = Karyawan::where('divisi', $divisi)->pluck('id');
            $query->whereIn('karyawan_id', $karyawanIds);
        }
        
        $cuti = $query->get();
        
        $stats = [
            'total' => $cuti->count(),
            'disetujui' => $cuti->where('status', 'disetujui')->count(),
            'menunggu' => $cuti->where('status', 'menunggu')->count(),
            'ditolak' => $cuti->where('status', 'ditolak')->count(),
            'dibatalkan' => $cuti->where('status', 'dibatalkan')->count(),
        ];
        
        return view('admin.cuti.report', compact('cuti', 'stats', 'startDate', 'endDate', 'divisi'));
    }

    /**
     * Export report data
     */
    public function exportReport(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasAnyRole(['admin', 'general_manager', 'owner'])) {
            abort(403);
        }
        
        // Implement export logic for report
        return response()->json([
            'success' => true,
            'message' => 'Export report functionality to be implemented'
        ]);
    }

    /**
     * Get admin stats for dashboard
     */
    public function getAdminStats(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasAnyRole(['admin', 'general_manager'])) {
            abort(403);
        }
        
        $stats = [
            'total_cuti' => Cuti::count(),
            'cuti_disetujui' => Cuti::where('status', 'disetujui')->count(),
            'cuti_menunggu' => Cuti::where('status', 'menunggu')->count(),
            'cuti_ditolak' => Cuti::where('status', 'ditolak')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}