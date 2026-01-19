<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
    

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Store Request Data:', $request->all());

        try {

            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'nama' => 'required|string|max:100',
                'jabatan' => 'required|string|max:100',
                'divisi' => 'nullable|string|max:100',
                'alamat' => 'required|string|min:5|max:500',
                'kontak' => 'required|string|min:10|max:20',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $karyawan = new Karyawan();
            $karyawan->user_id = $validated['user_id'];
            $karyawan->nama = $validated['nama'];
            $karyawan->jabatan = $validated['jabatan'];
            $karyawan->divisi = $validated['divisi'] ?? null;
            $karyawan->alamat = $validated['alamat'];
            $karyawan->kontak = $validated['kontak'];

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                $nama_foto = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

                $file->storeAs(
                    'karyawan',     // folder
                    $nama_foto,     // nama file
                    'public'        // disk
                );

                $karyawan->foto = $nama_foto;
            }

            $karyawan->save();

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil ditambahkan',
                'data' => $karyawan
            ], 201);
        } catch (\Exception $e) {

            \Log::error('Error creating karyawan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
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

                // Hapus foto lama
                if ($karyawan->foto) {
                    Storage::disk('public')->delete('karyawan/' . $karyawan->foto);
                }

                // Simpan foto baru
                $file = $request->file('foto');
                $fotoName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->storeAs('karyawan', $fotoName, 'public');
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
            if ($karyawan->foto) {
                Storage::disk('public')->delete('karyawan/' . $karyawan->foto);
            }


            // Hapus data dari database
            $karyawan->delete();

            return response()->json(['success' => true, 'message' => 'Data Karyawan berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
