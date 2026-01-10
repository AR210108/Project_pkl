<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $pengumuman = Pengumuman::with('users')->latest()->get();
    $users = User::all(); // Pastikan model User di-import
    
    return view('admin.pengumuman', [
        'pengumuman' => $pengumuman,
        'users' => $users
    ]);
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak diperlukan karena menggunakan modal
        return response()->json(['message' => 'Create form is displayed in modal']);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'judul'      => 'required|string|max:255',
            'isi_pesan' => 'required|string',
            'users'     => 'required|array|min:1',
            'users.*'   => 'exists:users,id',
            'lampiran'  => 'nullable|file|mimes:png,jpg,jpeg,pdf',
        ]);

        // Upload file
        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')
                ->store('pengumuman', 'public');
        }

        // Tentukan kepada
        $kepada = count($validated['users']) > 0 ? 'specific' : 'all';

        // Simpan pengumuman
        $pengumuman = Pengumuman::create([
            'judul'      => $validated['judul'],
            'isi_pesan' => $validated['isi_pesan'],
            'kepada'    => $kepada,
            'lampiran'  => $lampiranPath,
        ]);

        // SIMPAN RELASI USER (INI YANG BENAR)
        $pengumuman->users()->sync($validated['users']);

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dibuat',
            'data'    => $pengumuman->load('users'),
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors'  => $e->errors(),
        ], 422);
    }
}



    /**
     * Display the specified resource.
     */
    public function show(Pengumuman $pengumuman)
    {
        return response()->json($pengumuman);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengumuman $pengumuman)
    {
        return response()->json($pengumuman);
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Pengumuman $pengumuman)
{
    $validated = $request->validate([
        'judul'      => 'required|string|max:255',
        'isi_pesan' => 'required|string',
        'users'     => 'required|array|min:1',
        'users.*'   => 'exists:users,id',
        'lampiran'  => 'nullable|file|mimes:png,jpg,jpeg,pdf',
    ]);

    $data = [
        'judul'      => $validated['judul'],
        'isi_pesan' => $validated['isi_pesan'],
        'kepada'    => count($validated['users']) > 0 ? 'specific' : 'all',
    ];

    if ($request->hasFile('lampiran')) {
        if ($pengumuman->lampiran) {
            Storage::disk('public')->delete($pengumuman->lampiran);
        }

        $data['lampiran'] = $request->file('lampiran')
            ->store('pengumuman', 'public');
    }

    $pengumuman->update($data);

    // SYNC USER
    $pengumuman->users()->sync($validated['users']);

    return response()->json([
        'success' => true,
        'message' => 'Pengumuman berhasil diperbarui',
        'data'    => $pengumuman->load('users'),
    ]);
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        // Delete file if exists
        if ($pengumuman->lampiran) {
            Storage::disk('public')->delete('pengumuman/' . $pengumuman->lampiran);
        }
        
        $pengumuman->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dihapus!'
        ]);
    }
}