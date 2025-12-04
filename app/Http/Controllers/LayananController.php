<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::latest()->paginate(10);
        return view('admin/data_layanan', compact('layanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required',
            'harga'        => 'nullable|integer',
            'status'       => 'required',
        ]);

        Layanan::create($request->all());

        return back()->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->update($request->all());

        return back()->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Layanan::findOrFail($id)->delete();

        return back()->with('success', 'Layanan berhasil dihapus!');
    }
}
