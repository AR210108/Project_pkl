<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminKaryawanController extends Controller
{
    /**
     * =======================
     * ADMIN VIEW
     * =======================
     */
    public function index(Request $request)
    {
        $query = Karyawan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhere('alamat', 'LIKE', "%{$search}%");
            });
        }

        $karyawan = $query->paginate(10);

        $users = User::whereNotIn('id', function ($q) {
            $q->select('user_id')
              ->from('karyawan')
              ->whereNotNull('user_id');
        })->get(['id', 'name', 'divisi', 'role']);

        return view('admin.data_karyawan', compact('karyawan', 'users'));
    }

    /**
     * =======================
     * GENERAL MANAGER VIEW
     * =======================
     */
    public function karyawanGeneral(Request $request)
    {
        $query = Karyawan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhere('alamat', 'LIKE', "%{$search}%");
            });
        }

        $karyawan = $query->paginate(10);

        return view('general_manajer.data_karyawan', compact('karyawan'));
    }

    /**
     * =======================
     * MANAGER DIVISI VIEW
     * =======================
     */
    public function karyawanDivisi(Request $request)
    {
        $user = auth()->user();

        $query = Karyawan::where('divisi', $user->divisi);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhere('alamat', 'LIKE', "%{$search}%");
            });
        }

        $karyawan = $query->paginate(10);

        $users = User::where('divisi', $user->divisi)
            ->whereNotIn('id', function ($q) {
                $q->select('user_id')
                  ->from('karyawan')
                  ->whereNotNull('user_id');
            })
            ->get(['id', 'name', 'divisi', 'role']);

        return view('manager_divisi.daftar_karyawan', [
            'karyawan' => $karyawan,
            'users' => $users,
            'divisiManager' => $user->divisi
        ]);
    }

    /**
     * =======================
     * STORE
     * =======================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'alamat' => 'required|string|max:500',
            'kontak' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                ->store('karyawan', 'public');
        }

        $karyawan = Karyawan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => $karyawan
        ], 201);
    }

    /**
     * =======================
     * UPDATE
     * =======================
     */
    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'divisi' => 'required|string|max:100',
            'alamat' => 'required|string|max:500',
            'kontak' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {

            if ($karyawan->foto) {
                Storage::disk('public')->delete($karyawan->foto);
            }

            $validated['foto'] = $request->file('foto')
                ->store('karyawan', 'public');
        }

        $karyawan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui',
            'data' => $karyawan
        ]);
    }

    /**
     * =======================
     * DELETE
     * =======================
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        if ($karyawan->foto) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil dihapus'
        ]);
    }
}
