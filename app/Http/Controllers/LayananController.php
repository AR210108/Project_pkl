<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::latest()->paginate(10);
        return view('admin/data_layanan', compact('layanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required',
            'harga'        => 'nullable|integer',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Handle photo upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/layanan', $fotoName);
            $data['foto'] = 'layanan/' . $fotoName;
        }

        Layanan::create($data);

        return back()->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);
        
        $request->validate([
            'nama_layanan' => 'required',
            'harga'        => 'nullable|integer',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($layanan->foto) {
                Storage::delete('public/' . $layanan->foto);
            }
            
            $foto = $request->file('foto');
            $fotoName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/layanan', $fotoName);
            $data['foto'] = 'layanan/' . $fotoName;
        }
        
        $layanan->update($data);

        return back()->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);
        
        // Delete photo if exists
        if ($layanan->foto) {
            Storage::delete('public/' . $layanan->foto);
        }
        
        $layanan->delete();

        return back()->with('success', 'Layanan berhasil dihapus!');
    }
    public function landingPage()
{
    $layanans = Layanan::latest()->get(); 
    
    // Debug: Baris ini akan menampilkan data $layanans dan menghentikan proses.
    // Jika muncul data, berarti controller berjalan baik.
    // Jika tidak, ada masalah dengan pengambilan data.
    // dd($layanans); 
    
    return view('home', compact('layanans'));
}
    
}