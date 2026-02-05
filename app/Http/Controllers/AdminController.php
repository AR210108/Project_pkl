<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Layanan;
use App\Models\CatatanRapat;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Project;
use App\Models\Task;

class AdminController extends Controller
{
    public function beranda()
    {
        // Stats
        $jumlahKaryawan = User::where('role', 'karyawan')->count();
        $jumlahUser = User::count();
        $jumlahLayanan = Layanan::count();
        $jumlahProject = Project::count();
        
        // Meeting notes
        $catatanRapat = CatatanRapat::with(['peserta', 'penugasan'])
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();
        
        // Announcements
        $pengumumanTerbaru = Pengumuman::with('users')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Calendar events
        $today = Carbon::today();
        $events = CatatanRapat::with('peserta')
            ->whereBetween('tanggal', [
                $today->copy()->subDays(30),
                $today->copy()->addDays(30)
            ])
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            })
            ->map(function($items) {
                return $items->map(function($item) {
                    return [
                        'keputusan' => $item->keputusan,
                        'topik' => $item->topik
                    ];
                });
            });
        
        return view('admin.home', compact(
            'jumlahKaryawan',
            'jumlahUser',
            'jumlahLayanan',
            'jumlahProject',
            'catatanRapat',
            'pengumumanTerbaru',
            'events',
            'today'
        ));
    }
}