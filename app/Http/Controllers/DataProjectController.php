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
        'penanggung_jawab_id' => 'required|exists:users,id', // TAMBAHKAN VALIDASI INI
        'nama' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'harga' => 'nullable|numeric|min:0',
        'deadline' => 'required|date',
        'progres' => 'nullable|integer|min:0|max:100',
        'status' => 'required|in:Pending,Dalam Pengerjaan,Selesai,Dibatalkan'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    // Validasi: pastikan yang dipilih adalah manager divisi
    $penanggungJawab = User::find($request->penanggung_jawab_id);
    if ($penanggungJawab->role !== 'manager_divisi') {
        return response()->json([
            'success' => false,
            'message' => 'Penanggung jawab harus merupakan Manager Divisi'
        ], 422);
    }

    $project = Project::create([
        'layanan_id' => $request->layanan_id,
        'penanggung_jawab_id' => $request->penanggung_jawab_id, // SIMPAN PENANGGUNG JAWAB
        'nama' => $request->nama,
        'deskripsi' => $request->deskripsi,
        'harga' => $request->harga,
        'deadline' => $request->deadline,
        'progres' => $request->progres ?? 0,
        'status' => $request->status,
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
public function update(Request $request, string $id)
{
    $validator = Validator::make($request->all(), [
        'nama' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'penanggung_jawab_id' => 'nullable|exists:users,id', // TAMBAHKAN
        'harga' => 'nullable|numeric|min:0',
        'deadline' => 'required|date',
        'progres' => 'required|integer|min:0|max:100',
        'status' => 'required|in:Pending,Dalam Pengerjaan,Selesai,Dibatalkan'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $project = Project::findOrFail($id);
    
    // Update penanggung jawab jika ada
    $data = $request->all();
    
    // Validasi penanggung jawab jika diubah
    if ($request->has('penanggung_jawab_id') && $request->penanggung_jawab_id) {
        $penanggungJawab = User::find($request->penanggung_jawab_id);
        if ($penanggungJawab->role !== 'manager_divisi') {
            return response()->json([
                'success' => false,
                'message' => 'Penanggung jawab harus merupakan Manager Divisi'
            ], 422);
        }
    }
    
    $project->update($data);
    
    return response()->json;

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
}
