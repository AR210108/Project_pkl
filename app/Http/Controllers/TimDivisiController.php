<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimDivisiController extends Controller
{
    /**
     * Display the management dashboard.
     */
    public function index()
    {
        // Hitung statistik dari database
        $totalTim = Tim::count();
        $totalDivisi = Divisi::count();
        
        // Untuk tim aktif, asumsikan semua tim aktif
        $timAktif = $totalTim;
        
        // Hitung total anggota (konversi string ke integer)
        $totalAnggota = Tim::sum(DB::raw('CAST(jumlah_anggota AS SIGNED)'));
        
        // Ambil data dengan pagination
        $tims = Tim::latest()->paginate(5);
        $divisis = Divisi::latest()->paginate(5);
        
        return view('general_manajer.tim_dan_divisi', compact(
            'totalTim', 'totalDivisi', 'timAktif', 'totalAnggota', 'tims', 'divisis'
        ));
    }

    /**
     * Store a newly created tim.
     */
/**
 * Store a newly created tim.
 */
public function storeTim(Request $request)
{
    try {
        // Debug: Log request data
        \Log::info('Store Tim Request:', $request->all());
        
        $validated = $request->validate([
            'tim' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'jumlah_anggota' => 'required|string|max:10'
        ]);

        \Log::info('Validated data:', $validated);

        // Cek apakah divisi ada
        $divisiExists = Divisi::where('divisi', $validated['divisi'])->exists();
        if (!$divisiExists) {
            return response()->json([
                'success' => false,
                'message' => 'Divisi tidak ditemukan. Silakan pilih divisi yang tersedia.'
            ], 422);
        }

        $tim = Tim::create($validated);
        
        \Log::info('Tim created:', $tim->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Tim berhasil ditambahkan',
            'data' => $tim
        ], 201);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation error:', $e->errors());
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Store tim error:', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Update the specified tim.
     */
    public function updateTim(Request $request, $id)
    {
        $validated = $request->validate([
            'tim' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'jumlah_anggota' => 'required|string|max:10'
        ]);

        $tim = Tim::findOrFail($id);
        $tim->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Tim berhasil diperbarui',
            'data' => $tim
        ]);
    }

    /**
     * Remove the specified tim.
     */
    public function destroyTim($id)
    {
        $tim = Tim::findOrFail($id);
        $tim->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Tim berhasil dihapus'
        ]);
    }

    /**
     * Store a newly created divisi.
     */
    public function storeDivisi(Request $request)
    {
        $validated = $request->validate([
            'divisi' => 'required|string|max:255|unique:divisi'
        ]);

        // Jumlah tim akan di-set otomatis oleh model boot method
        $divisi = Divisi::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Divisi berhasil ditambahkan',
            'data' => $divisi
        ]);
    }

    /**
     * Update the specified divisi.
     */
    public function updateDivisi(Request $request, $id)
    {
        $validated = $request->validate([
            'divisi' => 'required|string|max:255|unique:divisi,divisi,' . $id
        ]);

        $divisi = Divisi::findOrFail($id);
        $oldNamaDivisi = $divisi->divisi;
        
        $divisi->update($validated);
        
        // Update nama divisi di semua tim yang terkait
        if ($oldNamaDivisi != $validated['divisi']) {
            Tim::where('divisi', $oldNamaDivisi)
                ->update(['divisi' => $validated['divisi']]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Divisi berhasil diperbarui',
            'data' => $divisi
        ]);
    }

    /**
     * Remove the specified divisi.
     */
    public function destroyDivisi($id)
    {
        $divisi = Divisi::findOrFail($id);
        
        // Check if divisi has tims
        if (Tim::where('divisi', $divisi->divisi)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus divisi yang memiliki tim'
            ], 400);
        }
        
        $divisi->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Divisi berhasil dihapus'
        ]);
    }

    /**
     * Search tim.
     */
    public function searchTim(Request $request)
    {
        $search = $request->get('search');
        
        $tims = Tim::where('tim', 'like', "%{$search}%")
            ->orWhere('divisi', 'like', "%{$search}%")
            ->latest()
            ->paginate(5);
            
        return response()->json([
            'success' => true,
            'data' => $tims
        ]);
    }

    /**
     * Search divisi.
     */
    public function searchDivisi(Request $request)
    {
        $search = $request->get('search');
        
        $divisis = Divisi::where('divisi', 'like', "%{$search}%")
            ->latest()
            ->paginate(5);
            
        return response()->json([
            'success' => true,
            'data' => $divisis
        ]);
    }

    /**
     * Get all divisis for dropdown.
     */
    public function getDivisis()
    {
        $divisis = Divisi::all(['id', 'divisi']);
        
        return response()->json([
            'success' => true,
            'data' => $divisis
        ]);
    }
}