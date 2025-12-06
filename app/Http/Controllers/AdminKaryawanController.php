<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class adminKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $karyawan = Karyawan::with('jabatan')->latest()->paginate(5);
    return view('admin.data_karyawan', compact('karyawan'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatan,id', // Pastikan jabatan_id ada di tabel jabatan
            'gaji' => 'required|string|max:50',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validasi foto
        ]);

        // Simpan data ke database
        Karyawan::create($request->all());

        // Return response JSON untuk AJAX
        return response()->json(['success' => 'Data karyawan berhasil ditambahkan!']);
    }

    /**
     * Display the specified resource.
     * Digunakan untuk mengambil data saat edit
     */
    public function show($id)
    {
        $karyawan = Karyawan::with('jabatan')->findOrFail($id);
        return response()->json($karyawan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatan,id',
            'gaji' => 'required|string|max:50',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($request->all());

        return response()->json(['success' => 'Data karyawan berhasil diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return response()->json(['success' => 'Data karyawan berhasil dihapus!']);
    }
}