<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

        // Filter by Penanggung Jawab
        if ($request->has('penanggung_jawab_id') && $request->penanggung_jawab_id != '') {
            $query->where('penanggung_jawab_id', $request->penanggung_jawab_id);
        }

        $projects = $query->orderBy('id', 'desc')->paginate(3)->withQueryString();

        // Ambil daftar manager divisi untuk dropdown filter
        $managers = User::where('role', 'manager_divisi')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'divisi_id']);

        return view('general_manajer.data_project', compact('projects', 'managers'));
    }

    public function admin(Request $request)
    {
        $query = Project::query();

        // SEARCH (nama & deskripsi)
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->q . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->q . '%');
            });
        }

        // FILTER STATUS PENGERJAAN
        if ($request->filled('status_pengerjaan')) {
            $query->where('status_pengerjaan', $request->status_pengerjaan);
        }

        // FILTER STATUS KERJASAMA
        if ($request->filled('status_kerjasama')) {
            $query->where('status_kerjasama', $request->status_kerjasama);
        }

        // FILTER TANGGAL MULAI PENGERJAAN
        if ($request->filled('tanggal_mulai_pengerjaan')) {
            $query->whereDate('tanggal_mulai_pengerjaan', $request->tanggal_mulai_pengerjaan);
        }

        // FILTER TANGGAL SELESAI PENGERJAAN
        if ($request->filled('tanggal_selesai_pengerjaan')) {
            $query->whereDate('tanggal_selesai_pengerjaan', $request->tanggal_selesai_pengerjaan);
        }

        $project = $query
            ->with(['invoice', 'penanggungJawab'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

    $invoices = Invoice::query();

    $invoices = $invoices->orderBy('id', 'desc')->get();

        return view('admin.data_project', compact('project', 'invoices'));
    }

    /**
     * Display a listing of the resource for Manager Divisi.
     * PERBAIKAN: Menggunakan $projects (plural) bukan $project
     */
    public function managerDivisi(Request $request)
    {
        $user = auth()->user();

        // Log untuk debugging
        Log::info('Manager Divisi accessing projects', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'user_divisi' => $user->divisi,
            'user_divisi_id' => $user->divisi_id
        ]);

        $query = Project::with(['layanan', 'penanggungJawab'])
            ->where('penanggung_jawab_id', $user->id);

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // PERBAIKAN: Gunakan $projects (plural) untuk konsistensi
        $projects = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Log hasil query
        Log::info('Projects found for manager divisi', [
            'count' => $projects->count(),
            'user_id' => $user->id,
            'query' => $query->toSql()
        ]);

        // Jika tidak ada project, log warning
        if ($projects->count() === 0) {
            Log::warning('No projects found for manager divisi', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);
        }

        // PERBAIKAN: Kirim $projects (plural) ke view
        return view('manager_divisi.data_project', compact('projects'));
    }

    /**
     * API: Get Projects Dropdown untuk Manager Divisi
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
        $validator = Validator::make($request->all(), [
            'layanan_id' => 'required|exists:layanans,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            // Biarkan tanggal pengerjaan/kerjasama kosong saat pembuatan; diisi lewat edit
            'tanggal_mulai_pengerjaan' => 'nullable|date',
            'tanggal_selesai_pengerjaan' => 'nullable|date',
            'tanggal_mulai_kerjasama' => 'nullable|date',
            'tanggal_selesai_kerjasama' => 'nullable|date',
            'status_pengerjaan' => 'required|in:pending,dalam_pengerjaan,selesai,dibatalkan',
            'status_kerjasama' => 'required|in:aktif,selesai,ditangguhkan',
            'progres' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $deadline = $request->deadline . ' 23:59:59';

        $project = Project::create([
            'layanan_id' => $request->layanan_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'deadline' => $deadline,
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
     * Update the specified resource in storage.
     * Untuk General Manager: update semua field termasuk penanggung jawab
     */
    public function update(Request $request, string $id)
    {
        Log::info('DataProjectController update method called', [
            'id' => $id, 
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);
        
        // Debug: log semua data yang diterima
        Log::info('All request data:', $request->all());
        
        // Validasi untuk semua field yang dikirim dari form edit General Manager
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai_pengerjaan' => 'required|date',
            'tanggal_selesai_pengerjaan' => 'nullable|date|after:tanggal_mulai_pengerjaan',
            'tanggal_mulai_kerjasama' => 'nullable|date',
            'tanggal_selesai_kerjasama' => 'nullable|date|after:tanggal_mulai_kerjasama',
            // Hapus pengeditan langsung untuk progres dan status_pengerjaan dari admin update
            'status_kerjasama' => 'required|in:aktif,selesai,ditangguhkan',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed in update method', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
                'input_received' => $request->all()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $project = Project::findOrFail($id);
            
            Log::info('Project found for update', [
                'project_id' => $project->id,
                'current_data' => [
                    'nama' => $project->nama,
                    'penanggung_jawab_id' => $project->penanggung_jawab_id,
                    'status' => $project->status,
                    'progres' => $project->progres,
                    'harga' => $project->harga
                ]
            ]);
            
            // Debug: log data sebelum update
            Log::info('Data before update:', [
                'old_penanggung_jawab_id' => $project->penanggung_jawab_id,
                'new_penanggung_jawab_id' => $request->penanggung_jawab_id
            ]);
            
            $updateData = [
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'deadline' => $request->deadline,
                'progres' => $request->progres,
                'status' => $request->status,
                'penanggung_jawab_id' => $request->penanggung_jawab_id ?: null,
            ];
            
            Log::info('Updating project with data', $updateData);
            
            $project->update($updateData);
            
            DB::commit();
            
            Log::info('Project updated successfully', [
                'project_id' => $project->id,
                'updated_data' => $project->fresh()->toArray()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Project berhasil diupdate!',
                'data' => $project->fresh(['layanan', 'penanggungJawab'])
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Project not found: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Project tidak ditemukan!'
            ], 404);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'id' => $id,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Progress & Status dari Manager Divisi.
     */
    public function updateManager(Request $request, string $id)
    {
        Log::info('UpdateManager method called', [
            'id' => $id,
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]);

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
        
        $statusRaw = $request->status;
        $statusMap = [
            'pending' => 'Pending',
            'proses'  => 'Proses',
            'selesai' => 'Selesai'
        ];
        
        $statusFinal = $statusMap[strtolower($statusRaw)] ?? ucfirst(strtolower($statusRaw));
        
        $allowedStatus = ['Pending', 'Proses', 'Selesai'];
        if (!in_array($statusFinal, $allowedStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid. Pilihan: Pending, Proses, Selesai'
            ], 422);
        }

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

        Log::info('Project updated by manager divisi', [
            'project_id' => $project->id,
            'new_progres' => $progres,
            'new_status' => $statusFinal,
            'updated_by' => auth()->id()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Progres dan status berhasil diupdate!',
            'data' => $project
        ]);
    }

    /**
     * Update General Manager (Full Update - untuk method updategeneral).
     */
    public function updategeneral(Request $request, string $id)
    {
        Log::info('Updategeneral method called', ['id' => $id, 'request_data' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'penanggung_jawab_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            Log::error('Updategeneral validation failed', ['errors' => $validator->errors()->toArray()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $project = Project::findOrFail($id);
            
            // Hanya perbolehkan admin mengubah field non-progres dan non-status_pengerjaan
            $project->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'tanggal_mulai_pengerjaan' => $request->tanggal_mulai_pengerjaan,
                'tanggal_selesai_pengerjaan' => $request->tanggal_selesai_pengerjaan,
                'tanggal_mulai_kerjasama' => $request->tanggal_mulai_kerjasama,
                'tanggal_selesai_kerjasama' => $request->tanggal_selesai_kerjasama,
                'status_kerjasama' => $request->status_kerjasama,
            ]);
            
            Log::info('Project updated via updategeneral', ['project' => $project->toArray()]);
            
            return response()->json([
                'success' => true,
                'message' => 'Project berhasil diupdate!',
                'data' => $project
            ]);
            
        } catch (\Exception $e) {
            Log::error('Updategeneral Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delete Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter/Refresh Table via AJAX.
     */
    public function filterByUser(Request $request)
    {
        $user = auth()->user();
        $projects = Project::query()->with(['layanan', 'penanggungJawab']);

        if ($user->role === 'manager_divisi') {
            $projects->where('penanggung_jawab_id', $user->id);
        }

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
    
    /**
     * Synchronize projects from layanan
     */
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
            Log::error('Sync Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Assign Penanggung Jawab saja (Method khusus untuk hanya mengubah penanggung jawab)
     * Method alternatif jika hanya ingin mengubah penanggung jawab
     */
    public function assignResponsible(Request $request, string $id)
    {
        Log::info('Assign responsible method called', [
            'id' => $id, 
            'request_data' => $request->all()
        ]);
        
        $validator = Validator::make($request->all(), [
            'penanggung_jawab_id' => 'nullable|exists:users,id',
        ], [
            'penanggung_jawab_id.exists' => 'Manager Divisi yang dipilih tidak valid',
        ]);

        if ($validator->fails()) {
            Log::error('Assign responsible validation failed', ['errors' => $validator->errors()->toArray()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $project = Project::findOrFail($id);
            
            $project->update([
                'penanggung_jawab_id' => $request->penanggung_jawab_id ?: null,
            ]);
            
            DB::commit();
            
            Log::info('Responsible assigned successfully', [
                'project_id' => $project->id,
                'new_responsible_id' => $project->penanggung_jawab_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Penanggung jawab berhasil ditetapkan!',
                'data' => $project->fresh(['layanan', 'penanggungJawab'])
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Project not found in assignResponsible: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Project tidak ditemukan!'
            ], 404);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Assign Responsible Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menetapkan penanggung jawab: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Simple update method for just assigning responsible person
     * Method yang lebih sederhana untuk debug
     */
    public function simpleUpdate(Request $request, string $id)
    {
        Log::info('Simple update called', ['id' => $id, 'data' => $request->all()]);
        
        try {
            $project = Project::findOrFail($id);
            
            // Hanya update penanggung jawab
            $project->update([
                'penanggung_jawab_id' => $request->penanggung_jawab_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil update penanggung jawab',
                'data' => $project
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
} 
