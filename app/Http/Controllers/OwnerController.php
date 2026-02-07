<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    /**
     * Mendapatkan data owner yang sedang login
     */
    public function getData()
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'id' => $user->id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data owner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan laporan umum (dengan data pemasukan dan keuangan)
     */
    public function laporan(Request $request)
    {
        // Ambil data Cashflow untuk statistik keseluruhan
        $totalPemasukan = \App\Models\Cashflow::where('tipe_transaksi', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = \App\Models\Cashflow::where('tipe_transaksi', 'pengeluaran')->sum('jumlah');
        $totalKeuntungan = $totalPemasukan - $totalPengeluaran;

        // Ambil semua data finance (Cashflow) untuk tabel data keuangan
        $financeDataRaw = \App\Models\Cashflow::with('kategori')->orderBy('tanggal_transaksi', 'desc')->get();
        $financeData = $financeDataRaw->map(function($item) {
            $item->tanggal_transaksi = $item->tanggal_transaksi->format('Y-m-d');
            // Set kategori dari relasi jika ada, atau gunakan subkategori
            if ($item->kategori) {
                $item->kategori = $item->kategori->nama_kategori;
            } else {
                $item->kategori = $item->subkategori ?? 'Lainnya';
            }
            return $item;
        });

        // Ambil data pemasukan dari FinanceTransaction untuk bulan ini
        $bulanIni = \Carbon\Carbon::now();
        $pemasukan = \App\Models\FinanceTransaction::where('tipe', 'income')
            ->whereYear('tanggal', $bulanIni->year)
            ->whereMonth('tanggal', $bulanIni->month)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Hitung statistik pemasukan bulan ini
        $totalPemasukanBulanIni = $pemasukan->sum('jumlah');
        $jumlahTransaksiBulanIni = $pemasukan->count();

        // Pemasukan per kategori
        $pemasukanPerKategori = $pemasukan->groupBy('kategori')->map(function($items) {
            return [
                'total' => $items->sum('jumlah'),
                'jumlah' => $items->count()
            ];
        })->sortByDesc('total');

        // Ambil daftar kategori unik dari financeDataRaw (sebelum mapping)
        $financeCategories = $financeDataRaw->pluck('kategori')->unique()->values();
        
        // Buat array kategori dengan format yang sama seperti KategoriCashflow
        $allKategori = $financeCategories->map(function($kategori) {
            return (object)['nama_kategori' => $kategori];
        });

        return view('pemilik.laporan', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'totalKeuntungan',
            'financeData',
            'pemasukan',
            'totalPemasukanBulanIni',
            'jumlahTransaksiBulanIni',
            'pemasukanPerKategori',
            'allKategori'
        ));
    }

    /**
     * Menampilkan laporan pemasukan
     */
    public function laporanPemasukan(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->get('tanggal_akhir', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
        $kategoriFilter = $request->get('kategori', '');

        \Log::info("Laporan Pemasukan - tanggalMulai: $tanggalMulai, tanggalAkhir: $tanggalAkhir, kategori: $kategoriFilter");

        // Query pemasukan dari finance transactions
        $pemasukan = \App\Models\FinanceTransaction::where('tipe', 'income')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);

        // Ambil daftar kategori unik
        $kategoriList = \App\Models\FinanceTransaction::where('tipe', 'income')
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (!empty($kategoriFilter)) {
            $pemasukan->where('kategori', $kategoriFilter);
        }

        $pemasukan = $pemasukan->orderBy('tanggal', 'desc')->get();

        // Hitung statistik
        $totalPemasukan = $pemasukan->sum('jumlah');
        $jumlahTransaksi = $pemasukan->count();
        $rataRataPemasukan = $jumlahTransaksi > 0 ? $totalPemasukan / $jumlahTransaksi : 0;

        // Kelompokkan berdasarkan kategori
        $pemasukanPerKategori = $pemasukan->groupBy('kategori')->map(function($items) {
            return [
                'total' => $items->sum('jumlah'),
                'jumlah' => $items->count()
            ];
        });

        \Log::info("Laporan Pemasukan - Total: $totalPemasukan, Transaksi: $jumlahTransaksi");

        return view('pemilik.laporan_pemasukan', compact(
            'pemasukan', 
            'kategoriList', 
            'kategoriFilter', 
            'tanggalMulai', 
            'tanggalAkhir',
            'totalPemasukan',
            'jumlahTransaksi',
            'rataRataPemasukan',
            'pemasukanPerKategori'
        ));
    }
}