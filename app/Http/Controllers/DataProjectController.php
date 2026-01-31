<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Layanan;

class DataProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Project::with(['layanan', 'penanggungJawab'])
            ->orderBy('id', 'desc')
            ->paginate(3);

        // Ambil daftar manager divisi untuk dropdown
        $managers = User::where('role', 'manager_divisi')
            ->orderBy('name')
            ->get();

        return view('general_manajer.data_project', compact('project', 'managers'));
    }
    public function admin()
    {
        $project = Project::orderBy('id', 'desc')->paginate(3);
        $layanans = Layanan::orderBy('id', 'desc')->get();

        return view('admin.data_project', compact('project', 'layanans'));
    }

    public function managerDivisi(Request $request)
    {
        $user = auth()->user(); // User yang sedang login (Manager Divisi)

        // Hanya tampilkan project yang dia sebagai penanggung jawab
        $projects = Project::with(['layanan', 'penanggungJawab'])
            ->where('penanggung_jawab_id', $user->id) // HANYA project miliknya
            ->orderBy('id', 'desc')
            ->paginate(3);

        return view('manager_divisi.data_project', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'layanan_id' => 'required|exists:layanans,id',
        'nama' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'harga' => 'required|numeric|min:0',
        'deadline' => 'required|date',
        // Tidak perlu validasi status karena default
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $project = Project::create([
        'layanan_id' => $request->layanan_id,
        'nama' => $request->nama,
        'deskripsi' => $request->deskripsi,
        'harga' => $request->harga,
        'deadline' => $request->deadline,
        'progres' => 0, // Default 0%
        'status' => 'Pending', // Default Pending
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Project berhasil ditambahkan!',
        'data' => $project
    ]);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
/**
 * Update the specified resource in storage.
 */
public function update(Request $request, string $id)
{
    $validator = Validator::make($request->all(), [
        'nama' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'deadline' => 'required|date',
        // HAPUS 'harga' karena tidak diedit
    ]);

    if ($validator->fails()) {
        \Log::error('Update Validation Error:', $validator->errors()->toArray());
        \Log::error('Request Data:', $request->all());
        
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $project = Project::findOrFail($id);
        
        \Log::info('Updating project ID: ' . $id, [
            'old_data' => $project->toArray(),
            'new_data' => $request->all()
        ]);
        
        $project->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
            // Harga tidak diupdate karena tidak ada di form edit
        ]);
        
        \Log::info('Project updated successfully:', $project->toArray());
        
        return response()->json([
            'success' => true,
            'message' => 'Project berhasil diupdate!',
            'data' => $project
        ]);

    } catch (\Exception $e) {
        \Log::error('Update Error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupdate project: ' . $e->getMessage()
        ], 500);
    }
}

// Tambahkan method baru untuk update dari manager divisi
public function updateManager(Request $request, string $id)
{
    $validator = Validator::make($request->all(), [
        'progres' => 'required|integer|min:0|max:100',
        'status' => 'required|string', // Ubah menjadi string
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $project = Project::findOrFail($id);
    
    // Normalisasi status
    $status = $request->status;
    $status = ucfirst(strtolower($status)); // "proses" -> "Proses"
    
    // Validasi manual status
    $allowedStatus = ['Pending', 'Proses', 'Selesai'];
    if (!in_array($status, $allowedStatus)) {
        return response()->json([
            'success' => false,
            'message' => 'Status tidak valid. Pilihan: Pending, Proses, Selesai'
        ], 422);
    }
    
    $project->update([
        'progres' => (int) $request->progres,
        'status' => $status, // Sekarang string dengan value yang benar
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Progres dan status berhasil diupdate!',
        'data' => $project
    ]);
}

public function updategeneral(Request $request, string $id)
{
    $validator = Validator::make($request->all(), [
        'nama' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'harga' => 'required|numeric|min:0',
        'deadline' => 'required|date',
        'penanggung_jawab_id' => 'nullable|exists:users,id', // TAMBAH INI
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $project = Project::findOrFail($id);
    
    $project->update([
        'nama' => $request->nama,
        'deskripsi' => $request->deskripsi,
        'harga' => $request->harga,
        'deadline' => $request->deadline,
        'penanggung_jawab_id' => $request->penanggung_jawab_id, // TAMBAH INI
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Penanggung jawab berhasil ditetapkan!',
        'data' => $project
    ]);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'project berhasil dihapus!'
        ]);
    }

    public function filterByUser(Request $request)
    {
        $user = auth()->user();
        $projects = Project::query();

        if ($request->has('user_id') && $request->user_id) {
            $projects->where('penanggung_jawab_id', $request->user_id);
        }

        $projects = $projects->orderBy('id', 'desc')->paginate(3);

        return response()->json([
            'html' => view('manager_divisi.partials.project_table', compact('projects'))->render(),
            'total' => $projects->total()
        ]);
    }
    public function syncFromLayanan($layananId)
{
    try {
        $layanan = Layanan::findOrFail($layananId);
        $projects = Project::where('layanan_id', $layananId)->get();
        
        $updatedCount = 0;
        
        foreach ($projects as $project) {
            $project->update([
                'nama' => $layanan->nama_layanan,
                'deskripsi' => $layanan->deskripsi,
                'harga' => $layanan->harga,
            ]);
            $updatedCount++;
        }
        
        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} project berhasil disinkronisasi dari layanan.",
            'data' => [
                'layanan' => $layanan,
                'updated_projects_count' => $updatedCount
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal sinkronisasi: ' . $e->getMessage()
        ], 500);
    }
}
}
