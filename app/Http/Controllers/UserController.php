<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $users = User::all();
        return view('admin.user', compact('users'));
    }

    /**
     * API endpoint untuk mendapatkan data users
     * Route: /admin/users/data
     */
    public function getData(): JsonResponse
    {
        try {
            $users = User::select('id', 'name', 'email', 'role', 'divisi')
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,karyawan,general_manager,manager_divisi,finance,owner',
            'password' => 'required|min:5'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'divisi' => $request->divisi,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.user')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,karyawan,general_manager,manager_divisi,finance,owner'
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->divisi = $request->divisi;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('admin.user')->with('success', 'User berhasil diperbarui');
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
}