<?php

namespace App\Http\Controllers;

use App\Models\CatatanRapat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CatatanRapatController extends Controller
{
    /**
     * Halaman utama
     */
    public function index(): View
    {
        return view('admin.catatan_rapat');
    }

    /**
     * Data untuk AJAX
     */
    public function data(): JsonResponse
    {
        $catatanRapat = CatatanRapat::with([
                'peserta:id,name',
                'penugasan:id,name'
            ])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return response()->json($catatanRapat);
    }

    /**
     * Simpan data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'topik' => 'required|string|max:255',
            'hasil_diskusi' => 'required|string',
            'keputusan' => 'required|string',
            'peserta' => 'required|array',
            'peserta.*' => 'exists:users,id',
            'penugasan' => 'required|array',
            'penugasan.*' => 'exists:users,id',
        ]);

        $catatan = CatatanRapat::create([
            'user_id' => auth()->id(),
            'tanggal' => $validated['tanggal'],
            'topik' => $validated['topik'],
            'hasil_diskusi' => $validated['hasil_diskusi'],
            'keputusan' => $validated['keputusan'],
        ]);

        // ✅ SIMPAN KE PIVOT
        $catatan->peserta()->sync($validated['peserta']);
        $catatan->penugasan()->sync($validated['penugasan']);

        return response()->json([
            'success' => true,
            'message' => 'Catatan rapat berhasil ditambahkan'
        ]);
    }

    /**
     * Update data
     */
    public function update(Request $request, CatatanRapat $catatanRapat): JsonResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'topik' => 'required|string|max:255',
            'hasil_diskusi' => 'required|string',
            'keputusan' => 'required|string',
            'peserta' => 'required|array',
            'peserta.*' => 'exists:users,id',
            'penugasan' => 'required|array',
            'penugasan.*' => 'exists:users,id',
        ]);

        $catatanRapat->update([
            'tanggal' => $validated['tanggal'],
            'topik' => $validated['topik'],
            'hasil_diskusi' => $validated['hasil_diskusi'],
            'keputusan' => $validated['keputusan'],
        ]);

        // ✅ UPDATE PIVOT
        $catatanRapat->peserta()->sync($validated['peserta']);
        $catatanRapat->penugasan()->sync($validated['penugasan']);

        return response()->json([
            'success' => true,
            'message' => 'Catatan rapat berhasil diperbarui'
        ]);
    }

    /**
     * Hapus data
     */
    public function destroy(CatatanRapat $catatanRapat): JsonResponse
    {
        $catatanRapat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan rapat berhasil dihapus'
        ]);
    }
}
