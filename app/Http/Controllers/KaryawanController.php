<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class KaryawanController extends Controller
{
    public function home()
    {
        return view('karyawan.home');
    }

    public function absensiPage()
    {
        return view('karyawan.absen');
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
            ]);
        }

        // Karena database menyimpan waktu lokal, kita kirim apa adanya
        return response()->json([
            'jam_masuk' => $absen->jam_masuk,
            'jam_pulang' => $absen->jam_pulang,
            'status' => $absen->status,
            'status_type' => $absen->status_type,
            'late_minutes' => $absen->late_minutes,
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
                                  'date' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                                  // Karena database menyimpan waktu lokal, kita kirim apa adanya
                                  'checkIn' => $item->jam_masuk,
                                  'checkOut' => $item->jam_pulang,
                                  'status' => $item->status,
                                  'statusType' => $item->status_type,
                                  'lateMinutes' => $item->late_minutes,
                              ];
                          })
                          ->all();

        return response()->json($history);
    }

    /**
     * Proses absen masuk via AJAX.
     */
    public function absenMasukApi(Request $request)
    {
        $today = now()->toDateString();
        
        // Ambil waktu lokal aplikasi
        $nowLocal = now();

        $cek = Absensi::where('user_id', Auth::id())->where('tanggal', $today)->first();
        if ($cek && $cek->jam_masuk) {
            return response()->json(['message' => 'Kamu sudah absen masuk hari ini'], 409);
        }

        // Hitung keterlambatan berdasarkan waktu lokal
        $workStartTime = $nowLocal->copy()->setTime(9, 0, 0);
        
        // PERBAIKAN: Hitung selisih dari jam mulai kerja (09:00) ke waktu sekarang
        $lateMinutes = $nowLocal->greaterThan($workStartTime) ? $workStartTime->diffInMinutes($nowLocal) : 0;
        
        $status = $lateMinutes > 0 ? 'Terlambat' : 'Tepat Waktu';
        $statusType = $lateMinutes > 0 ? 'late' : 'on-time';

        Absensi::updateOrCreate(
            ['user_id' => Auth::id(), 'tanggal' => $today],
            [
                // Simpan waktu lokal
                'jam_masuk' => $nowLocal,
                'status' => $status,
                'status_type' => $statusType,
                'late_minutes' => $lateMinutes,
            ]
        );

        return response()->json([
            'message' => 'Absen masuk berhasil!',
            'data' => [
                // Kirim waktu lokal
                'time' => $nowLocal->toDateTimeString(),
                'status' => $status,
                'late_minutes' => $lateMinutes,
            ]
        ]);
    }

    /**
     * Proses absen pulang via AJAX.
     */
    public function absenPulangApi(Request $request)
    {
        $today = now()->toDateString();
        
        // Ambil waktu lokal aplikasi
        $nowLocal = now();

        $absen = Absensi::where('user_id', Auth::id())->where('tanggal', $today)->first();

        if (!$absen || !$absen->jam_masuk) {
            return response()->json(['message' => 'Kamu belum melakukan absen masuk hari ini.'], 400);
        }

        if ($absen->jam_pulang) {
            return response()->json(['message' => 'Kamu sudah absen pulang'], 409);
        }

        // Update dengan waktu lokal
        $absen->update(['jam_pulang' => $nowLocal]);

        return response()->json([
            'message' => 'Absen pulang berhasil!',
            'data' => [
                // Kirim waktu lokal
                'time' => $nowLocal->toDateTimeString(),
            ]
        ]);
    }

    /**
     * Proses pengajuan izin (bisa multi-hari).
     */
    public function submitIzinApi(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $period = CarbonPeriod::create($request->start_date, $request->end_date);

        foreach ($period as $date) {
            Absensi::updateOrCreate(
                ['user_id' => Auth::id(), 'tanggal' => $date->toDateString()],
                [
                    'status' => $request->type,
                    'status_type' => 'absent',
                    'reason' => $request->reason,
                ]
            );
        }

        return response()->json(['message' => 'Pengajuan izin untuk ' . $period->count() . ' hari berhasil dikirim!']);
    }

    /**
     * Proses pengajuan dinas luar (bisa multi-hari).
     */
    public function submitDinasApi(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string',
            'purpose' => 'required|string',
            'description' => 'required|string',
        ]);

        $period = CarbonPeriod::create($request->start_date, $request->end_date);

        foreach ($period as $date) {
            Absensi::updateOrCreate(
                ['user_id' => Auth::id(), 'tanggal' => $date->toDateString()],
                [
                    'status' => 'Dinas Luar',
                    'status_type' => 'absent',
                    'reason' => $request->description,
                    'location' => $request->location,
                    'purpose' => $request->purpose,
                ]
            );
        }

        return response()->json(['message' => 'Pengajuan dinas luar untuk ' . $period->count() . ' hari berhasil dikirim!']);
    }
}