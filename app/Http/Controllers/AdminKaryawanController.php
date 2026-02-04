<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Divisi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
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
                        ->orWhere('role', 'LIKE', "%{$searchTerm}%")
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

            $divisis = Divisi::orderBy('divisi', 'asc')->get();

            return view('admin.data_karyawan', compact('karyawan', 'users', 'divisis'));

        } catch (\Exception $e) {
            \Log::error('Index karyawan error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data karyawan.');
        }
    }

    public function karyawanGeneral(Request $request)
    {
        try {
            $query = Karyawan::query();

            if ($request->has('search')) {
                $searchTerm = $request->get('search');
                $query->where('nama', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('role', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('alamat', 'LIKE', "%{$searchTerm}%");
            }

            $karyawan = $query->paginate(10);

            $users = User::with(['divisi' => function ($query) {
                $query->select('id', 'divisi');
            }])
                ->whereNotIn('id', function ($query) {
                    $query->select('user_id')
                        ->from('karyawan')
                        ->whereNotNull('user_id');
                })
                ->get(['id', 'name', 'divisi_id', 'role']);

            return view('general_manajer.data_karyawan', compact('karyawan', 'users'));

        } catch (\Exception $e) {
            \Log::error('Karyawan general error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data.');
        }
    }

    public function karyawanFinance(Request $request)
    {
        try {
            $query = Karyawan::query();

            if ($request->has('search')) {
                $searchTerm = $request->get('search');
                $query->where('nama', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('role', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('alamat', 'LIKE', "%{$searchTerm}%");
            }

            $karyawans = $query->paginate(10);

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
                    'email' => $k->email,
                    'role' => $k->role,
                    'divisi' => $k->divisi,
                    'gaji' => $k->gaji,
                    'alamat' => $k->alamat,
                    'kontak' => $k->kontak,
                    'foto' => $k->foto ? asset('storage/' . $k->foto) : ''
                ];
            });

            return view('finance.daftar_karyawan', compact('karyawans', 'users', 'karyawanJson'));

        } catch (\Exception $e) {
            \Log::error('Karyawan finance error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data.');
        }
    }

    public function karyawanDivisi(Request $request)
    {
        try {
            $user = auth::user();

            if ($user->role !== 'manager_divisi') {
                abort(403, 'Unauthorized');
            }

            $divisiManager = $user->divisi;

            $query = Karyawan::query();

            if ($divisiManager) {
                $query->where('divisi', $divisiManager->divisi);
            }

            if ($request->has('search')) {
                $searchTerm = $request->get('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('nama', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('role', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('alamat', 'LIKE', "%{$searchTerm}%");
                });
            }

            $karyawan = $query->orderBy('nama', 'asc')->get();

            $usersQuery = User::where('role', 'karyawan')
                ->whereNotIn('id', function ($query) {
                    $query->select('user_id')
                        ->from('karyawan')
                        ->whereNotNull('user_id');
                });

            if ($divisiManager) {
                $usersQuery->where('divisi_id', $divisiManager->id);
            }

            $users = $usersQuery->orderBy('name', 'asc')
                ->get(['id', 'name', 'email', 'divisi_id']);

            $namaDivisiManager = $divisiManager ? $divisiManager->divisi : 'Tidak ada divisi';

            return view('manager_divisi.daftar_karyawan', compact('karyawan', 'users', 'namaDivisiManager'));

        } catch (\Exception $e) {
            \Log::error('Karyawan divisi error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    try {
        $userRole = auth()->user()->role;
        
        // Aturan validasi dasar
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:owner,admin,general_manager,manager_divisi,finance,karyawan',
            'divisi_id' => 'nullable|exists:divisi,id',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:20',
            'status_kerja' => 'required|in:aktif,resign,phk',
            'status_karyawan' => 'required|in:tetap,kontrak,freelance',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        // Hanya finance dan admin yang bisa mengisi gaji
        if ($userRole === 'finance' || $userRole === 'admin') {
            $validationRules['gaji'] = 'nullable|numeric';
        } else {
            // Untuk non-finance, set gaji ke null
            $request->merge(['gaji' => null]);
        }

        $validated = $request->validate($validationRules);

        DB::beginTransaction();

        // 1. Buat user baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'divisi_id' => $validated['divisi_id'],
            'status_kerja' => $validated['status_kerja'],
            'status_karyawan' => $validated['status_karyawan'],
            'alamat' => $validated['alamat'],
            'kontak' => $validated['kontak'],
            'gaji' => $validated['gaji'] ?? null, // Gunakan null jika tidak diisi
            'sisa_cuti' => 12
        ]);

        // 2. Simpan foto user jika ada
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('users', 'public');
            $user->foto = $path;
            $user->save();
        }

        // 3. Buat record karyawan
        $divisi = Divisi::find($validated['divisi_id']);
        
        $karyawan = Karyawan::create([
            'user_id' => $user->id,
            'nama' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'divisi' => $divisi ? $divisi->divisi : '',
            'gaji' => $validated['gaji'] ?? null, // Gunakan null jika tidak diisi
            'alamat' => $validated['alamat'],
            'kontak' => $validated['kontak'],
            'status_kerja' => $validated['status_kerja'],
            'status_karyawan' => $validated['status_karyawan'],
            'foto' => $user->foto
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => $karyawan
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
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
public function update(Request $request, $id)
{
    try {
        // Validasi ID
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'ID karyawan tidak valid'
            ], 400);
        }
        
        // Cari karyawan
        $karyawan = Karyawan::find($id);
        
        if (!$karyawan) {
            \Log::warning('Karyawan not found for update', ['id' => $id]);
            
            return response()->json([
                'success' => false,
                'message' => 'Karyawan dengan ID ' . $id . ' tidak ditemukan'
            ], 404);
        }

        $userRole = auth()->user()->role;
        
        // Aturan validasi dasar
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $karyawan->user_id,
            'role' => 'required|in:owner,admin,general_manager,manager_divisi,finance,karyawan',
            'divisi_id' => 'nullable|exists:divisi,id',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:20',
            'status_kerja' => 'required|in:aktif,resign,phk',
            'status_karyawan' => 'required|in:tetap,kontrak,freelance',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        // Hanya finance dan admin yang bisa mengubah gaji
        if ($userRole === 'finance' || $userRole === 'admin') {
            $validationRules['gaji'] = 'nullable|numeric';
        } else {
            // Untuk non-finance, tetap gunakan gaji yang lama
            $request->merge(['gaji' => $karyawan->gaji]);
        }

        $validated = $request->validate($validationRules);

        DB::beginTransaction();

        // Update data user
        $user = User::find($karyawan->user_id);
        
        if (!$user) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'User terkait tidak ditemukan'
            ], 404);
        }
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->divisi_id = $validated['divisi_id'];
        $user->alamat = $validated['alamat'];
        $user->kontak = $validated['kontak'];
        $user->status_kerja = $validated['status_kerja'];
        $user->status_karyawan = $validated['status_karyawan'];

        // Hanya update gaji jika user adalah finance/admin
        if ($userRole === 'finance' || $userRole === 'admin') {
            $user->gaji = $validated['gaji'] ?? $user->gaji;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }
            
            $path = $request->file('foto')->store('users', 'public');
            $user->foto = $path;
        }

        $user->save();

        // Update data karyawan
        $divisi = Divisi::find($validated['divisi_id']);
        
        $karyawan->update([
            'nama' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'divisi' => $divisi ? $divisi->divisi : '',
            'alamat' => $validated['alamat'],
            'kontak' => $validated['kontak'],
            'status_kerja' => $validated['status_kerja'],
            'status_karyawan' => $validated['status_karyawan'],
            'foto' => $user->foto
        ]);

        // Hanya update gaji karyawan jika user adalah finance/admin
        if ($userRole === 'finance' || $userRole === 'admin') {
            $karyawan->update([
                'gaji' => $validated['gaji'] ?? $karyawan->gaji
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui',
            'data' => $karyawan
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Update karyawan error:', [
            'error' => $e->getMessage(),
            'id' => $id,
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Validasi ID
            if (!is_numeric($id) || $id <= 0) {
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ID karyawan tidak valid'
                    ], 400);
                }
                return redirect()->back()->with('error', 'ID karyawan tidak valid');
            }
            
            DB::beginTransaction();

            $karyawan = Karyawan::find($id);
            
            if (!$karyawan) {
                DB::rollBack();
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Karyawan dengan ID ' . $id . ' tidak ditemukan'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Karyawan tidak ditemukan');
            }
            
            // Hapus user terkait jika ada
            if ($karyawan->user) {
                // Hapus foto user jika ada
                if ($karyawan->user->foto) {
                    Storage::delete('public/' . $karyawan->user->foto);
                }
                $karyawan->user->delete();
            }

            // Hapus foto karyawan jika ada
            if ($karyawan->foto) {
                // Periksa apakah file ada di storage karyawan
                if (Storage::exists('karyawan/' . $karyawan->foto)) {
                    Storage::delete('karyawan/' . $karyawan->foto);
                }
                // Juga periksa di public path
                if (file_exists(public_path('karyawan/' . $karyawan->foto))) {
                    unlink(public_path('karyawan/' . $karyawan->foto));
                }
            }

            $karyawanName = $karyawan->nama;
            $karyawan->delete();

            DB::commit();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data karyawan '{$karyawanName}' berhasil dihapus"
                ]);
            }

            return redirect()
                ->route('admin.karyawan')
                ->with('success', "Data karyawan '{$karyawanName}' berhasil dihapus");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete karyawan error:', [
                'error' => $e->getMessage(),
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

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

    public function getKaryawanData($id)
    {
        try {
            // Validasi ID
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID karyawan tidak valid'
                ], 400);
            }
            
            $karyawan = Karyawan::with('user.divisi')->find($id);
            
            if (!$karyawan) {
                \Log::warning('Karyawan not found for get data', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan dengan ID ' . $id . ' tidak ditemukan'
                ], 404);
            }
            
            if (!$karyawan->user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan untuk karyawan ini'
                ], 404);
            }

            $data = [
                'id' => $karyawan->id,
                'user_id' => $karyawan->user->id,
                'name' => $karyawan->user->name,
                'email' => $karyawan->user->email,
                'role' => $karyawan->user->role,
                'divisi_id' => $karyawan->user->divisi_id,
                'gaji' => $karyawan->user->gaji,
                'alamat' => $karyawan->user->alamat,
                'kontak' => $karyawan->user->kontak,
                'status_kerja' => $karyawan->user->status_kerja,
                'status_karyawan' => $karyawan->user->status_karyawan,
                'foto' => $karyawan->user->foto ? asset('storage/' . $karyawan->user->foto) : null
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Get karyawan data error:', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk mendapatkan nama divisi dari user
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
            // Validasi ID
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID karyawan tidak valid'
                ], 400);
            }
            
            $karyawan = Karyawan::with('user.divisi')->find($id);
            
            if (!$karyawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan dengan ID ' . $id . ' tidak ditemukan'
                ], 404);
            }

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
            \Log::error('Get user data error:', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Menampilkan detail karyawan berdasarkan ID
     */
    public function show($id)
    {
        try {
            // Validasi ID
            if (!is_numeric($id) || $id <= 0) {
                abort(400, 'ID karyawan tidak valid');
            }
            
            $karyawan = Karyawan::with('user.divisi')->find($id);
            
            if (!$karyawan) {
                abort(404, 'Karyawan tidak ditemukan');
            }
            
            return view('admin.detail_karyawan', compact('karyawan'));
            
        } catch (\Exception $e) {
            \Log::error('Show karyawan error:', ['error' => $e->getMessage(), 'id' => $id]);
            abort(500, 'Terjadi kesalahan saat mengambil data karyawan');
        }
    }
}