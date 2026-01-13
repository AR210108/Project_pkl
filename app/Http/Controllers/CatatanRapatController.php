<?php

namespace App\Http\Controllers;

use App\Models\CatatanRapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CatatanRapatController extends Controller
{
    /**
     * Halaman utama catatan rapat
     */
    public function index(): View
    {
        $users = User::all();
        return view('admin.catatan_rapat', compact('users'));
    }

    /**
     * Form untuk membuat catatan rapat baru
     */
    public function create(): View
    {
        $users = User::all();
        return view('catatan_rapat.create', compact('users'));
    }

    /**
     * Form untuk mengedit catatan rapat
     */
    public function edit($id): View
    {
        $catatanRapat = CatatanRapat::with(['peserta', 'penugasan'])->findOrFail($id);
        $users = User::all();
        return view('catatan_rapat.edit', compact('catatanRapat', 'users'));
    }

    /**
     * API endpoint untuk mendapatkan data catatan rapat
     */
    public function getData(): JsonResponse
    {
        try {
            $catatanRapat = CatatanRapat::with([
                    'peserta:id,name,email',
                    'penugasan:id,name,email',
                    'user:id,name'
                ])
                ->orderBy('tanggal', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $catatanRapat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simpan catatan rapat baru
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'topik' => 'required|string|max:255',
                'hasil_diskusi' => 'required|string',
                'keputusan' => 'required|string',
                'peserta' => 'required|array|min:1',
                'peserta.*' => 'exists:users,id',
                'penugasan' => 'required|array|min:1',
                'penugasan.*' => 'exists:users,id',
            ]);

            $catatan = CatatanRapat::create([
                'user_id' => auth()->id(),
                'tanggal' => $validated['tanggal'],
                'topik' => $validated['topik'],
                'hasil_diskusi' => $validated['hasil_diskusi'],
                'keputusan' => $validated['keputusan'],
            ]);

            // Simpan relasi many-to-many
            $catatan->peserta()->sync($validated['peserta']);
            $catatan->penugasan()->sync($validated['penugasan']);

            // Load relations untuk response
            $catatan->load(['peserta:id,name', 'penugasan:id,name']);

            return response()->json([
                'success' => true,
                'message' => 'Catatan rapat berhasil ditambahkan',
                'data' => $catatan
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
     * Tampilkan detail catatan rapat
     */
    public function show($id): JsonResponse
    {
        try {
            $catatanRapat = CatatanRapat::with([
                    'peserta:id,name,email',
                    'penugasan:id,name,email',
                    'user:id,name'
                ])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $catatanRapat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update catatan rapat
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $catatanRapat = CatatanRapat::findOrFail($id);

            $validated = $request->validate([
                'tanggal' => 'required|date',
                'topik' => 'required|string|max:255',
                'hasil_diskusi' => 'required|string',
                'keputusan' => 'required|string',
                'peserta' => 'required|array|min:1',
                'peserta.*' => 'exists:users,id',
                'penugasan' => 'required|array|min:1',
                'penugasan.*' => 'exists:users,id',
            ]);

            $catatanRapat->update([
                'tanggal' => $validated['tanggal'],
                'topik' => $validated['topik'],
                'hasil_diskusi' => $validated['hasil_diskusi'],
                'keputusan' => $validated['keputusan'],
            ]);

            // Update relasi many-to-many
            $catatanRapat->peserta()->sync($validated['peserta']);
            $catatanRapat->penugasan()->sync($validated['penugasan']);

            // Load relations untuk response
            $catatanRapat->load(['peserta:id,name', 'penugasan:id,name']);

            return response()->json([
                'success' => true,
                'message' => 'Catatan rapat berhasil diperbarui',
                'data' => $catatanRapat
            ]);
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
     * Hapus catatan rapat
     */
    public function destroy($id): JsonResponse
    {
        try {
            $catatanRapat = CatatanRapat::findOrFail($id);
            
            // Hapus relasi pivot terlebih dahulu
            $catatanRapat->peserta()->detach();
            $catatanRapat->penugasan()->detach();
            
            // Hapus catatan rapat
            $catatanRapat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Catatan rapat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}