<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar karyawan dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        // Mulai dengan query builder untuk model Karyawan
        $query = Karyawan::query();

        // Jika ada input pencarian di URL (misal: ?search=John)
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            // Cari di kolom 'nama', 'jabatan', dan 'alamat'
            $query->where('nama', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('jabatan', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('alamat', 'LIKE', "%{$searchTerm}%");
        }

        // Ambil data dengan paginasi (10 data per halaman)
        // Laravel akan otomatis menjaga parameter pencarian di link paginasi
        $karyawan = $query->paginate(10);

        // Tampilkan ke view dengan data yang sudah dipaginasi
        return view('admin.data_karyawan', compact('karyawan'));
    }
    public function karyawanGeneral(Request $request)
    {
        // Mulai dengan query builder untuk model Karyawan
        $query = Karyawan::query();

        // Jika ada input pencarian di URL (misal: ?search=John)
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            // Cari di kolom 'nama', 'jabatan', dan 'alamat'
            $query->where('nama', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('jabatan', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('alamat', 'LIKE', "%{$searchTerm}%");
        }

        // Ambil data dengan paginasi (10 data per halaman)
        // Laravel akan otomatis menjaga parameter pencarian di link paginasi
        $karyawan = $query->paginate(10);

        // Tampilkan ke view dengan data yang sudah dipaginasi
        return view('general_manajer.data_karyawan', compact('karyawan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'jabatan' => 'required|string|max:255',
                'divisi' => 'required|string|max:255', 
                'alamat' => 'required|string',
                'kontak' => 'required|string|max:255',
                'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Upload foto
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $fotoName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('karyawan'), $fotoName);
            }

            // Simpan data ke database
            Karyawan::create([
                'user_id' => auth()->id(), // Ambil ID user yang sedang login
                'nama' => $validated['nama'],
                'jabatan' => $validated['jabatan'],
                'divisi' => $validated['divisi'],
                'alamat' => $validated['alamat'],
                'kontak' => $validated['kontak'],
                'foto' => $fotoName,
            ]);

            return response()->json(['success' => true, 'message' => 'Data Karyawan Berhasil Ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menambah data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);

            // Validasi data
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'jabatan' => 'required|string|max:255',
                'divisi' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kontak' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Foto tidak wajib diubah
            ]);

            // Update data karyawan
            $karyawan->nama = $validated['nama'];
            $karyawan->jabatan = $validated['jabatan'];
            $karyawan->divisi = $validated['divisi'];
            $karyawan->alamat = $validated['alamat'];
            $karyawan->kontak = $validated['kontak'];

            // Jika ada upload foto baru
            if ($request->hasFile('foto')) {
                // Hapus foto lama dari folder public/karyawan
                if ($karyawan->foto && file_exists(public_path('karyawan/' . $karyawan->foto))) {
                    unlink(public_path('karyawan/' . $karyawan->foto));
                }
                
                // Upload foto baru
                $file = $request->file('foto');
                $fotoName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('karyawan'), $fotoName);
                $karyawan->foto = $fotoName;
            }

            $karyawan->save();

            return response()->json(['success' => true, 'message' => 'Data Karyawan Berhasil Diupdate!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengupdate data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);
            
            // Hapus foto dari folder public/karyawan jika ada
            if ($karyawan->foto && file_exists(public_path('karyawan/' . $karyawan->foto))) {
                unlink(public_path('karyawan/' . $karyawan->foto));
            }
            
            // Hapus data dari database
            $karyawan->delete();

            return response()->json(['success' => true, 'message' => 'Data Karyawan berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}