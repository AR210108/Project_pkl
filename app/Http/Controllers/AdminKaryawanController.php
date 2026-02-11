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
            // Query users dengan relasi karyawan dan divisi (data user lebih lengkap)
            $query = User::with(['karyawan', 'divisi'])
                ->where('role', '!=', 'admin')  // Exclude admin role jika perlu
                ->where('role', '!=', 'owner');  // Exclude owner role

            // Search filter
            if ($request->has('search')) {
                $searchTerm = $request->get('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('role', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('alamat', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('kontak', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Ambil data dengan pagination
            $karyawan = $query->orderBy('created_at', 'desc')->paginate(10);

            // Ambil users yang BELUM memiliki relasi karyawan (untuk create new)
            $users = User::with('divisi')
                ->doesntHave('karyawan')
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
        // Query dari users dengan relasi karyawan & divisi (lebih reliable)
        $query = User::with(['karyawan', 'divisi'])
                    ->where('role', 'karyawan');

        \Log::info('Karyawan General - Query started');

        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('alamat', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('kontak', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter divisi
        if ($request->has('divisi') && $request->get('divisi') != '') {
            $query->where('divisi_id', $request->get('divisi'));
        }

        // Paginate langsung dari users
        $usersPaginated = $query->orderBy('name', 'asc')->paginate(10);

        \Log::info('Karyawan general loaded', [
            'total' => $usersPaginated->total(),
            'per_page' => $usersPaginated->perPage(),
            'current_page' => $usersPaginated->currentPage()
        ]);

        // Transform users ke format karyawan - gunakan mapWithKeys untuk maintain pagination
        $karyawan = $usersPaginated->through(function ($user) {
            return (object) [
                'id' => $user->id,
                'user_id' => $user->id,
                'nama' => $user->name,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'divisi' => $user->divisi ? $user->divisi->divisi : null,
                'divisi_id' => $user->divisi_id,
                'alamat' => $user->alamat,
                'kontak' => $user->kontak,
                'foto' => $user->foto,
                'gaji' => $user->gaji,
                'status_kerja' => $user->status_kerja,
                'status_karyawan' => $user->status_karyawan,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });

        return view('general_manajer.data_karyawan', ['karyawan' => $karyawan]);

    } catch (\Exception $e) {
        \Log::error('Karyawan general error:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
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

        // Prefer using divisi_id for filtering (manager's division)
        $divisiId = $user->divisi_id;

        // Build a users query filtered by role and manager's divisi_id
        $usersQueryMain = User::with(['karyawan', 'divisi'])
            ->where('role', 'karyawan');

        // Apply search on user fields if provided
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $usersQueryMain->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('role', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('alamat', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('kontak', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($divisiId) {
            $usersQueryMain->where('divisi_id', $divisiId);
        }

        $usersList = $usersQueryMain->orderBy('name', 'asc')->get();

        // Map users to the karyawan shape expected by the view
        $karyawan = $usersList->map(function ($user) {
            // Determine divisi name with multiple fallbacks
            $divisiName = null;
            if ($user->divisi && isset($user->divisi->divisi)) {
                $divisiName = $user->divisi->divisi;
            } elseif (!empty($user->divisi_id)) {
                // Fallback: try to find Divisi by id (covers cases where relation wasn't hydrated)
                $divModel = \App\Models\Divisi::find($user->divisi_id);
                if ($divModel) $divisiName = $divModel->divisi;
            }

            return (object) [
                'id' => $user->karyawan ? $user->karyawan->id : null,
                'user_id' => $user->id,
                'nama' => $user->name,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'divisi' => $divisiName,
                'divisi_id' => $user->divisi_id,
                'alamat' => $user->alamat,
                'kontak' => $user->kontak,
                'foto' => $user->foto,
                'gaji' => $user->gaji,
                'status_kerja' => $user->status_kerja,
                'status_karyawan' => $user->status_karyawan,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });

        // Users for modal (only karyawan in same divisi and not already karyawan)
        $usersForModal = User::where('role', 'karyawan')
            ->whereNotIn('id', function ($query) {
                $query->select('user_id')
                    ->from('karyawan')
                    ->whereNotNull('user_id');
            });

        if ($divisiId) {
            $usersForModal->where('divisi_id', $divisiId);
        }

        $users = $usersForModal->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'divisi_id']);

        // Determine division name for the header
        $namaDivisiManager = 'Tidak ada divisi';
        if ($divisiId) {
            $divModel = Divisi::find($divisiId);
            if ($divModel) $namaDivisiManager = $divModel->divisi;
        }

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

        // 3. Load karyawan yang sudah dibuat otomatis oleh User boot() event
        $user->load('karyawan');

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => $user->karyawan
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
                'message' => 'ID user tidak valid'
            ], 400);
        }
        
        // Cari user (bukan karyawan) - gunakan id sebagai user_id
        $user = User::with('karyawan')->find($id);
        
        if (!$user) {
            \Log::warning('User not found for update', ['id' => $id]);
            
            return response()->json([
                'success' => false,
                'message' => 'User dengan ID ' . $id . ' tidak ditemukan'
            ], 404);
        }

        $userRole = auth()->user()->role;
        
        // Aturan validasi dasar
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
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
            $request->merge(['gaji' => $user->gaji]);
        }

        $validated = $request->validate($validationRules);

        DB::beginTransaction();

        // Update data user (boot event akan otomatis sinkronkan ke karyawan)
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

        $user->save();  // Boot event updated() akan trigger sinkronisasi ke karyawan

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui',
            'data' => $user->load('karyawan')
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
        \Log::error('Update user error:', [
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
                        'message' => 'ID user tidak valid'
                    ], 400);
                }
                return redirect()->back()->with('error', 'ID user tidak valid');
            }
            
            DB::beginTransaction();

            // Cari user dengan relasi karyawan
            $user = User::with('karyawan')->find($id);
            
            if (!$user) {
                DB::rollBack();
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User dengan ID ' . $id . ' tidak ditemukan'
                    ], 404);
                }
                return redirect()->back()->with('error', 'User tidak ditemukan');
            }
            
            $userName = $user->name;

            // Hapus foto user jika ada
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }

            // Hapus relasi karyawan jika ada (cascade delete oleh FK)
            if ($user->karyawan) {
                $user->karyawan->delete();
            }

            // Delete user (akan trigger deleted event jika ada)
            $user->delete();

            DB::commit();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "User '{$userName}' berhasil dihapus"
                ]);
            }

            return redirect()
                ->route('admin.karyawan')
                ->with('success', "User '{$userName}' berhasil dihapus");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete user error:', [
                'error' => $e->getMessage(),
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->route('admin.karyawan')
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function getKaryawanData($id)
    {
        try {
            // Validasi ID
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID user tidak valid'
                ], 400);
            }
            
            // Cari dari User table dengan relasi karyawan (data user lebih lengkap)
            $user = User::with('karyawan', 'divisi')->find($id);
            
            if (!$user) {
                \Log::warning('User not found for get data', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'User dengan ID ' . $id . ' tidak ditemukan'
                ], 404);
            }

            $data = [
                'id' => $user->id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'divisi_id' => $user->divisi_id,
                'divisi_name' => $user->divisi ? $user->divisi->divisi : '',
                'gaji' => $user->gaji,
                'alamat' => $user->alamat,
                'kontak' => $user->kontak,
                'status_kerja' => $user->status_kerja,
                'status_karyawan' => $user->status_karyawan,
                'sisa_cuti' => $user->sisa_cuti,
                'foto' => $user->foto ? asset('storage/' . $user->foto) : null,
                'karyawan_id' => $user->karyawan ? $user->karyawan->id : null
            ];

            return response()->json([
                'success' => true,
                'data' => $data
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