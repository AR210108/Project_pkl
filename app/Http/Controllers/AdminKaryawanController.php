<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
<<<<<<< HEAD
=======
use App\Models\User;
use Illuminate\Support\Facades\Auth;
>>>>>>> a550bd8390bd8f67a1b06aea285a34ba7deddc67
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
<<<<<<< HEAD
        // Hapus logika pencarian yang tidak digunakan karena pencarian di-handle di JS
        // Ambil SEMUA data karyawan agar pagination klien bisa bekerja dengan benar
        $karyawan = Karyawan::latest()->get();
        
        return view('admin.data_karyawan', compact('karyawan'));
    }
=======
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
        $karyawan = $query->paginate(10);

        // AMBIL DATA USERS YANG BELUM MENJADI KARYAWAN
        $users = User::whereNotIn('id', function ($query) {
            $query->select('user_id')
                ->from('karyawan')
                ->whereNotNull('user_id');
        })->get(['id', 'name', 'divisi', 'role']); // Tambahkan role

        // Tampilkan ke view dengan data yang sudah dipaginasi
        return view('admin.data_karyawan', compact('karyawan', 'users'));
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
    public function karyawanDivisi(Request $request)
{
    // Mulai dengan query builder untuk model Karyawan
    $query = Karyawan::query();

    // ========== TAMBAHKAN FILTER BERDASARKAN DIVISI USER YANG LOGIN ==========
    // Ambil user yang sedang login
    $user = auth()->user();
    
    // Pastikan user adalah manager divisi dan memiliki divisi
    if ($user && $user->divisi) {
        // Filter hanya karyawan dengan divisi yang sama dengan manager yang login
        $query->where('divisi', $user->divisi);
    }
    // ========================================================================

    // Jika ada input pencarian di URL (misal: ?search=John)
    if ($request->has('search')) {
        $searchTerm = $request->get('search');
        // Cari di kolom 'nama', 'jabatan', dan 'alamat'
        $query->where('nama', 'LIKE', "%{$searchTerm}%")
            ->orWhere('jabatan', 'LIKE', "%{$searchTerm}%")
            ->orWhere('alamat', 'LIKE', "%{$searchTerm}%");
    }

    // Ambil data dengan paginasi (10 data per halaman)
    $karyawan = $query->paginate(10);

    // AMBIL DATA USERS YANG BELUM MENJADI KARYAWAN
    // ========== TAMBAHKAN FILTER UNTUK USERS JUGA ==========
    $userQuery = User::whereNotIn('id', function ($query) {
        $query->select('user_id')
            ->from('karyawan')
            ->whereNotNull('user_id');
    });
    
    // Filter users berdasarkan divisi yang sama dengan manager login
    if ($user && $user->divisi) {
        $userQuery->where('divisi', $user->divisi);
    }
    
    $users = $userQuery->get(['id', 'name', 'divisi', 'role']);
    // =======================================================

    // Tampilkan ke view dengan data yang sudah dipaginasi
    // Kirim juga divisi user login ke view jika diperlukan
    $divisiManager = $user ? $user->divisi : null;
    
    return view('manager_divisi.daftar_karyawan', compact('karyawan', 'users', 'divisiManager'));
}
>>>>>>> a550bd8390bd8f67a1b06aea285a34ba7deddc67

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
<<<<<<< HEAD
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
=======
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jabatan' => 'required|string|max:100',
            'divisi' => 'nullable|string|max:100', // Ubah menjadi nullable
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cek apakah user sudah menjadi karyawan
        $existingKaryawan = Karyawan::where('user_id', $request->user_id)->first();
        if ($existingKaryawan) {
            return response()->json([
                'success' => false,
                'message' => 'User ini sudah terdaftar sebagai karyawan'
            ], 400);
>>>>>>> a550bd8390bd8f67a1b06aea285a34ba7deddc67
        }

        // Ambil data user
        $user = User::find($request->user_id);

        $karyawan = new Karyawan();
        $karyawan->user_id = $request->user_id;
        $karyawan->nama = $user->name; // Ambil nama dari user
        $karyawan->jabatan = $request->jabatan; // Sudah terisi dari role user
        $karyawan->divisi = $request->divisi; // Sudah terisi otomatis
        $karyawan->alamat = $request->alamat;
        $karyawan->kontak = $request->kontak;

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nama_foto = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('karyawan'), $nama_foto);
            $karyawan->foto = $nama_foto;
        }

        $karyawan->save();

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan'
        ]);
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
<<<<<<< HEAD
                
                $file = $request->file('foto');
                $fotoName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Simpan file baru
                $path = $file->storeAs('karyawan', $fotoName, 'public');
                $data['foto'] = $path;
=======

                // Upload foto baru
                $file = $request->file('foto');
                $fotoName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('karyawan'), $fotoName);
                $karyawan->foto = $fotoName;
>>>>>>> a550bd8390bd8f67a1b06aea285a34ba7deddc67
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
<<<<<<< HEAD
            
            // Hapus foto dari storage jika ada
            if ($karyawan->foto) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            
=======

            // Hapus foto dari folder public/karyawan jika ada
            if ($karyawan->foto && file_exists(public_path('karyawan/' . $karyawan->foto))) {
                unlink(public_path('karyawan/' . $karyawan->foto));
            }

            // Hapus data dari database
>>>>>>> a550bd8390bd8f67a1b06aea285a34ba7deddc67
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
<<<<<<< HEAD
    
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
=======
}
>>>>>>> a550bd8390bd8f67a1b06aea285a34ba7deddc67
