<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class LayananController extends Controller
{
    public function financeIndex()
{
    // Ambil data dari database
    $layanans = Layanan::all();

    // Kirim data ke view
    return view('finance.data_layanan', compact('layanans'));
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layanans = Layanan::latest()->get();
        return view('admin/data_layanan', compact('layanans'));
    }

    public function Generalindex()
    {
        $layanan = Layanan::latest()->get();
        return view('general_manajer/data_layanan', compact('layanan'));
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
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422); // HTTP Status 422: Unprocessable Entity
        }

        // 2. Proses data dan simpan
try {
    $data = $request->only(['nama_layanan', 'harga', 'deskripsi']);

    // ✅ Tambahkan ini
    $data['harga'] = $request->harga ?? 0;

    // Handle foto upload
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        $fotoName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
        $foto->storeAs('public/layanan', $fotoName);
        $data['foto'] = 'layanan/' . $fotoName;
    }

    $layanan = Layanan::create($data);

    return response()->json([
        'success' => true,
        'message' => 'Layanan berhasil ditambahkan!',
        'data' => $layanan
    ], 201);

} catch (\Exception $e) {
    return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan saat menyimpan data.',
        'error' => $e->getMessage()
    ], 500);
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
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // 3. Proses data dan update
        try {
            $data = $request->only(['nama_layanan', 'harga', 'deskripsi']);

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
            
            $layanan->update($data);

            // 4. Kembalikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil diperbarui!',
                'data' => $layanan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            
            // Hapus foto dari storage jika ada
            if ($layanan->foto) {
                Storage::delete('public/' . $layanan->foto);
            }
            
            $layanan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Method lainnya tidak berubah
    public function indexLayanan(Request $request)
    {
        $query = Layanan::query();
        
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'like', "%{$search}%") // Ganti 'nama' -> 'nama_layanan'
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
        $count = \App\Models\Layanan::count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'count' => $count
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data layanan: ' . $e->getMessage()
        ], 500);
    }
}
}
