<?php

namespace App\Http\Controllers;

use App\Models\Cashflow;
use App\Models\KategoriCashflow;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CashflowController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan halaman utama data keuangan.
     */
    public function index()
    {
        // Ambil semua data cashflow beserta relasi kategorinya, diurutkan dari yang terbaru
        $cashflowData = Cashflow::with('kategori')->orderBy('tanggal_transaksi', 'desc')->get();

        // Ambil semua kategori untuk dropdown
        $allKategori = KategoriCashflow::all();

        // Format data untuk dikirim ke JavaScript
        $formattedData = $cashflowData->map(function ($item) {
            return [
                'id' => $item->id, // Gunakan ID untuk identifikasi unik
                'nomor_transaksi' => $item->nomor_transaksi, // Tampilkan nomor transaksi
                'tanggal_transaksi' => $item->tanggal_transaksi->format('Y-m-d'),
                'nama_transaksi' => $item->nama_transaksi,
                'kategori' => $item->kategori ? $item->kategori->nama_kategori : 'Tidak Diketahui',
                'deskripsi' => $item->deskripsi,
                'jumlah' => $item->jumlah, // Kirim sebagai angka, format di JavaScript
                'tipe_transaksi' => $item->tipe_transaksi,
            ];
        });

        // Kirim data yang sudah diformat ke view
        return view('finance.pemasukan', [
            'financeData' => $formattedData,
            'allKategori' => $allKategori
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan transaksi baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data dari form
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:income,expense',
            'kategori_id' => 'required|exists:kategori_cashflow,id', // Pastikan kategori ada di DB
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        // Siapkan data untuk disimpan, sesuaikan nama field form dengan nama kolom DB
        $dataToStore = [
            'tanggal_transaksi' => $validated['tanggal'],
            'tipe_transaksi' => $validated['tipe'] === 'income' ? 'pemasukan' : 'pengeluaran',
            'kategori_id' => $validated['kategori_id'],
            'nama_transaksi' => $validated['nama'],
            'jumlah' => $validated['jumlah'],
            'deskripsi' => $validated['deskripsi'],
            // 'nomor_transaksi' akan diisi otomatis oleh model event
        ];

        // Simpan ke database
        Cashflow::create($dataToStore);

        // Redirect kembali ke halaman utama dengan pesan sukses
        return redirect()->back()
                         ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    /**
     * API Endpoint untuk mendapatkan kategori berdasarkan tipe.
     * Digunakan oleh JavaScript untuk mengisi dropdown kategori secara dinamis.
     */
    public function getKategoriByType($tipe)
    {
        // Mapping dari tipe di URL ('pemasukan'/'pengeluaran') ke tipe di database
        $tipeDatabase = $tipe;

        $kategoris = KategoriCashflow::where('tipe_kategori', $tipeDatabase)->get();

        // Kembalikan dalam format JSON
        return response()->json($kategoris);
    }
}
