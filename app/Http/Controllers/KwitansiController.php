<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kwitansi;
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

    return view('admin.kwitansi', compact('kwitansis'));
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
    public function store(Request $request): JsonResponse
    {
        // Validate the request
        $validated = $request->validate([
            'invoice_id' => 'nullable|exists:invoices,id',
            'nama_perusahaan' => 'required|string|max:255',
            'nomor_order' => 'required|string|unique:kwitansis,nomor_order',
            'tanggal' => 'required|date',
            'nama_klien' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'sub_total' => 'nullable|numeric|min:0',
            'fee_maintenance' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'status' => 'required|in:Pembayawan Awal,Lunas',
        ]);

        try {
            // Create new kwitansi
            $kwitansi = Kwitansi::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kwitansi berhasil dibuat!',
                'data' => $kwitansi
            ], 201); // 201 Created
        } catch (QueryException $e) {
            // Handle database errors (constraint violations, etc.)
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database. Silakan coba lagi.',
                'error' => $e->getMessage()
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
            'status' => 'required|in:Pembayawan Awal,Lunas',
        ]);

        try {
            // Update kwitansi
            $kwitansi->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kwitansi berhasil diperbarui!',
                'data' => $kwitansi
            ]);
        } catch (QueryException $e) {
            // Handle database errors
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database. Silakan coba lagi.',
                'error' => $e->getMessage()
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
    public function cetak($id)
    {
        $kwitansi = Kwitansi::findOrFail($id);

        // Format tanggal
        $tanggal = \Carbon\Carbon::parse($kwitansi->tanggal)->locale('id')->isoFormat('DD/MM/YY');
        $tanggalLengkap = \Carbon\Carbon::parse($kwitansi->tanggal)->locale('id')->isoFormat('DD MMMM YYYY');

        return view('admin.kwitansi_cetak', compact('kwitansi', 'tanggal', 'tanggalLengkap'));
    }
}