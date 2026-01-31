<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DataProjectController extends Controller
{
    /**
     * Display a listing of the resource for General Manager.
     */
    public function index(Request $request)
    {
        $query = Project::with(['layanan', 'penanggungJawab']);

        // Search Logic (Search by Nama Project atau Nama Penanggung Jawab)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('penanggungJawab', function($subQ) use ($searchTerm) {
                      $subQ->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // PERBAIKAN: Gunakan penanggung_jawab_id, bukan manager_id
        // Filter by Penanggung Jawab (Dropdown filter di halaman GM)
        if ($request->has('penanggung_jawab_id') && $request->penanggung_jawab_id != '') {
            $query->where('penanggung_jawab_id', $request->penanggung_jawab_id);
        }

        $projects = $query->orderBy('id', 'desc')->paginate(3)->withQueryString();

        // Ambil daftar manager divisi untuk dropdown filter
        $managers = User::where('role', 'manager_divisi')
            ->orderBy('name')
            ->get();

        return view('general_manajer.data_project', compact('projects', 'managers'));
    }

    /**
     * Display a listing of the resource for Admin.
     */
    public function admin(Request $request)
    {
        $query = Project::with('layanan');

        // Search Logic
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('nama', 'like', '%' . $searchTerm . '%');
        }

        // PERUBAHAN: Gunakan variabel $project (bukan $projects)
        $project = $query->orderBy('id', 'desc')->paginate(3);
        
        $layanans = Layanan::orderBy('id', 'desc')->get();

        // PERUBAHAN: Kirim 'project' (singular) bukan 'projects'
        return view('admin.data_project', compact('project', 'layanans'));
    }

    /**
     * Display a listing of the resource for Manager Divisi.
     */
    public function managerDivisi(Request $request)
    {
        $user = auth()->user(); // User yang sedang login (Manager Divisi)

        $query = Project::with(['layanan', 'penanggungJawab'])
            ->where('penanggung_jawab_id', $user->id); // HANYA project miliknya

        // Search logic untuk Manager Divisi
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $project = $query->orderBy('id', 'desc')->paginate(3)->withQueryString();

        return view('manager_divisi.data_project', compact('project'));
    }

    /**
     * API: Get Projects Dropdown untuk Manager Divisi
     * Digunakan di modal Create Task (hanya muncul project miliknya).
     */
    public function getManagerProjectsDropdown()
    {
        $user = auth()->user();
        
        $projects = Project::where('penanggung_jawab_id', $user->id)
            ->select(['id', 'nama', 'status', 'deadline'])
            ->orderBy('nama', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    // PERBAIKAN VALIDASI:
    $validator = Validator::make($request->all(), [
        'layanan_id' => 'required|exists:layanans,id',
        'nama' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'harga' => 'required|numeric|min:0',
        'deadline' => 'required|date', // Ubah dari 'Y-m-d H:i' menjadi 'date'
        'penanggung_jawab_id' => 'nullable|exists:users,id',
    ], [
        'deadline.date' => 'Format deadline harus tanggal (YYYY-MM-DD)',
        'harga.numeric' => 'Harga harus berupa angka',
        'layanan_id.required' => 'Pilih layanan terlebih dahulu',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    // Pastikan deadline dalam format yang benar
    $deadline = $request->deadline . ' 23:59:59'; // Tambah waktu jika perlu

    $project = Project::create([
        'layanan_id' => $request->layanan_id,
        'nama' => $request->nama,
        'deskripsi' => $request->deskripsi,
        'harga' => $request->harga,
        'deadline' => $deadline, // Gunakan deadline yang sudah diformat
        'progres' => 0,
        'status' => 'Pending',
        'penanggung_jawab_id' => $request->penanggung_jawab_id ?? auth()->id(),
        'created_by' => auth()->id(),
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
        $project = Project::with(['layanan', 'penanggungJawab', 'tasks'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * Update the specified resource in storage (General Manager/Admin Update).
     */
 public function update(Request $request, string $id)
{
    // SET HEADER UNTUK MENJAMIN RESPONSE JSON
    $request->headers->set('Accept', 'application/json');
    
    $validator = Validator::make($request->all(), [
        // PERBAIKI: Hapus layanan_id karena tidak ada di form edit
        'nama' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'harga' => 'required|numeric|min:0',
        'deadline' => 'required|date',
        // 'penanggung_jawab_id' juga mungkin tidak ada di form edit
    ], [
        'nama.required' => 'Nama project harus diisi',
        'deskripsi.required' => 'Deskripsi harus diisi',
        'harga.required' => 'Harga harus diisi',
        'harga.numeric' => 'Harga harus berupa angka',
        'deadline.required' => 'Deadline harus diisi',
        'deadline.date' => 'Format tanggal tidak valid',
    ]);

    if ($validator->fails()) {
        // PASTIKAN mengembalikan JSON
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()->toArray()
        ], 422, [], JSON_UNESCAPED_UNICODE);
    }

    try {
        $project = Project::findOrFail($id);
        
        $project->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'deadline' => $request->deadline,
            // Jangan update layanan_id karena tidak ada di form
            // Jangan update penanggung_jawab_id karena tidak ada di form
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Project berhasil diupdate!',
            'data' => $project
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Update Progress & Status dari Manager Divisi.
     */
    public function updateManager(Request $request, string $id)
    {
        // SECURITY: Pastikan manager hanya update project miliknya
        $project = Project::where('id', $id)
                        ->where('penanggung_jawab_id', auth()->id())
                        ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'progres' => 'required|integer|min:0|max:100',
            'status' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Normalisasi status
        $statusRaw = $request->status;
        $statusMap = [
            'pending' => 'Pending',
            'proses'  => 'Proses',
            'selesai' => 'Selesai'
        ];
        
        $statusFinal = $statusMap[strtolower($statusRaw)] ?? ucfirst(strtolower($statusRaw));
        
        // Validasi manual status
        $allowedStatus = ['Pending', 'Proses', 'Selesai'];
        if (!in_array($statusFinal, $allowedStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid. Pilihan: Pending, Proses, Selesai'
            ], 422);
        }

        // LOGIKA OTOMATISASI STATUS BERDASARKAN PROGRES
        $progres = (int) $request->progres;
        
        if ($progres == 100) {
            $statusFinal = 'Selesai';
        } elseif ($progres > 0 && $statusFinal === 'Pending') {
            $statusFinal = 'Proses';
        }
        
        $project->update([
            'progres' => $progres,
            'status' => $statusFinal,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Progres dan status berhasil diupdate!',
            'data' => $project
        ]);
    }

    /**
     * Update General Manager (Assign Penanggung Jawab).
     */
    public function updategeneral(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'deadline' => 'required|date_format:Y-m-d H:i',
            'penanggung_jawab_id' => 'nullable|exists:users,id',
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
            'penanggung_jawab_id' => $request->penanggung_jawab_id,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Project berhasil diupdate!',
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

    /**
     * Filter/Refresh Table via AJAX (Legacy / Optional).
     */
    public function filterByUser(Request $request)
    {
        $user = auth()->user();
        $projects = Project::query()->with(['layanan', 'penanggungJawab']);

        // Security: Jika role manager, hanya lihat miliknya sendiri
        if ($user->role === 'manager_divisi') {
            $projects->where('penanggung_jawab_id', $user->id);
        }

        // Filter by user id dari request (hanya relevan untuk GM/Admin)
        if ($request->has('user_id') && $request->user_id) {
            if ($user->role !== 'manager_divisi') {
                $projects->where('penanggung_jawab_id', $request->user_id);
            }
        }

        $projects = $projects->orderBy('id', 'desc')->paginate(3);

        return response()->json([
            'html' => view('manager_divisi.partials.project_table', compact('projects'))->render(),
            'total' => $projects->total()
        ]);
    }
}