<?php

namespace App\Http\Controllers;

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

        // Kirim variabel $urls ke view
        return view('admin.home', compact('urls'));
    }
    
    public function dataKaryawan()
    {
        return view('admin.data_karyawan');
    }
}