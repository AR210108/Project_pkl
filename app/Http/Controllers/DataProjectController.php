<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Invoice; // Changed from Layanan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $project = Project::with(['invoice', 'penanggungJawab'])
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhereHas('penanggungJawab', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->orderBy('id', 'desc')
            ->paginate(3)
            ->withQueryString();

        $managers = User::where('role', 'manager_divisi')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'divisi_id']);

        return view('general_manajer.data_project', compact('project', 'managers', 'search'));
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

    public function managerDivisi(Request $request)
    {
        $user = auth()->user();

        $projects = Project::with(['invoice', 'penanggungJawab'])
            ->where('penanggung_jawab_id', $user->id)
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
            'invoice_id' => 'required|exists:invoices,id',
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
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::create([
            'invoice_id' => $request->invoice_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'tanggal_mulai_pengerjaan' => $request->tanggal_mulai_pengerjaan,
            'tanggal_selesai_pengerjaan' => $request->tanggal_selesai_pengerjaan,
            'tanggal_mulai_kerjasama' => $request->tanggal_mulai_kerjasama,
            'tanggal_selesai_kerjasama' => $request->tanggal_selesai_kerjasama,
            'status_pengerjaan' => $request->status_pengerjaan,
            'status_kerjasama' => $request->status_kerjasama,
            'progres' => $request->progres,
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
        $project = Project::with(['invoice', 'penanggungJawab'])->findOrFail($id);
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
            'tanggal_mulai_pengerjaan' => 'required|date',
            'tanggal_selesai_pengerjaan' => 'nullable|date|after:tanggal_mulai_pengerjaan',
            'tanggal_mulai_kerjasama' => 'nullable|date',
            'tanggal_selesai_kerjasama' => 'nullable|date|after:tanggal_mulai_kerjasama',
            // Hapus pengeditan langsung untuk progres dan status_pengerjaan dari admin update
            'status_kerjasama' => 'required|in:aktif,selesai,ditangguhkan',
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
            
            return response()->json([
                'success' => true,
                'message' => 'Project berhasil diupdate!',
                'data' => $project
            ]);

        } catch (\Exception $e) {
            \Log::error('Update Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateManager(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'progres' => 'required|integer|min:0|max:100',
            'status_pengerjaan' => 'required|in:pending,dalam_pengerjaan,selesai,dibatalkan',
            'status_kerjasama' => 'required|in:aktif,selesai,ditangguhkan',
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
            'progres' => (int) $request->progres,
            'status_pengerjaan' => $request->status_pengerjaan,
            'status_kerjasama' => $request->status_kerjasama,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Project berhasil diupdate!',
            'data' => $project
        ]);
    }

    public function updategeneral(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:255',
            'deskripsi' => 'sometimes|string',
            'harga' => 'sometimes|numeric|min:0',
            'tanggal_mulai_pengerjaan' => 'sometimes|date',
            'tanggal_selesai_pengerjaan' => 'nullable|date|after:tanggal_mulai_pengerjaan',
            'tanggal_mulai_kerjasama' => 'nullable|date',
            'tanggal_selesai_kerjasama' => 'nullable|date|after:tanggal_mulai_kerjasama',
            'status_pengerjaan' => 'sometimes|in:pending,dalam_pengerjaan,selesai,dibatalkan',
            'status_kerjasama' => 'sometimes|in:aktif,selesai,ditangguhkan',
            'progres' => 'sometimes|integer|min:0|max:100',
            'penanggung_jawab_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $project = Project::findOrFail($id);
            
            \Log::info('General Manager updating project ID: ' . $id, [
                'old_data' => $project->toArray(),
                'new_data' => $request->all()
            ]);
            
            $updateData = [];
            
            // Update semua field jika ada
            $fields = [
                'nama', 'deskripsi', 'harga', 'tanggal_mulai_pengerjaan',
                'tanggal_selesai_pengerjaan', 'tanggal_mulai_kerjasama',
                'tanggal_selesai_kerjasama', 'status_pengerjaan',
                'status_kerjasama', 'progres'
            ];
            
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }
            
            // Field utama yang diubah General Manager
            $updateData['penanggung_jawab_id'] = $request->penanggung_jawab_id;
            
            $project->update($updateData);
            
            \Log::info('Project updated by General Manager:', $project->toArray());
            
            return response()->json([
                'success' => true,
                'message' => 'Project berhasil diupdate!',
                'data' => $project
            ]);

        } catch (\Exception $e) {
            \Log::error('General Manager Update Error: ' . $e->getMessage(), [
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
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project berhasil dihapus!'
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

    /**
     * Sync project data from invoice
     */
    public function syncFromInvoice($invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);
            $projects = Project::where('invoice_id', $invoiceId)->get();
            
            $updatedCount = 0;
            
            foreach ($projects as $project) {
                $project->update([
                    'nama' => $invoice->judul ?? 'Project dari Invoice #' . $invoice->id,
                    'deskripsi' => $invoice->deskripsi ?? '',
                    'harga' => $invoice->total ?? 0,
                ]);
                $updatedCount++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "{$updatedCount} project berhasil disinkronisasi dari invoice.",
                'data' => [
                    'invoice' => $invoice,
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

    /**
     * Get invoice details for auto-fill
     */
    public function getInvoiceDetails($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $invoice->judul ?? 'Project dari Invoice #' . $invoice->id,
                    'deskripsi' => $invoice->deskripsi ?? '',
                    'harga' => $invoice->total ?? 0,
                    'tanggal_mulai_kerjasama' => $invoice->tanggal_mulai ? $invoice->tanggal_mulai->format('Y-m-d') : null,
                    'tanggal_selesai_kerjasama' => $invoice->tanggal_selesai ? $invoice->tanggal_selesai->format('Y-m-d') : null,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data invoice: ' . $e->getMessage()
            ], 500);
        }
    }
}