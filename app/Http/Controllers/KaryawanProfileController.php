<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanProfileController extends Controller
{
    public function index()
    {
         $karyawan = Auth::user(); // pakai user dari Laravel auth

    if (!$karyawan) {
        return redirect('/login'); // redirect ke login kalau belum login
    }

    return view('karyawan.profile', compact('karyawan'));
}
    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $karyawan = session('karyawan');

        // 🔥 kalau pakai database (opsional)
        /*
        Karyawan::where('id', $karyawan['id'])->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        */

        // Update session
        session([
            'karyawan' => array_merge($karyawan, [
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ])
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
