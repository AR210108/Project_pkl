<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Divisi; // TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    /**
     * Halaman utama user management
     */
public function index()
{
    $users = User::with('divisi')->get();
    $divisis = Divisi::all(); // PERBAIKAN: Hapus where('divisi')
    return view('admin.user', compact('users', 'divisis'));
}

    /**
     * API endpoint untuk mendapatkan data users
     * Route: /admin/users/data
     */
    public function getData(): JsonResponse
    {
        try {
            $users = User::select('id', 'name', 'email', 'role', 'divisi_id')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk mendapatkan data divisi untuk dropdown
     * Route: /admin/divisis/list
     */
public function getDivisis(): JsonResponse
{
    try {
        $divisis = Divisi::select('id', 'divisi')->get();
        
        return response()->json([
            'success' => true,
            'data' => $divisis
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * API endpoint untuk select dropdown (minimal data)
     * Route: /admin/users/data (alias untuk compatibility)
     */
    public function data(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => User::select('id', 'name', 'role')->orderBy('name')->get()
        ]);
    }

    /**
     * Simpan user baru
     */
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:5',
            'role'      => 'required',
            'divisi_id' => 'nullable|exists:divisi,id',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => bcrypt($validated['password']),
            'role'      => $validated['role'],
            'divisi_id' => $validated['divisi_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data'    => $user
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors'  => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error'   => $e->getMessage()
        ], 500);
    }
}


    /**
     * Update user
     */
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|in:admin,karyawan,general_manager,manager_divisi,finance,owner',
        'divisi_id' => 'nullable|exists:divisi,id'
    ]);

    $user = User::findOrFail($id);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;
    $user->divisi_id = $request->divisi_id; // Update divisi_id
    
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }
    
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'User berhasil diperbarui',
        'data' => $user
    ]);
}

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Cek jika user sedang login
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.user')->with('error', 'Tidak dapat menghapus user yang sedang login');
        }
        
        $user->delete();
        
        return redirect()->route('admin.user')->with('success', 'User berhasil dihapus');
    }

    /**
     * Get user untuk edit modal
     */
    public function getUser($id): JsonResponse // OPTIONAL: Tambah method ini
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}