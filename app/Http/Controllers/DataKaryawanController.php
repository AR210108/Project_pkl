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
            'gaji' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
        ]);

        Karyawan::create($request->only('nama','jabatan','gaji','alamat','kontak'));

        return redirect()->back()->with('success','Data disimpan');
    }

    public function update(Request $request, $id)
    {
        $k = Karyawan::findOrFail($id);
        $k->update($request->only('nama','jabatan','gaji','alamat','kontak'));
        return redirect()->back()->with('success','Data diperbarui');
    }

    public function destroy($id)
    {
        Karyawan::findOrFail($id)->delete();
        return redirect()->back()->with('success','Data dihapus');
    }
}
