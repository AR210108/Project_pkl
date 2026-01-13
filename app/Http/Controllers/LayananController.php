<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::latest()->paginate(10);
        return view('admin/data_layanan', compact('layanans'));
    }

    public function indexLayanan(Request $request)
    {
        $query = Layanan::query();
        
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        $pelayanan = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $search = $request->query('search');
        
        return view('general_manajer.data_layanan', compact('pelayanan', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required',
            'harga'      => 'required|integer',
            'durasi'     => 'required',
            'deskripsi'  => 'required',
            'kategori'   => 'required',
        ]);

        Layanan::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'durasi' => $request->durasi,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        return back()->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return response()->json($layanan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'required',
            'harga'      => 'required|integer',
            'durasi'     => 'required',
            'deskripsi'  => 'required',
            'kategori'   => 'required',
        ]);

        $layanan = Layanan::findOrFail($id);
        $layanan->update([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'durasi' => $request->durasi,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        return back()->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Layanan::findOrFail($id)->delete();

        return back()->with('success', 'Layanan berhasil dihapus!');
    }
}
