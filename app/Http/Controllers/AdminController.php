<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Task;

class AdminController extends Controller
{
    public function beranda()
    {
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalManager = User::where('role', 'general_manager')->count();
        $totalFinance = User::where('role', 'finance')->count();
        
        $absensiHariIni = Absensi::whereDate('created_at', today())->count();
        $tugasPending = Task::where('status', 'pending')->count();
        $tugasSelesai = Task::where('status', 'completed')->count();
        
        return view('admin.home', compact(
            'totalKaryawan',
            'totalAdmin',
            'totalManager',
            'totalFinance',
            'absensiHariIni',
            'tugasPending',
            'tugasSelesai'
        ));
    }
}