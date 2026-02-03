<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LayananController extends Controller
{
    public function financeIndex()
    {
        // Ambil data dari database
        $layanans = Layanan::all();

        // Kirim data ke view baru
        return view('finance.data_layanan', compact('layanans'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Untuk API/AJAX request - kembalikan JSON
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            try {
                $query = Layanan::query();
                
                // Filter jika ada search
                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_layanan', 'like', "%$search%")
                          ->orWhere('deskripsi', 'like', "%$search%");
                    });
                }
                
                // Order by
                $query->orderBy('nama_layanan', 'asc');
                
                // Jika hanya perlu data untuk dropdown (minimal)
                if ($request->filled('for_dropdown') && $request->for_dropdown == 'true') {
                    $layanans = $query->get(['id', 'nama_layanan', 'harga', 'deskripsi']);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Data layanan berhasil diambil',
                        'data' => $layanans
                    ]);
                }
                
                // Default dengan pagination
                $perPage = $request->input('per_page', 10);
                $layanans = $query->paginate($perPage);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Data layanan berhasil diambil',
                    'data' => $layanans->items(),
                    'pagination' => [
                        'total' => $layanans->total(),
                        'per_page' => $layanans->perPage(),
                        'current_page' => $layanans->currentPage(),
                        'last_page' => $layanans->lastPage(),
                        'from' => $layanans->firstItem(),
                        'to' => $layanans->lastItem()
                    ]
                ]);
            } catch (\Exception $e) {
                Log::error('API Layanan Index Error: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data layanan',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
        }
        
        // Untuk web request - kembalikan view
        $layanans = Layanan::latest()->get();
        return view('admin/data_layanan', compact('layanans'));
    }

    public function Generalindex()
    {
        $layanan = Layanan::latest()->get();
        return view('general_manajer/data_layanan', compact('layanan'));
    }

    /**
     * API endpoint khusus untuk dropdown invoice
     */
    public function getForInvoiceDropdown(Request $request)
    {
        try {
            $layanans = Layanan::orderBy('nama_layanan', 'asc')
                ->get(['id', 'nama_layanan', 'harga', 'deskripsi']);
            
            return response()->json([
                'success' => true,
                'message' => 'Data layanan untuk dropdown berhasil diambil',
                'data' => $layanans
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting layanan for dropdown: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data layanan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'nama_layanan' => 'required|string|max:255',
            'harga'        => 'nullable|numeric|min:0',
            'deskripsi'    => 'required|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Untuk web request
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 2. Proses data dan simpan
        try {
            $data = $request->only(['nama_layanan', 'harga', 'deskripsi']);

            // ✅ Pastikan harga tidak null
            $data['harga'] = $request->harga ?? 0;

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/layanan', $fotoName);
                $data['foto'] = 'layanan/' . $fotoName;
            }

            $layanan = Layanan::create($data);
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Layanan berhasil ditambahkan!',
                    'data' => $layanan
                ], 201);
            }
            
            // Untuk web request
            return redirect()->back()
                ->with('success', 'Layanan berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Error storing layanan: ' . $e->getMessage());
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            // Untuk web request
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. Cari layanan
        $layanan = Layanan::findOrFail($id);

        // 2. Validasi input
        $validator = Validator::make($request->all(), [
            'nama_layanan' => 'required|string|max:255',
            'harga'        => 'nullable|numeric|min:0',
            'deskripsi'    => 'required|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Untuk web request
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // 3. Proses data dan update
        try {
            // Mulai database transaction
            \DB::beginTransaction();
            
            $data = $request->only(['nama_layanan', 'harga', 'deskripsi']);
            
            // Pastikan harga tidak null
            $data['harga'] = $request->harga ?? 0;

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($layanan->foto) {
                    Storage::delete('public/' . $layanan->foto);
                }
                
                $foto = $request->file('foto');
                $fotoName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/layanan', $fotoName);
                $data['foto'] = 'layanan/' . $fotoName;
            }
            
            // Update layanan
            $layanan->update($data);
            
            // 4. Update semua project yang terkait (jika ada relasi)
            $updatedProjects = 0;
            if (method_exists($layanan, 'projects') && $layanan->projects()->exists()) {
                $updatedProjects = $layanan->projects()->update([
                    'nama' => $layanan->nama_layanan,
                    'deskripsi' => $layanan->deskripsi,
                    'harga' => $layanan->harga,
                ]);
            }
            
            // Commit transaction
            \DB::commit();
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Layanan berhasil diperbarui! ' . 
                               ($updatedProjects ? "{$updatedProjects} project terkait juga diperbarui." : ''),
                    'data' => $layanan,
                    'updated_projects_count' => $updatedProjects
                ]);
            }
            
            // Untuk web request
            $message = 'Layanan berhasil diperbarui!';
            if ($updatedProjects) {
                $message .= " {$updatedProjects} project terkait juga diperbarui.";
            }
            
            return redirect()->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            // Rollback transaction jika error
            \DB::rollBack();
            Log::error('Error updating layanan: ' . $e->getMessage());
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            // Untuk web request
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update hanya harga untuk finance
     */
    public function updateHarga(Request $request, $id)
    {
        // Validasi hanya harga
        $validator = Validator::make($request->all(), [
            'harga' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Untuk web request
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $layanan = Layanan::findOrFail($id);
            
            // Update hanya harga
            $layanan->update([
                'harga' => $request->harga
            ]);
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Harga layanan berhasil diperbarui!',
                    'data' => $layanan
                ]);
            }
            
            // Untuk web request
            return redirect()->back()
                ->with('success', 'Harga layanan berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Error updating harga layanan: ' . $e->getMessage());
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui harga.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            // Untuk web request
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            
            // Hapus foto dari storage jika ada
            if ($layanan->foto) {
                Storage::delete('public/' . $layanan->foto);
            }
            
            $layanan->delete();
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Layanan berhasil dihapus!'
                ]);
            }
            
            // Untuk web request
            return redirect()->back()
                ->with('success', 'Layanan berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting layanan: ' . $e->getMessage());
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            // Untuk web request
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method lainnya tidak berubah
    public function indexLayanan(Request $request)
    {
        $query = Layanan::query();
        
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        $pelayanan = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $search = $request->query('search');
        
        return view('general_manajer.data_layanan', compact('pelayanan', 'search'));
    }

    public function landingPage()
    {
        $layanans = Layanan::latest()->get(); 
        return view('home', compact('layanans'));
    }
    
    public function getCount()
    {
        try {
            $count = Layanan::count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'count' => $count
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting layanan count: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data layanan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get layanan by ID (API)
     */
    public function show($id, Request $request)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data layanan berhasil diambil',
                    'data' => $layanan
                ]);
            }
            
            // Untuk web request (jika ada view show)
            return view('admin.layanan_show', compact('layanan'));
            
        } catch (\Exception $e) {
            Log::error('Error showing layanan: ' . $e->getMessage());
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Layanan tidak ditemukan',
                    'error' => config('app.debug') ? $e->getMessage() : 'Not found'
                ], 404);
            }
            
            // Untuk web request
            return redirect()->back()
                ->with('error', 'Layanan tidak ditemukan: ' . $e->getMessage());
        }
    }
    public function financeApi()
{
    try {
        $layanan = Layanan::where('status', 'aktif')
            ->select('id', 'nama_layanan', 'harga', 'deskripsi', 'created_at')
            ->orderBy('nama_layanan')
            ->get();
            
        return response()->json([
            'success' => true,
            'message' => 'Data layanan berhasil diambil',
            'data' => $layanan
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching finance layanan: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data layanan',
            'error' => $e->getMessage()
        ], 500);
    }
}
}