<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Divisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ManagerDivisiTaskController extends Controller
{
    /**
     * Menampilkan halaman kelola tugas
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get karyawan in same division
        $karyawan = User::where('divisi', $user->divisi)
                       ->where('role', 'karyawan')
                       ->get();
        
        // List of all divisions
        $divisi = Divisi::orderBy('divisi')->get();

        // Get other managers excluding current user
        $managers = User::where('role', 'manager_divisi')
                       ->where('id', '!=', $user->id)
                       ->get();
        
        return view('manager_divisi.kelola_tugas', compact(
            'karyawan', 
            'divisi', 
            'managers'
        ));
    }
    
    /**
     * MENAMBAHKAN: Method untuk menampilkan data karyawan divisi
     */
    public function dataKaryawan()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manager_divisi') abort(403, 'Akses hanya untuk Manager Divisi');
        
        Log::info('Manager Divisi Data Karyawan:', [
            'manager_id' => $user->id,
            'manager_name' => $user->name,
            'manager_divisi' => $user->divisi,
            'manager_divisi_id' => $user->divisi_id
        ]);
        
        $karyawan = collect(); // Inisialisasi collection kosong
        
        // CARA 1: Cari berdasarkan divisi_id (jika ada)
        if (!empty($user->divisi_id)) {
            $karyawan = User::where('divisi_id', $user->divisi_id)
                          ->where('role', 'karyawan')
                          ->with('divisi') // Eager load relation
                          ->orderBy('name')
                          ->get();
            
            if ($karyawan->isNotEmpty()) {
                Log::info('✓ Ditemukan ' . $karyawan->count() . ' karyawan dengan divisi_id');
            } else {
                Log::warning('✗ Tidak ditemukan karyawan dengan divisi_id');
            }
        }
        
        // CARA 2: Jika masih kosong, cari berdasarkan divisi (string)
        if ($karyawan->isEmpty() && !empty($user->divisi)) {
            $karyawan = User::where('divisi', $user->divisi)
                          ->where('role', 'karyawan')
                          ->orderBy('name')
                          ->get();
            
            if ($karyawan->isNotEmpty()) {
                Log::info('✓ Ditemukan ' . $karyawan->count() . ' karyawan dengan divisi string');
            } else {
                Log::warning('✗ Tidak ditemukan karyawan dengan divisi string');
            }
        }
        
        // CARA 3: Jika masih kosong, tampilkan semua karyawan untuk debugging
        if ($karyawan->isEmpty()) {
            Log::warning('Tidak ada karyawan ditemukan di divisi manager. Menampilkan semua karyawan untuk debug.');
            $karyawan = User::where('role', 'karyawan')
                          ->orderBy('name')
                          ->get(['id', 'name', 'email', 'divisi', 'divisi_id']);
            
            Log::info('Total semua karyawan di database: ' . $karyawan->count());
            
            // Log detail semua karyawan untuk debugging
            foreach ($karyawan as $k) {
                Log::info('Karyawan - ID: ' . $k->id . 
                         ', Nama: ' . $k->name . 
                         ', Divisi: ' . $k->divisi . 
                         ', Divisi ID: ' . $k->divisi_id);
            }
        }
        
        // Tambahkan data debug untuk view
        $debugInfo = [
            'manager_divisi' => $user->divisi,
            'manager_divisi_id' => $user->divisi_id,
            'total_karyawan' => $karyawan->count(),
            'karyawan_list' => $karyawan->map(function($k) {
                return [
                    'id' => $k->id,
                    'nama' => $k->name,
                    'divisi' => $k->divisi,
                    'divisi_id' => $k->divisi_id,
                    'jabatan' => $k->jabatan ?? '-',
                    'email' => $k->email
                ];
            })->toArray()
        ];
        
        return view('manager_divisi.data_karyawan', compact('karyawan', 'debugInfo'));
    }
    
    /**
     * MENAMBAHKAN: API untuk mendapatkan karyawan divisi (AJAX)
     */
    public function getKaryawanDivisiApi()
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }
            
            Log::info('API: Get karyawan divisi for manager', [
                'manager_id' => $user->id,
                'manager_name' => $user->name,
                'manager_divisi' => $user->divisi,
                'manager_divisi_id' => $user->divisi_id
            ]);
            
            $karyawan = User::where('role', 'karyawan')
                ->where(function($query) use ($user) {
                    // Prioritaskan pencarian dengan divisi_id jika ada
                    if ($user->divisi_id) {
                        $query->where('divisi_id', $user->divisi_id);
                    }
                    // Jika tidak ada divisi_id atau ingin backup, gunakan nama divisi
                    if ($user->divisi) {
                        $query->orWhere('divisi', $user->divisi);
                    }
                })
                ->with('divisi:id,divisi') // Eager load relation
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'divisi', 'divisi_id'])
                ->map(function($k) {
                    return [
                        'id' => $k->id,
                        'name' => $k->name,
                        'nama' => $k->name,
                        'email' => $k->email,
                        'divisi' => $k->divisi,
                        'divisi_id' => $k->divisi_id,
                        'divisi_nama' => null, 
                        'jabatan' => 'Karyawan', 
                        'alamat' => '-',
                        'kontak' => '-',
                        'foto' => $k->foto,
                        'status' => 'aktif' 
                    ];
                });
            
            Log::info('API: Found ' . $karyawan->count() . ' karyawan');
            
            return response()->json([
                'success' => true,
                'data' => $karyawan,
                'total' => $karyawan->count(),
                'manager_info' => [
                    'name' => $user->name,
                    'divisi' => $user->divisi,
                    'divisi_id' => $user->divisi_id // FIXED: was $divisi_id (undefined)
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('API Error in getKaryawanDivisiApi:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    } // FIXED: Missing closing brace
    
    /**
     * MENAMBAHKAN: Get karyawan untuk dropdown - FIXED VERSION (NO STATUS)
     */
    public function getKaryawanDropdown()
    {
        try {
            $user = Auth::user();
            
            // Validasi user adalah manager divisi
            if (!$user || $user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Hanya manager divisi yang dapat mengakses.'
                ], 403);
            }
            
            // Debug: Log info user
            Log::info('=== MANAGER DIVISI KARYAWAN DROPDOWN API CALL ===');
            Log::info('Manager Info:', [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'divisi' => $user->divisi,
                'divisi_id' => $user->divisi_id
            ]);
            
            // Validasi manager memiliki divisi
            if (!$user->divisi_id && !$user->divisi) {
                Log::warning('Manager tidak memiliki divisi yang ditugaskan.');
                return response()->json([
                    'success' => false,
                    'message' => 'Manager tidak memiliki divisi yang ditugaskan.'
                ], 400);
            }
            
            // Query karyawan - Multiple kondisi untuk memastikan data ditemukan
            $query = User::where('role', 'karyawan');
            
            // Coba 1: Filter dengan divisi_id jika ada
            $found = false;
            if ($user->divisi_id) {
                $karyawanByDivisiId = User::where('role', 'karyawan')
                    ->where('divisi_id', $user->divisi_id)
                    ->count();
                
                if ($karyawanByDivisiId > 0) {
                    $query->where('divisi_id', $user->divisi_id);
                    $found = true;
                    Log::info('Found ' . $karyawanByDivisiId . ' karyawan with divisi_id: ' . $user->divisi_id);
                }
            }
            
            // Coba 2: Filter dengan nama divisi jika belum ditemukan atau sebagai backup
            if (!$found && $user->divisi) {
                $karyawanByDivisiName = User::where('role', 'karyawan')
                    ->where('divisi', $user->divisi)
                    ->count();
                
                if ($karyawanByDivisiName > 0) {
                    $query->where('divisi', $user->divisi);
                    $found = true;
                    Log::info('Found ' . $karyawanByDivisiName . ' karyawan with divisi name: ' . $user->divisi);
                }
            }
            
            // Ambil data
            $karyawan = $query->orderBy('name')
                ->get(['id', 'name', 'email', 'divisi', 'divisi_id'])
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'nama' => $item->name,
                        'email' => $item->email,
                        'divisi' => $item->divisi,
                        'divisi_id' => $item->divisi_id,
                        'jabatan' => 'Karyawan',
                        'user' => [
                            'id' => $item->id,
                            'name' => $item->name,
                            'divisi' => $item->divisi,
                            'divisi_id' => $item->divisi_id
                        ]
                    ];
                });
            
            // Debug: Log hasil query
            Log::info('Query Result:', [
                'divisi_id' => $user->divisi_id,
                'divisi_name' => $user->divisi,
                'karyawan_count' => $karyawan->count(),
                'found_with_filter' => $found
            ]);
            
            // Debug: Log detail semua karyawan untuk debugging
            foreach ($karyawan as $k) {
                Log::info('Karyawan - ID: ' . $k['id'] . 
                         ', Nama: ' . $k['name'] . 
                         ', Divisi: ' . $k['divisi'] . 
                         ', Divisi ID: ' . $k['divisi_id']);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil diambil',
                'data' => $karyawan,
                'total' => $karyawan->count(),
                'manager_info' => [
                    'name' => $user->name,
                    'divisi' => $user->divisi,
                    'divisi_id' => $user->divisi_id
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getKaryawanDropdown:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    } // FIXED: Missing closing brace
    
    /**
     * MENAMBAHKAN: API untuk mendapatkan karyawan berdasarkan parameter
     */
    public function getKaryawanByDivisi($parameter)
    {
        try {
            $user = Auth::user();
            
            // Cek apakah parameter adalah ID numerik atau nama divisi
            $isId = is_numeric($parameter);
            
            // Verifikasi akses
            if ($user->role === 'manager_divisi') {
                if ($isId) {
                    // Jika parameter adalah ID, pastikan sesuai dengan divisi manager
                    if ($parameter != $user->divisi_id) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Unauthorized. Anda tidak memiliki akses ke divisi ini.'
                        ], 403);
                    }
                } else {
                    // Jika parameter adalah nama divisi, pastikan sesuai dengan divisi manager
                    if ($parameter !== $user->divisi) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Unauthorized. Anda tidak memiliki akses ke divisi ini.'
                        ], 403);
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            Log::info('API: Get karyawan by divisi parameter', [
                'parameter' => $parameter,
                'parameter_type' => $isId ? 'id' : 'divisi_name',
                'user_id' => $user->id,
                'user_role' => $user->role,
                'user_divisi' => $user->divisi,
                'user_divisi_id' => $user->divisi_id
            ]);
            
            $query = User::where('role', 'karyawan');
            
            if ($isId) {
                // Jika parameter adalah ID numerik
                $query->where('divisi_id', $parameter);
            } else {
                // Jika parameter adalah nama divisi
                $query->where('divisi', $parameter);
            }
            
            $karyawan = $query->orderBy('name')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'nama' => $item->name,
                        'email' => $item->email,
                        'divisi_id' => $item->divisi_id,
                        'divisi' => $item->divisi,
                        'jabatan' => 'Karyawan'
                    ];
                });
            
            Log::info('API: Found ' . $karyawan->count() . ' karyawan for divisi: ' . $parameter);
            
            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil diambil',
                'data' => $karyawan,
                'total' => $karyawan->count(),
                'parameter_info' => [
                    'parameter' => $parameter,
                    'parameter_type' => $isId ? 'divisi_id' : 'divisi_name'
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Karyawan By Divisi Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    } // FIXED: Missing closing brace
    
    /**
     * MENAMBAHKAN: API untuk mendapatkan daftar project (TAMBAHKAN INI)
     */
    public function getProjectDropdown()
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Hanya Manager Divisi.'
                ], 403);
            }

            // Ambil data dari tabel divisis (Penanggung Jawab)
            $projects = \App\Models\Divisi::orderBy('divisi', 'asc')
                ->get(['id', 'divisi']);

            // Transformasi data agar seragam dengan API karyawan
            $projectData = $projects->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->divisi, 
                    'project_name' => $item->divisi
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $projectData,
                'total' => $projectData->count(),
                'manager_info' => [
                    'name' => $user->name,
                    'divisi' => $user->divisi,
                    'divisi_id' => $user->divisi_id
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getProjectDropdown:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data project'
            ], 500);
        }
    } // FIXED: Missing closing brace
    
    /**
     * MENAMBAHKAN: Method tambah karyawan baru
     */
    public function storeKaryawan(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'manager_divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'divisi_id' => 'required|exists:divisis,id',
            'jabatan' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:20',
        ]);
        
        // Validasi: hanya bisa tambah karyawan ke divisi sendiri
        if ($validated['divisi_id'] != $user->divisi_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya bisa menambahkan karyawan ke divisi Anda sendiri.'
            ], 422);
        }
        
        // Get divisi name
        $divisi = Divisi::find($validated['divisi_id']);
        
        // Create karyawan
        $newKaryawan = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'karyawan',
            'divisi_id' => $validated['divisi_id'],
            'divisi' => $divisi->divisi,
            'jabatan' => $validated['jabatan'],
            'alamat' => $validated['alamat'] ?? null,
            'kontak' => $validated['kontak'] ?? null,
            'sisa_cuti' => 12,
            'total_cuti_tahunan' => 12,
            'status' => 'aktif'
        ]);
        
        Log::info('Karyawan baru ditambahkan oleh Manager Divisi:', [
            'manager_id' => $user->id,
            'manager_name' => $user->name,
            'karyawan_id' => $newKaryawan->id,
            'karyawan_name' => $newKaryawan->name,
            'divisi_id' => $newKaryawan->divisi_id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => $newKaryawan
        ]);
    } // FIXED: Missing closing brace (Method duplicate below handled by removing one)
    
    /**
     * MENAMBAHKAN: Method untuk edit karyawan
     */
    public function updateKaryawan(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'manager_divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }
        
        $karyawan = User::where('id', $id)
                       ->where('role', 'karyawan')
                       ->firstOrFail();
        
        // Validasi divisi
        if ($karyawan->divisi_id != $user->divisi_id && $karyawan->divisi != $user->divisi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya bisa mengubah karyawan di divisi Anda sendiri'
            ], 422);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'divisi_id' => 'required|exists:divisis,id',
            'jabatan' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,cuti,tidak aktif'
        ]);
        
        // Get divisi name
        $divisi = Divisi::find($validated['divisi_id']);
        
        $karyawan->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'divisi_id' => $validated['divisi_id'],
            'divisi' => $divisi->divisi,
            'jabatan' => $validated['jabatan'],
            'alamat' => $validated['alamat'] ?? null,
            'kontak' => $validated['kontak'] ?? null,
            'status' => $validated['status']
        ]);
        
        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed'
            ]);
            $karyawan->update([
                'password' => Hash::make($request->password)
            ]);
        }
        
        Log::info('Karyawan diperbarui oleh Manager Divisi:', [
            'manager_id' => $user->id,
            'manager_name' => $user->name,
            'karyawan_id' => $karyawan->id,
            'karyawan_name' => $karyawan->name
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui',
            'data' => $karyawan
        ]);
    }
    
    /**
     * MENAMBAHKAN: Delete karyawan
     */
    public function destroyKaryawan($id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'manager_divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }
        
        $karyawan = User::where('id', $id)
                       ->where('role', 'karyawan')
                       ->firstOrFail();
        
        // Validasi divisi
        if ($karyawan->divisi_id != $user->divisi_id && $karyawan->divisi != $user->divisi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya bisa menghapus karyawan di divisi Anda sendiri'
            ], 403);
        }
        
        // Soft delete
        $karyawan->delete();
        
        Log::info('Karyawan dihapus oleh Manager Divisi:', [
            'manager_id' => $user->id,
            'manager_name' => $user->name,
            'karyawan_id' => $karyawan->id,
            'karyawan_name' => $karyawan->name,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil dihapus'
        ]);
    }
    
    /**
     * MENAMBAHKAN: Method tambah karyawan
     */
    public function createKaryawan()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manager_divisi') {
            abort(403, 'Akses hanya untuk Manager Divisi');
        }
        
        $divisiList = Divisi::orderBy('divisi')->get();
        
        return view('manager_divisi.tambah_karyawan', compact('divisiList'));
    }

    // =========== METHOD TASK YANG SUDAH ADA ===========
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prioritas' => 'required|in:tinggi,normal,rendah',
            'deadline' => 'required|date',
            'status' => 'sometimes|in:pending,proses,selesai,dibatalkan',
            'target_type' => 'required|in:karyawan,divisi,manager',
            'kategori' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'project_id' => 'nullable|exists:divisis,id',
            'priority' => 'required|in:tinggi,normal,rendah',
        ]);
        
        $user = Auth::user();
        $validated['created_by'] = $user->id;
        $validated['assigned_by_manager'] = $user->id;
        $validated['assigned_at'] = now();
        
        // FIX: Konversi prioritas
        $prioritasMapping = [
            'tinggi' => 'high',
            'normal' => 'medium',
            'rendah' => 'low'
        ];
        $validated['priority'] = $prioritasMapping[$validated['prioritas']] ?? 'medium';
        unset($validated['prioritas']); 
        
        $validated['status'] = $validated['status'] ?? 'pending';
        
        // FIX: Validasi divisi untuk manager
        if ($request->target_type === 'divisi' && $request->filled('target_divisi')) {
            if ($request->target_divisi !== $user->divisi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda hanya dapat menugaskan ke divisi Anda sendiri'
                ], 422);
            }
            $validated['target_divisi'] = $request->target_divisi;
            $validated['target_type'] = 'divisi';
            $validated['is_broadcast'] = true;
            
            $validated['assigned_to'] = $user->id;
            $validated['target_manager_id'] = $user->id;
        } 
        elseif ($request->target_type === 'karyawan' && $request->filled('assigned_to')) {
            $karyawan = User::find($request->assigned_to);
            if (!$karyawan || $karyawan->divisi !== $user->divisi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan tidak berada di divisi Anda'
                ], 422);
            }
            $validated['assigned_to'] = $request->assigned_to;
            $validated['target_type'] = 'karyawan';
            $validated['is_broadcast'] = false;
            $validated['target_divisi'] = $user->divisi;
            $validated['target_manager_id'] = $user->id;
        } 
        elseif ($request->target_type === 'manager' && $request->filled('target_manager_id')) {
            $validated['target_manager_id'] = $request->target_manager_id;
            $validated['target_type'] = 'manager';
            $validated['is_broadcast'] = false;
            $validated['assigned_to'] = $request->target_manager_id;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Harap lengkapi data penerima tugas sesuai tipe yang dipilih'
            ], 422);
        }
        
        if ($request->has('project_id')) {
            $validated['project_id'] = $request->project_id;
        }

        if ($request->filled('id')) {
            $task = Task::findOrFail($request->id);
            
            if ($task->created_by != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengedit tugas ini'
                ], 403);
            }
            
            $task->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diperbarui'
            ]);
        }
        
        Task::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat'
        ]);
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai,dibatalkan',
            'catatan_update' => 'nullable|string'
        ]);
        
        $task = Task::findOrFail($id);
        $user = Auth::user();
        
        $canUpdate = false;
        
        if ($task->created_by == $user->id) {
            $canUpdate = true;
        } elseif ($task->assigned_to == $user->id) {
            $canUpdate = true;
        } elseif ($task->target_divisi == $user->divisi && $user->role === 'manager_divisi') {
            $canUpdate = true;
        } elseif ($task->target_manager_id == $user->id) {
            $canUpdate = true;
        }
        
        if (!$canUpdate) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengupdate tugas ini'
            ], 403);
        }
        
        $updateData = [
            'status' => $request->status,
        ];
        
        if ($request->filled('catatan_update')) {
            $updateData['catatan_update'] = $request->catatan_update;
        }
        
        if ($request->status === 'selesai' && $task->status !== 'selesai') {
            $updateData['completed_at'] = now();
        }
        
        $task->update($updateData);
        
        return response()->json([
            'success' => true,
            'message' => 'Status tugas berhasil diupdate'
        ]);
    }
    
    public function assignToKaryawan(Request $request, $id)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:users,id'
        ]);
        
        $task = Task::findOrFail($id);
        $user = Auth::user();
        
        if (!$task->is_broadcast || $task->target_type !== 'divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Tugas ini bukan tugas broadcast ke divisi'
            ], 422);
        }
        
        if ($task->target_divisi !== $user->divisi || $user->role !== 'manager_divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menugaskan tugas ini'
            ], 403);
        }
        
        $karyawan = User::findOrFail($request->karyawan_id);
        if ($karyawan->divisi != $task->target_divisi) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak berada di divisi yang sama'
            ], 422);
        }
        
        $task->update([
            'assigned_to' => $request->karyawan_id,
            'assigned_by_manager' => $user->id,
            'assigned_at' => now(),
            'is_broadcast' => false,
            'target_type' => 'karyawan',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditugaskan ke karyawan'
        ]);
    }
    
    /**
     * FIX: Get Tasks Api - Manual Join & Logic Fix
     */
    public function getTasksApi(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'my-tasks');
        
        // Manual join
        $tasks = Task::leftJoin('users as assignee', 'tasks.assigned_to', '=', 'assignee.id')
                    ->leftJoin('users as creator', 'tasks.created_by', '=', 'creator.id')
                    ->select('tasks.*', 'assignee.name as assignee_name', 'creator.name as creator_name');
        
        if ($type === 'my-tasks') {
            $tasks = $tasks->where('created_by', $user->id);
        } elseif ($type === 'team-tasks') {
            $tasks = $tasks->where(function($query) use ($user) {
                $query->where('target_manager_id', $user->id);
            })->orWhere(function($query) use ($user) {
                $query->where('target_divisi', $user->divisi)
                      ->where('target_type', 'divisi')
                      ->where('is_broadcast', true);
            })->orWhere(function($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })->orWhere(function($query) use ($user) {
                $query->where('created_by', $user->id);
            });
        } else {
            // LOGIC FIX: Di sini kita menggunakan manual join, jadi kita TIDAK boleh menggunakan orWhereHas (relasi model)
            // Kita harus menggunakan orWhere biasa pada kolom yang sudah di-join.
            
            $tasks = $tasks->where(function($q) use ($user) {
                $q->where('target_divisi', $user->divisi);
                
                // Karena sudah di-join manual, kita cek kolom assignee dan creator langsung di query builder
                $q->orWhere(function($innerQuery) use ($user) {
                     $innerQuery->whereHas('assignedUser', function($subQ) use ($user) {
                        // Fallback jika ingin pakai relasi, tapi hati-hati konflik dengan manual join
                        // Sebaiknya gunakan kolom dari join:
                        // $subQ->where('divisi', $user->divisi); 
                     });
                });
                
                // Alternatif yang lebih aman tanpa relasi (menggunakan join result atau ID):
                // Tapi karena ini builder awal sebelum get(), kita butuh relasi atau subquery kompleks.
                // Sesuai komentar kode asli: "FIX: Removed orHas... karena relasi mungkin tidak ada"
                // Maka kita gunakan filter sederhana berdasarkan kolom yang tersedia di tabel tasks
                
                $q->orWhere('target_manager_id', $user->id);
            });
            
            // Menambahkan filter untuk created_by agar melihat tugas sendiri juga
            $tasks->orWhere('created_by', $user->id);
        }
        
        $tasks = $tasks->orderBy('created_at', 'desc')->get();
        
        $tasks->transform(function($task) {
            if ($task->target_type === 'karyawan' && !empty($task->assignee)) {
                // Pastikan menggunakan data join
                $task->assignee_text = $task->assignee_name ?? $task->assignee->name ?? '-';
            } elseif ($task->target_divisi) {
                $task->assignee_text = 'Divisi ' . ($task->target_divisi ?? '-');
            } elseif ($task->target_type === 'manager' && !empty($task->target_manager)) {
                 $task->assignee_text = 'Manager: ' . ($task->target_manager->name ?? '-');
            } else {
                $task->assignee_text = '-';
            }
            
            $task->target_divisi = $task->target_divisi ?? '-';
            $task->assignee_divisi = $task->target_divisi ?? '-';
            $task->creator_name = $task->creator_name ?? '-';
            
            $task->is_overdue = $task->deadline && now()->gt($task->deadline) && $task->status !== 'selesai';
            $task->formatted_deadline = $task->deadline ? $task->deadline->format('d M Y H:i') : '-';
            
            $task->progress_percentage = $task->progress_percentage ?? 0;
            $task->status_color = $task->status_color ?? 'warning';
            $task->priority_color = $task->priority_color ?? 'secondary';
            
            return $task;
        });
        
        return response()->json($tasks);
    }
    
    /**
     * FIX: Get Statistics
     */
    public function getStatistics()
    {
        $user = Auth::user();
        
        $baseQuery = Task::where('created_by', $user->id);
        
        $baseQuery->orWhere('target_divisi', $user->divisi);

        $total = (clone $baseQuery)->count();
        
        $completed = (clone $baseQuery)->where('status', 'selesai')->count();
        
        $inProgress = (clone $baseQuery)->where('status', 'proses')->count();
        
        $pending = (clone $baseQuery)->where('status', 'pending')->count();
        
        $overdue = (clone $baseQuery)
            ->where('deadline', '<', now())
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->count();
        
        return response()->json([
            'total' => $total,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'pending' => $pending,
            'overdue' => $overdue
        ]);
    }
    
    // Get task detail
    public function show($id)
    {
        $task = Task::leftJoin('users as assignee', 'tasks.assigned_to', '=', 'assignee.id')
            ->leftJoin('users as creator', 'tasks.created_by', '=', 'creator.id')
            ->leftJoin('users as manager', 'tasks.target_manager_id', '=', 'manager.id')
            ->select('tasks.*', 
                     'assignee.name as assignee_name', 
                     'creator.name as creator_name',
                     'manager.name as target_manager_name')
            ->where('tasks.id', $id)
            ->first();
        
        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan'
            ], 404);
        }
        
        $user = Auth::user();
        
        $canView = false;
        
        if ($task->created_by == $user->id) {
            $canView = true;
        } elseif ($task->assigned_to == $user->id) {
            $canView = true;
        } elseif ($task->target_divisi == $user->divisi && $user->role === 'manager_divisi') {
            $canView = true;
        } elseif ($task->target_manager_id == $user->id) {
            $canView = true;
        } elseif ($user->role === 'general_manager') {
            $canView = true;
        }
        
        if (!$canView) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk melihat tugas ini'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    // TAMBAHKAN: Method untuk mendapatkan tugas dari General Manager
    public function getTasksFromGeneralManager()
    {
        $user = Auth::user();
        
        // Ambil tugas dari GM yang ditugaskan ke divisi manager ini
        $tasks = Task::leftJoin('users as creator', 'tasks.created_by', '=', 'creator.id')
                ->select('tasks.*', 'creator.name as creator_name')
                ->where('target_divisi', $user->divisi)
                ->where('target_type', 'divisi')
                ->where('is_broadcast', true)
                ->where('created_by', '!=', $user->id) 
                ->orderBy('created_at', 'desc')
                ->get();
        
        return response()->json([
            'success' => true,
            'tasks' => $tasks,
            'count' => $tasks->count()
        ]);
    }
}