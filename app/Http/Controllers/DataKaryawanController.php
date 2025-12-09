<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class DataKaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::orderBy('id', 'desc')->get();
        return view('admin.data_karyawan', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'gaji' => 'required|numeric',
            'alamat' => 'required',
            'kontak' => 'required',
        ]);

        Karyawan::create([
            'nama'      => $request->nama,
            'jabatan'   => $request->jabatan,
            'gaji'      => $request->gaji,
            'alamat'    => $request->alamat,
            'kontak'    => $request->kontak,
        ]);

        return redirect()->back()->with('success','Data berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'gaji' => 'required|numeric',
            'alamat' => 'required',
            'kontak' => 'required',
        ]);

        $k = Karyawan::findOrFail($id);

        $k->update([
            'nama'      => $request->nama,
            'jabatan'   => $request->jabatan,
            'gaji'      => $request->gaji,
            'alamat'    => $request->alamat,
            'kontak'    => $request->kontak,
        ]);

        return redirect()->back()->with('success','Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $k = Karyawan::findOrFail($id);
        $k->delete();

        return redirect()->back()->with('success','Data berhasil dihapus');
    }
}
