<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function home()
    {
        return view('karyawan.home');
    }

    public function absensiPage()
    {
        $today = date('Y-m-d');
        $absen = Absensi::where('user_id', Auth::id())
                        ->where('tanggal', $today)
                        ->first();

        return view('karyawan.absen', compact('absen'));
    }

    public function absenMasuk()
    {
        $today = date('Y-m-d');

        $cek = Absensi::where('user_id', Auth::id())
                      ->where('tanggal', $today)
                      ->first();
        if ($cek) {
            return back()->with('msg', 'Kamu sudah absen masuk hari ini');
        }

        Absensi::create([
            'user_id' => Auth::id(),
            'tanggal' => $today,
            'jam_masuk' => now()->format('H:i:s')
        ]);

        return back()->with('msg', 'Absen masuk berhasil');
    }

    public function absenPulang()
    {
        $today = date('Y-m-d');

        $absen = Absensi::where('user_id', Auth::id())
                        ->where('tanggal', $today)
                        ->first();

        if (!$absen) {
            return back()->with('msg', 'Kamu belum absen masuk');
        }

        if ($absen->jam_pulang) {
            return back()->with('msg', 'Kamu sudah absen pulang');
        }

        $absen->update([
            'jam_pulang' => now()->format('H:i:s')
        ]);

        return back()->with('msg', 'Absen pulang berhasil');
    }
}
