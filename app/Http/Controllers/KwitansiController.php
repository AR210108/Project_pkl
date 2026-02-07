<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kwitansi;
use App\Models\Invoice; // TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class KwitansiController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Kwitansi::with('invoice')->latest();

            if ($request->has('search') && $request->search != '') {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('nama_klien', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('nomor_order', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('nama_perusahaan', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
                });
            }

            $kwitansis = $query->get();

            // Return view for web requests, JSON for API requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kwitansi berhasil diambil',
                    'data' => $kwitansis
                ], 200);
            }

            // Return HTML view for normal web requests
            return view('admin.kwitansi', [
                'kwitansis' => $kwitansis
            ]);
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()
                ], 500);
            }

            // Return error view for web requests
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Method untuk API (JSON response)
     */
    public function getKwitansiData(Request $request)
    {
        try {
            $query = Kwitansi::with('invoice')->latest();

            if ($request->has('search') && $request->search != '') {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('nama_klien', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('nomor_order', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('nama_perusahaan', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
                });
            }

            $kwitansis = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Data kwitansi berhasil diambil',
                'data' => $kwitansis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Finance index page
     */
    public function financeIndex(Request $request)
    {
        $query = Kwitansi::with('invoice')->latest();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_klien', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('nomor_order', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('nama_perusahaan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
            });
        }

        $kwitansis = $query->paginate(10);

        return view('finance.kwitansi', compact('kwitansis'));
    }

    /**
     * Display the specified resource.
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $kwitansi = Kwitansi::with('invoice')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $kwitansi
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kwitansi tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    // KwitansiController.php - store method
    public function store(Request $request): JsonResponse
    {
        try {
            // Generate kwitansi number
            $year = date('Y');
            $lastKwitansi = Kwitansi::orderBy('id', 'desc')->first();

            if ($lastKwitansi && $lastKwitansi->kwitansi_no) {
                $lastNumber = intval(substr($lastKwitansi->kwitansi_no, -5));
                $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $nextNumber = '00001';
            }

            $kwitansiNo = "KW-$year-$nextNumber";

            $validated = $request->validate([
                'invoice_id' => 'nullable|exists:invoices,id',
                'tanggal' => 'required|date',
                'nama_perusahaan' => 'required|string|max:255',
                'nomor_order' => 'required|string',
                'nama_klien' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'harga' => 'required|numeric|min:0',
                'sub_total' => 'required|numeric|min:0',
                'fee_maintenance' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'status' => 'required|in:Pembayaran Awal,Lunas',
                'bank' => 'nullable|string|max:100',
                'no_rekening' => 'nullable|string|max:50'
            ]);

            // Add kwitansi number to validated data
            $validated['kwitansi_no'] = $kwitansiNo;

            $kwitansi = Kwitansi::create($validated);

            // Update invoice status if needed
            if ($request->invoice_id) {
                $invoice = Invoice::find($request->invoice_id);
                if ($invoice && $request->status == 'Lunas') {
                    $invoice->status_pembayaran = 'lunas';
                    $invoice->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Kwitansi berhasil dibuat!',
                'data' => $kwitansi
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $kwitansi = Kwitansi::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kwitansi tidak ditemukan.'
            ], 404);
        }

        try {
            // Validate the request
            $validated = $request->validate([
                'invoice_id' => 'nullable|exists:invoices,id',
                'nama_perusahaan' => 'required|string|max:255',
                'nomor_order' => 'required|string|unique:kwitansis,nomor_order,' . $id,
                'tanggal' => 'required|date',
                'nama_klien' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'harga' => 'required|numeric|min:0',
                'sub_total' => 'nullable|numeric|min:0',
                'fee_maintenance' => 'nullable|numeric|min:0',
                'total' => 'nullable|numeric|min:0',
                'status' => 'required|in:Pembayaran Awal,Lunas', // PERBAIKI TYPO DI SINI
            ]);

            // Update kwitansi
            $kwitansi->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kwitansi berhasil diperbarui!',
                'data' => $kwitansi
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            // Handle database errors
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $kwitansi = Kwitansi::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kwitansi tidak ditemukan.'
            ], 404);
        }

        try {
            // Delete kwitansi
            $kwitansi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kwitansi berhasil dihapus!'
            ]);
        } catch (QueryException $e) {
            // Handle database errors (constraint violations, etc.)
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kwitansi. Mungkin masih terkait dengan data lain.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cetak kwitansi
     */
    /**
     * Get kwitansi data for printing (API)
     */
    public function getKwitansiForPrint($id)
    {
        try {
            $kwitansi = Kwitansi::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data kwitansi berhasil diambil',
                'data' => $kwitansi
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kwitansi tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cetak kwitansi (view)
     */
    public function cetak($id)
    {
        try {
            $kwitansi = Kwitansi::findOrFail($id);

            // Format tanggal
            $tanggal = \Carbon\Carbon::parse($kwitansi->tanggal)->locale('id')->isoFormat('DD/MM/YY');
            $tanggalLengkap = \Carbon\Carbon::parse($kwitansi->tanggal)->locale('id')->isoFormat('DD MMMM YYYY');

            return response()->json([
                'success' => true,
                'message' => 'Data kwitansi berhasil diambil',
                'data' => $kwitansi,
                'tanggal' => $tanggal,
                'tanggalLengkap' => $tanggalLengkap
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
