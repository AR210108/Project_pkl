<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Menampilkan halaman data karyawan dengan semua pegawai.
     */
    public function index()
    {
        // Terima filter dari query params
        $query = Pegawai::query();

        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($divisi = request('divisi')) {
            $query->where('divisi', $divisi);
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        $pegawai = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('general_manajer/data_karyawan', compact('pegawai'));
    }

    /**
     * Menyimpan data pegawai baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'telp' => 'required|string|max:15',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'status' => 'required|in:Magang,Karyawan Tetap',
            'email' => 'required|email|unique:pegawai,email',
        ]);

        Pegawai::create($validated);

        return redirect()->route('pegawai.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    /**
     * Menampilkan data pegawai untuk diedit.
     */
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        // Kita akan mengirim data sebagai JSON untuk diproses oleh JavaScript
        return response()->json($pegawai);
    }

    /**
     * Memperbarui data pegawai yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'telp' => 'required|string|max:15',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'status' => 'required|in:Magang,Karyawan Tetap',
            'email' => 'required|email|unique:pegawai,email,'.$pegawai->id,
        ]);

        $pegawai->update($validated);

        return redirect()->route('pegawai.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    /**
     * Menghapus data pegawai.
     */
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Karyawan berhasil dihapus!');
    }
}