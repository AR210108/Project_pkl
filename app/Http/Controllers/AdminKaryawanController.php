<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Divisi; // JANGAN LUPA IMPORT MODEL DIVISI
use Illuminate\Support\Facades\Auth;
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
        $karyawan = $query->paginate(10);

        // AMBIL DATA USERS YANG BELUM MENJADI KARYAWAN DENGAN EAGER LOADING DIVISI
        $users = User::with(['divisi' => function($query) {
            $query->select('id', 'divisi');
        }])
        ->whereNotIn('id', function ($query) {
            $query->select('user_id')
                ->from('karyawan')
                ->whereNotNull('user_id');
        })
        ->get(['id', 'name', 'divisi_id', 'role']);

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
        $karyawan = $query->paginate(10);

        // AMBIL DATA USERS YANG BELUM MENJADI KARYAWAN DENGAN EAGER LOADING DIVISI
        $users = User::with(['divisi' => function($query) {
            $query->select('id', 'divisi');
        }])
        ->whereNotIn('id', function ($query) {
            $query->select('user_id')
                ->from('karyawan')
                ->whereNotNull('user_id');
        })
        ->get(['id', 'name', 'divisi_id', 'role']);

        // Tampilkan ke view dengan data yang sudah dipaginasi
        return view('general_manajer.data_karyawan', compact('karyawan', 'users'));
    }
    
    public function karyawanFinance(Request $request)
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
        $karyawans = $query->paginate(10);

        // AMBIL DATA USERS YANG BELUM MENJADI KARYAWAN DENGAN EAGER LOADING DIVISI
        $users = User::with(['divisi' => function($query) {
            $query->select('id', 'divisi');
        }])
        ->whereNotIn('id', function ($query) {
            $query->select('user_id')
                ->from('karyawan')
                ->whereNotNull('user_id');
        })
        ->get(['id', 'name', 'divisi_id', 'role']);

        $karyawanJson = $karyawans->getCollection()->map(function ($k) {
            return [
                'id' => $k->id,
                'nama' => $k->nama,
                'jabatan' => $k->jabatan,
                'divisi' => $k->divisi,
                'gaji' => $k->gaji,
                'alamat' => $k->alamat,
                'kontak' => $k->kontak,
                'foto' => $k->foto ? asset('storage/'.$k->foto) : ''
            ];
        });

        // Tampilkan ke view dengan data yang sudah dipaginasi
        return view('finance.daftar_karyawan', compact('karyawans', 'users', 'karyawanJson'));
    }

    public function karyawanDivisi(Request $request)
    {
        $query = Karyawan::query();

        $user = auth()->user();

        // Filter berdasarkan divisi_id user login
        if ($user && $user->divisi_id) {
            // CARI NAMA DIVISI DARI USER
            $divisiUser = Divisi::find($user->divisi_id);
            if ($divisiUser) {
                $query->where('divisi', $divisiUser->divisi); // Karyawan.divisi menyimpan NAMA divisi
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%$search%")
                  ->orWhere('jabatan', 'LIKE', "%$search%")
                  ->orWhere('alamat', 'LIKE', "%$search%");
            });
        }

        $karyawan = $query->paginate(10);

        // USERS yang belum jadi karyawan + satu divisi DENGAN EAGER LOADING
        $userQuery = User::with(['divisi' => function($query) {
            $query->select('id', 'divisi');
        }])
        ->whereNotIn('id', function ($q) {
            $q->select('user_id')
              ->from('karyawan')
              ->whereNotNull('user_id');
        });

        if ($user && $user->divisi_id) {
            $userQuery->where('divisi_id', $user->divisi_id);
        }

        $users = $userQuery->get(['id', 'name', 'divisi_id', 'role']);

        $divisiManager = $user?->divisi_id;

        return view(
            'manager_divisi.daftar_karyawan',
            compact('karyawan', 'users', 'divisiManager')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jabatan' => 'nullable|string|max:100',
            'alamat'  => 'required|string',
            'kontak'  => 'required|string|max:20',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cek apakah user sudah menjadi karyawan
        $existingKaryawan = Karyawan::where('user_id', $request->user_id)->first();
        if ($existingKaryawan) {
            return response()->json([
                'success' => false,
                'message' => 'User ini sudah terdaftar sebagai karyawan'
            ], 400);
        }

        // Ambil data user DENGAN DIVISI
        $user = User::with(['divisi' => function($query) {
            $query->select('id', 'divisi');
        }])->findOrFail($request->user_id);

        $karyawan = new Karyawan();
        $karyawan->user_id = $user->id;
        $karyawan->nama    = $user->name;
        $karyawan->jabatan = $request->jabatan ?: $user->role;
        
        // AMBIL NAMA DIVISI DARI RELASI
        $karyawan->divisi  = $user->divisi ? $user->divisi->divisi : '';
        
        $karyawan->alamat  = $request->alamat;
        $karyawan->kontak  = $request->kontak;

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
        try {
            $karyawan = Karyawan::findOrFail($id);

            // Validasi data
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'jabatan' => 'required|string|max:255',
                'divisi' => 'required|string|max:255',
                'gaji' => 'nullable|string|max:255',
                'alamat' => 'required|string',
                'kontak' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Update data karyawan
            $karyawan->nama = $validated['nama'];
            $karyawan->jabatan = $validated['jabatan'];
            $karyawan->divisi = $validated['divisi'];
            $karyawan->gaji = $validated['gaji'] ?? null;
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
                $fotoName = time() . '_' . $file->getClientOriginalName();
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
        $karyawan = Karyawan::find($id);

        if (!$karyawan) {
            return redirect()
                ->route('finance.daftar_karyawan')
                ->with('error', 'Data karyawan sudah tidak tersedia');
        }

        // hapus foto
        if ($karyawan->foto && file_exists(public_path('karyawan/' . $karyawan->foto))) {
            unlink(public_path('karyawan/' . $karyawan->foto));
        }

        $karyawan->delete();

        return redirect()
            ->route('finance.daftar_karyawan')
            ->with('success', 'Data karyawan berhasil dihapus');
    }

    /**
     * Method untuk mendapatkan nama divisi dari user
     * Bisa dipakai di blade atau API
     */
    public function getDivisiName($divisi_id)
    {
        if (!$divisi_id) {
            return '';
        }
        
        $divisi = Divisi::find($divisi_id);
        return $divisi ? $divisi->divisi : '';
    }
}