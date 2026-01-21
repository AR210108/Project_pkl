<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /* =====================================================
     |  ADMIN
     ===================================================== */
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');
        $this->markAbsentEmployees($today);

        $stats = $this->apiStatistics()->getData(true)['data'];

        $attendances = Absensi::with('user')
            ->whereNotIn('status', ['Tidak Masuk', 'Cuti', 'Sakit', 'Izin'])
            ->latest('tanggal')
            ->get();

        $ketidakhadiran = Absensi::with('user')
            ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Tidak Masuk'])
            ->latest('tanggal')
            ->get();

        $users = User::where('role', 'karyawan')->get();

        return view('admin.absensi', compact('stats', 'attendances', 'ketidakhadiran', 'users'));
    }

    /* =====================================================
     |  GENERAL MANAGER
     ===================================================== */
    public function absenGeneral()
    {
        $today = Carbon::today()->format('Y-m-d');
        $this->markAbsentEmployees($today);

        $stats = $this->apiStatistics()->getData(true)['data'];

        $attendances = Absensi::with('user')
            ->whereNotIn('status', ['Tidak Masuk', 'Cuti', 'Sakit', 'Izin'])
            ->latest('tanggal')
            ->get();

        $ketidakhadiran = Absensi::with('user')
            ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Tidak Masuk'])
            ->latest('tanggal')
            ->get();

        $users = User::where('role', 'karyawan')->get();

        return view('general_manajer.kelola_absen', compact('stats', 'users'));
    }

    /* =====================================================
     |  MANAGER DIVISI
     ===================================================== */
public function absenManager()
{
    $user = Auth::user();

    // Query dasar
    $query = Absensi::with('user')
        ->whereHas('user', fn ($q) => $q->where('divisi', $user->divisi));

    // Statistik (pakai collection, tidak masalah)
    $allAbsensis = $query->get();

    $stats = [
        'total_tepat_waktu' => $allAbsensis->where('status', 'Tepat Waktu')->count(),
        'total_terlambat'   => $allAbsensis->where('status', 'Terlambat')->count(),
        'total_tidak_masuk' => $allAbsensis->where('status', 'Tidak Masuk')->count(),
        'total_cuti'        => $allAbsensis->where('status', 'Cuti')->count(),
        'total_sakit'       => $allAbsensis->where('status', 'Sakit')->count(),
        'total_izin'        => $allAbsensis->where('status', 'Izin')->count(),
        'total_dinas_luar'  => $allAbsensis->where('status', 'Dinas Luar')->count(),
    ];

    // ❗ Pagination KHUSUS tabel
    $ketidakhadiran = Absensi::with('user')
        ->whereHas('user', fn ($q) => $q->where('divisi', $user->divisi))
        ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Tidak Masuk'])
        ->latest('tanggal')
        ->paginate(10);

    return view(
        'manager_divisi.kelola_absensi',
        compact('stats', 'ketidakhadiran', 'allAbsensis')
    );
}

    /* =====================================================
     |  PEMILIK
     ===================================================== */
    public function rekapAbsensi()
    {
        $stats = $this->apiStatistics()->getData(true)['data'];

        $attendances = Absensi::with('user')
            ->whereNotIn('status', ['Tidak Masuk', 'Cuti', 'Sakit', 'Izin'])
            ->latest('tanggal')
            ->get();

        $ketidakhadiran = Absensi::with('user')
            ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Tidak Masuk'])
            ->latest('tanggal')
            ->get();

        $users = User::where('role', 'karyawan')->get();

        return view('pemilik.rekap_absensi', compact(
            'stats', 'attendances', 'ketidakhadiran', 'users'
        ));
    }

    /* =====================================================
     |  AUTO TANDAI TIDAK MASUK
     ===================================================== */
private function markAbsentEmployees($date)
{
    $employees = User::where('role', 'karyawan')->get(); // ⬅ ambil object User
    $hadir = Absensi::whereDate('tanggal', $date)->pluck('user_id');

    $tidakHadir = $employees->whereNotIn('id', $hadir);

    foreach ($tidakHadir as $user) {
        Absensi::firstOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => $date,
                'status'  => 'Tidak Masuk'
            ],
            [
                'name' => $user->name, // ✅ SEKARANG VALID
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]
        );
    }
}


    /* =====================================================
     |  API
     ===================================================== */
    public function apiIndex(Request $request)
    {
        $query = Absensi::with('user');

        if ($request->search) {
            $query->whereHas('user', fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            );
        }

        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'success' => true,
            'data' => $query->latest('tanggal')->get()
        ]);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'status'  => 'required|string',
            'jam_masuk' => 'nullable',
            'jam_pulang' => 'nullable',
        ]);

        $data['approval_status'] = 'approved';

        Absensi::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil ditambahkan'
        ]);
    }

    public function apiDestroy($id)
    {
        Absensi::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil dihapus'
        ]);
    }

public function apiStatistics(Request $request = null)
{
    try {
        $today = Carbon::today()->format('Y-m-d');

        $startDate = $request?->get('tanggal_mulai') ?? $today;
        $endDate   = $request?->get('tanggal_akhir') ?? $today;
        $divisi    = $request?->get('divisi');

        $query = Absensi::whereBetween('tanggal', [$startDate, $endDate]);

        if ($divisi) {
            $query->whereHas('user', fn ($q) =>
                $q->where('divisi', $divisi)
            );
        }

        $stats = [
            'total_tepat_waktu' => (clone $query)->where('status', 'Tepat Waktu')->count(),
            'total_terlambat'   => (clone $query)->where('status', 'Terlambat')->count(),
            'total_tidak_masuk' => (clone $query)->where('status', 'Tidak Masuk')->count(),
            'total_cuti'        => (clone $query)->where('status', 'Cuti')->count(),
            'total_sakit'       => (clone $query)->where('status', 'Sakit')->count(),
            'total_izin'        => (clone $query)->where('status', 'Izin')->count(),
            'total_dinas_luar'        => (clone $query)->where('status', 'Dinas Luar')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
