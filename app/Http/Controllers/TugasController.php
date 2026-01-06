<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen tugas.
     */
    public function index()
    {
        return view('general_manajer.kelola_tugas');
    }

    /**
     * Mengambil data tugas untuk datatable (JSON).
     */
    public function getData(Request $request)
    {
        $tugas = Tugas::latest()->paginate(3); // 3 items per page sesuai dengan JS Anda
        
        return response()->json($tugas);
    }

    /**
     * Menyimpan tugas baru ke database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date',
            'karyawan' => 'required|string',
            'manajer' => 'required|string',
            'status' => 'required|in:To Do,In Progress,Done',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Tugas::create($request->all());

        return response()->json(['message' => 'Tugas berhasil ditambahkan!']);
    }

    /**
     * Menampilkan detail tugas tertentu.
     */
    public function show($id)
    {
        $tugas = Tugas::findOrFail($id);
        return response()->json($tugas);
    }

    /**
     * Memperbarui tugas yang ada.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date',
            'karyawan' => 'required|string',
            'manajer' => 'required|string',
            'status' => 'required|in:To Do,In Progress,Done',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tugas = Tugas::findOrFail($id);
        $tugas->update($request->all());

        return response()->json(['message' => 'Tugas berhasil diperbarui!']);
    }

    /**
     * Menghapus tugas dari database.
     */
    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();

        return response()->json(['message' => 'Tugas berhasil dihapus!']);
    }
}