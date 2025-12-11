<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengumuman = Pengumuman::latest()->get();
        return view('admin.pengumuman', compact('pengumuman'));
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
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'judul_informasi' => 'required|string|max:255',
            'isi_pesan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('lampiran');
        
        // Handle file upload
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('pengumuman', $filename, 'public');
            $data['lampiran'] = $filename;
        }

        $pengumuman = Pengumuman::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dibuat!',
            'data' => $pengumuman
        ]);
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
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'judul_informasi' => 'required|string|max:255',
            'isi_pesan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('lampiran');
        
        // Handle file upload
        if ($request->hasFile('lampiran')) {
            // Delete old file if exists
            if ($pengumuman->lampiran) {
                Storage::disk('public')->delete('pengumuman/' . $pengumuman->lampiran);
            }
            
            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('pengumuman', $filename, 'public');
            $data['lampiran'] = $filename;
        }

        $pengumuman->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil diperbarui!',
            'data' => $pengumuman
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