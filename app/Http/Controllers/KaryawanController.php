<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class KaryawanController extends Controller
{
    /**
     * Menampilkan halaman beranda karyawan.
     * REVISI: Metode ini menggunakan logika terbaru untuk 'Jumlah Ketidakhadiran' dan 'Jumlah Tugas'.
     */
    public function home()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        // 1. Ambil status absensi hari ini
        $absenToday = Absensi::where('user_id', $userId)
                            ->where('tanggal', $today)
                            ->first();

        $attendanceStatus = 'Belum Absen';
        if ($absenToday) {
            $attendanceStatus = $absenToday->status;
        }

        // PERUBAHAN 1: Hitung Jumlah Ketidakhadiran (Sakit, Izin, Cuti yang disetujui)
        $ketidakhadiranCount = Absensi::where('user_id', $userId)
                                ->where('approval_status', 'approved')
                                ->whereIn('status', ['Cuti', 'Sakit', 'Izin'])
                                ->count();

        // PERUBAHAN 2: Hitung Jumlah Tugas (Dinas Luar yang disetujui)
        $tugasCount = Absensi::where('user_id', $userId)
                        ->where('approval_status', 'approved')
                        ->where('status', 'Dinas Luar')
                        ->count();

        // Kirim data ke view dengan nama variabel baru
        return view('karyawan.home', [
            'attendance_status' => $attendanceStatus,
            'ketidakhadiran_count' => $ketidakhadiranCount,
            'tugas_count' => $tugasCount,
        ]);
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
                'approval_status' => 'approved',
            ]);
        }

        return response()->json([
            'jam_masuk' => $absen->jam_masuk,
            'jam_pulang' => $absen->jam_pulang,
            'status' => $absen->status,
            'status_type' => $absen->status_type,
            'late_minutes' => $absen->late_minutes,
            'approval_status' => $absen->approval_status,
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
                                  'checkIn' => $item->jam_masuk,
                                  'checkOut' => $item->jam_pulang,
                                  'status' => $item->status,
                                  'statusType' => $item->status_type,
                                  'lateMinutes' => $item->late_minutes,
                                  'isEarlyCheckout' => $item->is_early_checkout,
                                  'earlyCheckoutReason' => $item->early_checkout_reason,
                                  'approvalStatus' => $item->approval_status,
                                  'userName' => $item->name,
                              ];
                          })
                          ->all();

        return response()->json($history);
    }

    /**
     * Mengambil data untuk dashboard karyawan via API.
     * REVISI: Metode ini menggunakan logika terbaru untuk API.
     */
    public function getDashboardData()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        // 1. Ambil status absensi hari ini
        $absenToday = Absensi::where('user_id', $userId)
                            ->where('tanggal', $today)
                            ->first();

        $attendanceStatus = 'Belum Absen';
        if ($absenToday) {
            $attendanceStatus = $absenToday->status;
        }

        // PERUBAHAN 1: Hitung Jumlah Ketidakhadiran (Sakit, Izin, Cuti yang disetujui)
        $ketidakhadiranCount = Absensi::where('user_id', $userId)
                                ->where('approval_status', 'approved')
                                ->whereIn('status', ['Cuti', 'Sakit', 'Izin'])
                                ->count();

        // PERUBAHAN 2: Hitung Jumlah Tugas (Dinas Luar yang disetujui)
        $tugasCount = Absensi::where('user_id', $userId)
                        ->where('approval_status', 'approved')
                        ->where('status', 'Dinas Luar')
                        ->count();

        // Kembalikan response JSON dengan kunci baru
        return response()->json([
            'attendance_status' => $attendanceStatus,
            'ketidakhadiran_count' => $ketidakhadiranCount,
            'tugas_count' => $tugasCount,
        ]);
    }

    /**
     * Proses absen masuk via AJAX.
     */
    public function absenMasukApi(Request $request)
    {
        try {
            $today = now()->toDateString();
            
            $existingAbsence = Absensi::where('user_id', Auth::id())
                                      ->where('tanggal', $today)
                                      ->whereIn('status', ['Cuti', 'Sakit', 'Izin', 'Dinas Luar'])
                                      ->where('approval_status', 'approved')
                                      ->first();

            if ($existingAbsence) {
                return response()->json([
                    'message' => 'Anda tidak dapat melakukan absen masuk karena telah mengajukan "' . $existingAbsence->status . '" pada hari ini.'
                ], 403);
            }
            
            $nowLocal = now();
            $userName = Auth::user()->name;

            $cek = Absensi::where('user_id', Auth::id())->where('tanggal', $today)->first();
            if ($cek && $cek->jam_masuk) {
                return response()->json(['message' => 'Kamu sudah absen masuk hari ini'], 409);
            }

            $workStartTime = $nowLocal->copy()->setTime(9, 5, 0); 
            $lateMinutes = $nowLocal->greaterThan($workStartTime) ? $workStartTime->diffInMinutes($nowLocal) : 0;
            
            $status = $lateMinutes > 0 ? 'Terlambat' : 'Tepat Waktu';
            $statusType = $lateMinutes > 0 ? 'late' : 'on-time';

            Absensi::updateOrCreate(
                ['user_id' => Auth::id(), 'tanggal' => $today],
                [
                    'name' => $userName,
                    'jam_masuk' => $nowLocal,
                    'status' => $status,
                    'status_type' => $statusType,
                    'late_minutes' => $lateMinutes,
                    'approval_status' => 'approved',
                ]
            );

            return response()->json([
                'message' => 'Absen masuk berhasil!',
                'data' => [
                    'time' => $nowLocal->toDateTimeString(),
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server. Silakan coba lagi.'], 500);
        }
    }

    /**
     * Proses absen pulang via AJAX.
     */
    public function absenPulangApi(Request $request)
    {
        try {
            $today = now()->toDateString();
            $nowLocal = now();
            $userName = Auth::user()->name;

            $absen = Absensi::where('user_id', Auth::id())->where('tanggal', $today)->first();

            if (!$absen || !$absen->jam_masuk) {
                return response()->json(['message' => 'Anda belum melakukan absen masuk hari ini.'], 400);
            }

            if ($absen->jam_pulang) {
                return response()->json(['message' => 'Anda sudah absen pulang hari ini.'], 409);
            }

            $workEndTime = $nowLocal->copy()->setTime(17, 0, 0);
            $isEarlyCheckout = $nowLocal->lessThan($workEndTime);
            
            $reason = null;
            if ($isEarlyCheckout) {
                $request->validate([
                    'reason' => 'required|string|max:255',
                ]);
                $reason = $request->input('reason');
            }

            $absen->update([
                'name' => $userName,
                'jam_pulang' => $nowLocal,
                'is_early_checkout' => $isEarlyCheckout,
                'early_checkout_reason' => $reason,
                'approval_status' => 'approved',
            ]);

            return response()->json([
                'message' => 'Absen pulang berhasil!',
                'data' => [
                    'time' => $nowLocal->toDateTimeString(),
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server. Silakan coba lagi.'], 500);
        }
    }

    /**
     * Proses pengajuan izin (bisa multi-hari).
     */
    public function submitIzinApi(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $userName = Auth::user()->name;
        $period = CarbonPeriod::create($request->start_date, $request->end_date);

        DB::beginTransaction();
        try {
            foreach ($period as $date) {
                Absensi::updateOrCreate(
                    ['user_id' => Auth::id(), 'tanggal' => $date->toDateString()],
                    [
                        'name' => $userName,
                        'status' => $request->type,
                        'status_type' => 'absent',
                        'reason' => $request->reason,
                        'approval_status' => 'pending',
                    ]
                );
            }
            DB::commit();

            return response()->json(['message' => 'Pengajuan izin untuk ' . $period->count() . ' hari berhasil dikirim dan menunggu persetujuan admin.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal mengajukan izin. Terjadi kesalahan pada server.'], 500);
        }
    }

    /**
     * Proses pengajuan dinas luar (bisa multi-hari).
     */
    public function submitDinasApi(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string',
            'purpose' => 'required|string',
            'description' => 'required|string',
        ]);

        $userName = Auth::user()->name;
        $period = CarbonPeriod::create($request->start_date, $request->end_date);
        
        DB::beginTransaction();
        try {
            foreach ($period as $date) {
                Absensi::updateOrCreate(
                    ['user_id' => Auth::id(), 'tanggal' => $date->toDateString()],
                    [
                        'name' => $userName,
                        'status' => 'Dinas Luar',
                        'status_type' => 'absent',
                        'reason' => $request->description,
                        'location' => $request->location,
                        'purpose' => $request->purpose,
                        'approval_status' => 'pending',
                    ]
                );
            }
            DB::commit();

            return response()->json(['message' => 'Pengajuan dinas luar untuk ' . $period->count() . ' hari berhasil dikirim dan menunggu persetujuan admin.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal mengajukan dinas luar. Terjadi kesalahan pada server.'], 500);
        }
    }
}