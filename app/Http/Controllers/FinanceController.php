<?php

namespace App\Http\Controllers;

use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FinanceController extends Controller
{
    /**
     * Menampilkan halaman dan mengirim data ke View
     */
    public function index()
    {
        // Ambil semua data dari database
        $transactions = FinanceTransaction::orderBy('tanggal', 'desc')->get();

        // Format data agar sama persis dengan struktur 'financeData' di JavaScript Anda
        // Ini penting agar JavaScript (filter, tabel, pagination) bisa membacanya
        $formattedData = $transactions->map(function ($item) {
            return [
                'no' => $item->id,
                'tanggal' => $item->tanggal->format('Y-m-d'),
                'nama' => $item->nama,
                'kategori' => $item->kategori,
                'deskripsi' => $item->deskripsi,
                'jumlah' => 'Rp ' . number_format($item->jumlah, 0, ',', '.'), // Format Rupiah string
                'tipe' => $item->tipe,
            ];
        });

        // Kirim data ke view dengan nama variabel '$financeData'
        return view('finance.pemasukan', ['financeData' => $formattedData]);
    }

    /**
     * Menyimpan data transaksi baru (POST Request)
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:income,expense',
            'kategori' => 'required',
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        // Simpan
        FinanceTransaction::create([
            'tanggal' => $request->tanggal,
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'nama' => $request->nama,
            'jumlah' => $request->jumlah,
            'deskripsi' => $request->deskripsi,
        ]);

        // Redirect kembali ke halaman pemasukan dengan pesan sukses
        return redirect()->to('/pemasukan')->with('success', 'Data berhasil disimpan!');
    }
}