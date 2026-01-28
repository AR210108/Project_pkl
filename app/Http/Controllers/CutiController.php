<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Jika request AJAX untuk datatable, redirect ke getData
        if ($request->ajax() || $request->wantsJson() || $request->has('page')) {
            return $this->getData($request);
        }

        return view('karyawan.cuti');
    }

    /**
     * Get data for datatable (JSON response)
     */
    public function getData(Request $request)
    {
        Log::info('CutiController@getData called', ['user_id' => Auth::id()]);
        
        $userId = Auth::id();

        try {
            $query = Cuti::where('user_id', $userId)->latest();

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
            
            return response()->json([
                'success' => true,
                'data' => $cuti->items(),
                'pagination' => [
                    'current_page' => $cuti->currentPage(),
                    'last_page' => $cuti->lastPage(),
                    'per_page' => $cuti->perPage(),
                    'total' => $cuti->total(),
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getData: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data'], 500);
        }
    }

    /**
     * Get cuti statistics
     */
    public function getStats(Request $request)
    {
        $userId = Auth::id();
        $stats = $this->calculateStatsGeneric($userId);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        // 1. Validasi Data (Durasi tidak wajib karena akan dihitung otomatis)
        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            // 'durasi' sengaja tidak di-wajib-kan (removed) agar bisa dihitung otomatis
            'keterangan' => 'required|string|min:10|max:500',
            'jenis_cuti' => 'required|in:tahunan,sakit,penting,melahirkan,lainnya',
        ], [
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. HITUNG DURASI OTOMATIS (Jika user tidak mengisi manual)
        $durasi = 0;
        if (empty($request->durasi)) {
            $start = new \DateTime($request->tanggal_mulai);
            $end = new \DateTime($request->tanggal_selesai);
            $interval = $start->diff($end);
            $durasi = $interval->days + 1; // +1 untuk menghitung hari pertama
        } else {
            $durasi = (int)$request->durasi;
        }

        // 3. Validasi Kuota Cuti (Jika Tahunan)
        if ($request->jenis_cuti === 'tahunan') {
            $currentYear = date('Y');
            $cutiTerpakai = Cuti::where('user_id', $userId)
                ->where('jenis_cuti', 'tahunan')
                ->where('status', 'disetujui')
                ->whereYear('tanggal_mulai', $currentYear)
                ->sum('durasi');
            
            $totalCutiTahunan = 12; // Atau ambil dari setting sistem
            $sisaCuti = max(0, $totalCutiTahunan - $cutiTerpakai);
            
            if ($durasi > $sisaCuti) {
                return response()->json([
                    'success' => false,
                    'message' => "Durasi cuti melebihi sisa cuti tahunan Anda. Sisa cuti tahunan: {$sisaCuti} hari"
                ], 422);
            }
        }

        // 4. Cek Overlapping (Bentrok Jadwal)
        $overlappingCuti = Cuti::where('user_id', $userId)
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
            return response()->json([
                'success' => false,
                'message' => 'Tanggal cuti bertabrakan dengan cuti lain yang sudah disetujui.'
            ], 422);
        }

        // 5. Simpan Data
        try {
            $cuti = Cuti::create([
                'user_id' => $userId,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'durasi' => $durasi, // Simpan durasi hasil hitungan
                'keterangan' => $request->keterangan,
                'jenis_cuti' => $request->jenis_cuti,
                'status' => 'menunggu',
            ]);

            // 6. HITUNG STATS TERBARU UNTUK DIKEMBALIKAN KE FRONTEND
            // Ini agar kartu di browser langsung berubah tanpa reload
            $currentStats = $this->calculateStatsGeneric($userId);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil dikirim.',
                'data' => $cuti,
                'updated_stats' => $currentStats // <-- Kirim stats baru
            ]);

        } catch (\Exception $e) {
            Log::error('Error store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem.'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $cuti = Cuti::where('user_id', $userId)->where('id', $id)->firstOrFail();

        // Hanya boleh edit jika status 'menunggu'
        if ($cuti->status !== 'menunggu') {
            return response()->json(['success' => false, 'message' => 'Data tidak dapat diubah karena sudah diproses.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'durasi' => 'required|integer|min:1',
            'keterangan' => 'required|string|min:10|max:500',
            'jenis_cuti' => 'required|in:tahunan,sakit,penting,melahirkan,lainnya',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek Overlapping saat edit
        $overlappingCuti = Cuti::where('user_id', $userId)
            ->where('id', '!=', $id) // Kecualikan diri sendiri
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
            return response()->json([
                'success' => false,
                'message' => 'Tanggal cuti bertabrakan dengan cuti lain yang sudah disetujui.'
            ], 422);
        }

        try {
            $cuti->update($request->only(['tanggal_mulai', 'tanggal_selesai', 'durasi', 'keterangan', 'jenis_cuti']));

            // Hitung ulang stats
            $stats = $this->calculateStatsGeneric($userId);

            return response()->json([
                'success' => true,
                'message' => 'Data cuti berhasil diperbarui.',
                'updated_stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        $cuti = Cuti::where('user_id', $userId)->where('id', $id)->firstOrFail();
        
        // Hanya bisa hapus jika status menunggu
        if ($cuti->status !== 'menunggu') {
            return response()->json(['success' => false, 'message' => 'Hanya bisa menghapus pengajuan menunggu'], 403);
        }

        try {
            $cuti->delete();
            
            // Hitung ulang stats setelah hapus
            $stats = $this->calculateStatsGeneric($userId);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan dihapus.',
                'updated_stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengajuan.'
            ], 500);
        }
    }

    /**
     * Approve cuti request
     */
    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        
        // Cek Role
        if (!$user->hasAnyRole(['admin', 'general_manager', 'manager_divisi'])) {
            abort(403);
        }
        
        $cuti = Cuti::findOrFail($id);
        
        $cuti->update([
            'status' => 'disetujui',
            'disetujui_oleh' => $user->id,
            'disetujui_pada' => now()
        ]);
        
        // HITUNG STATS TERBARU MILIK PEMILIK CUTI
        // Kita kembalikan stats milik user yang punya cuti tersebut
        $stats = $this->calculateStatsGeneric($cuti->user_id);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cuti berhasil disetujui.',
                'updated_stats' => $stats // <-- Kirim stats terbaru pemilik
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
        
        $request->validate([
            'alasan_penolakan' => 'required|string|min:5|max:500'
        ]);
        
        $cuti->update([
            'status' => 'ditolak',
            'disetujui_oleh' => $user->id,
            'disetujui_pada' => now(),
            'catatan_penolakan' => $request->alasan_penolakan
        ]);
        
        // HITUNG STATS TERBARU MILIK PEMILIK CUTI
        $stats = $this->calculateStatsGeneric($cuti->user_id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cuti berhasil ditolak.',
                'updated_stats' => $stats // <-- Kirim stats terbaru pemilik
            ]);
        }
        
        return back()->with('success', 'Cuti berhasil ditolak.');
    }

    /**
     * Helper function to calculate stats for a specific user
     * Digunakan agar kode tidak duplikat di store, approve, reject
     */
    private function calculateStatsGeneric($userId)
    {
        $totalCutiTahunan = 12; // Default 12 hari
        
        // Hitung berdasarkan tahun berjalan
        $currentYear = date('Y');
        $cutiTerpakai = Cuti::where('user_id', $userId)
            ->where('jenis_cuti', 'tahunan')
            ->where('status', 'disetujui')
            ->whereYear('tanggal_mulai', $currentYear)
            ->sum('durasi');
        
        $sisaCuti = max(0, $totalCutiTahunan - $cutiTerpakai);
        
        return [
            'total_cuti_tahunan' => $totalCutiTahunan,
            'cuti_terpakai' => $cutiTerpakai,
            'sisa_cuti' => $sisaCuti,
            'total_menunggu' => Cuti::where('user_id', $userId)->where('status', 'menunggu')->count(),
            'total_disetujui' => Cuti::where('user_id', $userId)->where('status', 'disetujui')->count(),
            'total_ditolak' => Cuti::where('user_id', $userId)->where('status', 'ditolak')->count(),
        ];
    }
}