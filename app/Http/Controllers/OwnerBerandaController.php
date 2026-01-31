<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cashflow;
use App\Models\Layanan;

class OwnerBerandaController extends Controller
{
    public function index() {
        // Ambil data keuangan lengkap
        $financeData = Cashflow::orderBy('tanggal_transaksi', 'desc')->get()->map(function($item) {
            $item->tanggal_transaksi = $item->tanggal_transaksi->format('Y-m-d');
            return $item;
        });

        // Hitung total pemasukan, pengeluaran, dll.
        $totalPemasukan = Cashflow::where('tipe_transaksi', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Cashflow::where('tipe_transaksi', 'pengeluaran')->sum('jumlah');
        $totalKeuntungan = $totalPemasukan - $totalPengeluaran;
        $jumlahLayanan = Layanan::count();

        return view('pemilik.home', compact('financeData', 'totalPemasukan', 'totalPengeluaran', 'totalKeuntungan', 'jumlahLayanan'));
    }
}
