<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan; // pastikan model Karyawan ada

class AdminKaryawanController extends Controller
{
    public function index()
    {
        // Ambil semua data karyawan dari tabel
        $karyawan = Karyawan::all();

        // Kirim data ke view
       return view('admin.data_karyawan', compact('karyawan'));
    }
}
