<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // Tambahkan ini yang kurang

class AdminKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan SEMUA data karyawan untuk pagination di sisi klien (JavaScript).
     */
    public function index(Request $request)
    {
        // Hapus logika pencarian yang tidak digunakan karena pencarian di-handle di JS
        // Ambil SEMUA data karyawan agar pagination klien bisa bekerja dengan benar
        $karyawan = Karyawan::latest()->get();
        
        return view('admin.data_karyawan', compact('karyawan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto tidak wajib saat tambah
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // 2. Proses data dan simpan
        try {
            $data = $request->only(['nama', 'jabatan', 'divisi', 'alamat', 'kontak']);

            // Handle foto upload dengan lebih aman
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $fotoName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Simpan file ke storage/app/public/karyawan
                $path = $file->storeAs('karyawan', $fotoName, 'public');
                
                // Simpan path relatif ke database
                $data['foto'] = $path;
            }
            
            $karyawan = Karyawan::create($data);

            // 3. Kembalikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil ditambahkan!',
                'data' => $karyawan
            ], 201); // 201 Created

        } catch (\Exception $e) {
            // 4. Tangkap kesalahan
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
        $karyawan = Karyawan::findOrFail($id);

        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto tidak wajib saat update
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
            $data = $request->only(['nama', 'jabatan', 'divisi', 'alamat', 'kontak']);

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($karyawan->foto) {
                    Storage::disk('public')->delete($karyawan->foto);
                }
                
                $file = $request->file('foto');
                $fotoName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Simpan file baru
                $path = $file->storeAs('karyawan', $fotoName, 'public');
                $data['foto'] = $path;
            }
            
            $karyawan->update($data);

            // 4. Kembalikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil diperbarui!',
                'data' => $karyawan
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
            $karyawan = Karyawan::findOrFail($id);
            
            // Hapus foto dari storage jika ada
            if ($karyawan->foto) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            
            $karyawan->delete();

            // KEMBALIKAN RESPONS JSON
            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Method lain yang Anda butuhkan (misal untuk General Manager)
     */
    public function karyawanGeneral(Request $request)
    {
        // Logika untuk General Manager bisa dipertahankan jika diperlukan
        $query = Karyawan::query();

        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->where('nama', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('jabatan', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('alamat', 'LIKE', "%{$searchTerm}%");
        }

        $karyawan = $query->paginate(10); // Gunakan pagination server-side untuk General Manager
        return view('general_manajer.data_karyawan', compact('karyawan'));
    }
}