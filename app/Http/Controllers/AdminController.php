<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use App\Models\Layanan;
use App\Models\CatatanRapat;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman home admin.
     */
    public function home()
    {
        // Definisikan semua URL yang dibutuhkan oleh JavaScript
        $urls = [
            'data_karyawan' => route('admin.data_karyawan'),
            'keuangan' => route('admin.keuangan.index'),
            'absensi' => route('admin.absensi.index'),
        ];
        $jumlahKaryawan = Karyawan::count();
        $jumlahUser = User::count();
        $jumlahLayanan = Layanan::count();
        $catatanRapat = CatatanRapat::with(['peserta', 'penugasan'])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();
        $pengumumanTerbaru = Pengumuman::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

                $today = Carbon::today();

    $events = CatatanRapat::select('id', 'tanggal', 'keputusan')
        ->whereNotNull('keputusan')
        ->where('keputusan', '!=', '')
        ->get()
        ->groupBy(fn ($item) =>
            Carbon::parse($item->tanggal)->format('Y-m-d')
        );
        return view('admin.home', compact(
            'urls',
            'jumlahKaryawan',
            'jumlahUser',
            'jumlahLayanan',
            'catatanRapat',
            'pengumumanTerbaru',
            'events',
            'today'

        ));
    }

    public function dataKaryawan()
    {
        return view('admin.data_karyawan');
    }
}
