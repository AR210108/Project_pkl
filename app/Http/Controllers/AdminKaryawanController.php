<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Divisi; // JANGAN LUPA IMPORT MODEL DIVISI
use Illuminate\Support\Facades\DB;
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
        // Query karyawan dengan relasi user
        $query = Karyawan::with(['user' => function ($query) {
            $query->select('id', 'name', 'email', 'role', 'divisi_id');
        }, 'user.divisi']);

        // Search filter
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('jabatan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('divisi', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('alamat', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        // Ambil data dengan pagination
        $karyawan = $query->orderBy('created_at', 'desc')->paginate(10);

        // Ambil users yang BELUM menjadi karyawan
        $users = User::with('divisi')
            ->whereNotIn('id', function ($query) {
                $query->select('user_id')
                    ->from('karyawan')
                    ->whereNotNull('user_id');
            })
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'role', 'divisi_id']);

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
        $users = User::with(['divisi' => function ($query) {
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
        $users = User::with(['divisi' => function ($query) {
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
                'foto' => $k->foto ? asset('storage/' . $k->foto) : ''
            ];
        });

        // Tampilkan ke view dengan data yang sudah dipaginasi
        return view('finance.daftar_karyawan', compact('karyawans', 'users', 'karyawanJson'));
    }

    public function karyawanDivisi(Request $request)
    {
        // Dapatkan user yang sedang login
        $user = auth::user();

        // Cek apakah user adalah manager divisi
        if ($user->role !== 'manager_divisi') {
            abort(403, 'Unauthorized');
        }

        // Dapatkan divisi manager dari relationship
        $divisiManager = $user->divisi; // Ini menggunakan relationship divisi()

        // Query karyawan berdasarkan divisi manager
        $query = Karyawan::query();

        // Filter berdasarkan divisi yang sama dengan manager
        if ($divisiManager) {
            // $divisiManager adalah object Divisi, ambil nama divisinya
            $query->where('divisi', $divisiManager->divisi);
        }

        // Search filter jika ada
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('jabatan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('alamat', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Ambil data karyawan
        $karyawan = $query->orderBy('nama', 'asc')->get();

        // Ambil users yang belum menjadi karyawan DAN sama divisinya dengan manager
        $usersQuery = User::where('role', 'karyawan') // Hanya karyawan
            ->whereNotIn('id', function ($query) {
                $query->select('user_id')
                    ->from('karyawan')
                    ->whereNotNull('user_id');
            });

        // Filter users berdasarkan divisi yang sama
        if ($divisiManager) {
            $usersQuery->where('divisi_id', $divisiManager->id);
        }

        $users = $usersQuery->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'divisi_id']);

        // Kirim nama divisi ke view
        $namaDivisiManager = $divisiManager ? $divisiManager->divisi : 'Tidak ada divisi';

        return view('manager_divisi.daftar_karyawan', compact('karyawan', 'users', 'namaDivisiManager'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id|unique:karyawan,user_id',
                'nama'    => 'required|string|max:255',
                'email'   => 'required|email|max:255',
                'jabatan' => 'nullable|string|max:100',
                'divisi'  => 'nullable|string|max:100',
                'gaji'    => 'nullable|string|max:100',
                'alamat'  => 'required|string',
                'kontak'  => 'required|string|max:20',
                'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Ambil data user untuk verifikasi
            $user = User::with('divisi')->findOrFail($validated['user_id']);

            // Verifikasi bahwa nama dan email sesuai dengan user yang dipilih
            if ($user->name !== $validated['nama']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama tidak sesuai dengan user yang dipilih'
                ], 400);
            }

            if ($user->email !== $validated['email']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak sesuai dengan user yang dipilih'
                ], 400);
            }

            // Cek apakah user sudah menjadi karyawan
            $existingKaryawan = Karyawan::where('user_id', $user->id)->first();
            if ($existingKaryawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ini sudah terdaftar sebagai karyawan'
                ], 400);
            }

            // Buat data karyawan
            $karyawanData = [
                'user_id' => $user->id,
                'nama'    => $validated['nama'],
                'email'   => $validated['email'],
                'jabatan' => $validated['jabatan'] ?: $user->role, // Jika jabatan kosong, ambil dari role
                'divisi'  => $validated['divisi'] ?: ($user->divisi ? $user->divisi->divisi : ''),
                'gaji'    => $validated['gaji'] ?? null,
                'alamat'  => $validated['alamat'],
                'kontak'  => $validated['kontak'],
            ];

            // Handle upload foto
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $nama_foto = time() . '_' . $foto->getClientOriginalName();
                $foto->move(public_path('karyawan'), $nama_foto);
                $karyawanData['foto'] = $nama_foto;
            }

            // Simpan karyawan
            $karyawan = Karyawan::create($karyawanData);

            if ($user->divisi_id === null && $karyawan->divisi) {
                $divisi = Divisi::where('divisi', $karyawan->divisi)->first();
                if ($divisi) {
                    $user->divisi_id = $divisi->id;
                    $user->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil ditambahkan',
                'data' => $karyawan
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Store karyawan error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // Di method update(), ganti \DB:: dengan DB::
    // Di method update(), perbaiki sinkronisasi divisi
public function update(Request $request, $id)
{
    try {
        $karyawan = Karyawan::with('user.divisi')->findOrFail($id);

        // Validasi data
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'gaji' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        // Update data karyawan
        $karyawan->nama = $validated['nama'];
        $karyawan->email = $validated['email'];
        $karyawan->jabatan = $validated['jabatan'];
        $karyawan->divisi = $validated['divisi'] ?? null;
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

        // **SINKRONKAN KE USER JIKA ADA RELASI**
        if ($karyawan->user_id && $karyawan->user) {
            $user = $karyawan->user;
            $userUpdated = false;
            
            // Update nama user
            if ($user->name !== $karyawan->nama) {
                $user->name = $karyawan->nama;
                $userUpdated = true;
            }
            
            // Update email user
            if ($user->email !== $karyawan->email) {
                $user->email = $karyawan->email;
                $userUpdated = true;
            }
            
            // **PERBAIKAN: Update divisi_id di user berdasarkan divisi string di karyawan**
            if ($karyawan->divisi) {
                $divisi = Divisi::where('divisi', $karyawan->divisi)->first();
                if ($divisi) {
                    if ($user->divisi_id !== $divisi->id) {
                        $user->divisi_id = $divisi->id;
                        $userUpdated = true;
                    }
                } else {
                    // Jika divisi tidak ditemukan, set ke null
                    if ($user->divisi_id !== null) {
                        $user->divisi_id = null;
                        $userUpdated = true;
                    }
                }
            } else {
                // Jika divisi kosong di karyawan
                if ($user->divisi_id !== null) {
                    $user->divisi_id = null;
                    $userUpdated = true;
                }
            }
            
            if ($userUpdated) {
                $user->save();
                \Log::info('User updated from karyawan sync', [
                    'user_id' => $user->id,
                    'karyawan_id' => $karyawan->id,
                    'changes' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'divisi_id' => $user->divisi_id
                    ]
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true, 
            'message' => 'Data Karyawan dan User Berhasil Diupdate!',
            'data' => $karyawan->load('user')
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Update karyawan error:', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false, 
            'message' => 'Gagal mengupdate data: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);

            // hapus foto
            if ($karyawan->foto && file_exists(public_path('karyawan/' . $karyawan->foto))) {
                unlink(public_path('karyawan/' . $karyawan->foto));
            }

            $karyawanName = $karyawan->nama;
            $karyawan->delete();

            // Support AJAX request
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data karyawan '{$karyawanName}' berhasil dihapus"
                ]);
            }

            // Support form submission
            return redirect()
                ->route('admin.karyawan')
                ->with('success', "Data karyawan '{$karyawanName}' berhasil dihapus");
        } catch (\Exception $e) {
            \Log::error('Delete karyawan error:', ['error' => $e->getMessage(), 'id' => $id]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data karyawan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->route('admin.karyawan')
                ->with('error', 'Gagal menghapus data karyawan: ' . $e->getMessage());
        }
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
    public function getUserData($id)
    {
        try {
            $karyawan = Karyawan::with('user.divisi')->findOrFail($id);

            $userData = null;
            if ($karyawan->user) {
                $userData = [
                    'id' => $karyawan->user->id,
                    'name' => $karyawan->user->name,
                    'email' => $karyawan->user->email,
                    'role' => $karyawan->user->role,
                    'divisi' => $karyawan->user->divisi ? $karyawan->user->divisi->divisi : null,
                    'divisi_id' => $karyawan->user->divisi_id
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $userData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
