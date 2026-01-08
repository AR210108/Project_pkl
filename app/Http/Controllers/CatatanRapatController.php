<?php

namespace App\Http\Controllers;

use App\Models\CatatanRapat;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CatatanRapatController extends Controller
{
    public function index(): View
    {
        $catatanRapat = CatatanRapat::orderBy('tanggal', 'desc')->paginate(10);
    return view('admin.catatan_rapat', compact('catatanRapat'));
    }


public function data(): JsonResponse
{
    $catatanRapat = CatatanRapat::orderBy('tanggal', 'desc')->paginate(10);

    return response()->json($catatanRapat);
}


    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'peserta' => 'required|string|max:255',
            'topik' => 'required|string|max:255',
            'hasil_diskusi' => 'required|string',
            'keputusan' => 'required|string',
            'penugasan' => 'required|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        CatatanRapat::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Catatan rapat berhasil ditambahkan!'
        ]);
    }

    public function update(Request $request, CatatanRapat $catatanRapat): JsonResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'peserta' => 'required|string|max:255',
            'topik' => 'required|string|max:255',
            'hasil_diskusi' => 'required|string',
            'keputusan' => 'required|string',
            'penugasan' => 'required|string|max:255',
        ]);

        $catatanRapat->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Catatan rapat berhasil diperbarui!'
        ]);
    }

    public function destroy(CatatanRapat $catatanRapat): JsonResponse
    {
        $catatanRapat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan rapat berhasil dihapus!'
        ]);
    }
}
