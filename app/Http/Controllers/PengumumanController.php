<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Query untuk tampilan awal
        $query = Pengumuman::with(['creator:id,name', 'users:id,name'])
            ->latest();
        
        // Filter jika bukan admin
        if (!$user->isAdmin()) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        
        $pengumuman = $query->get();
        $users = User::where('id', '!=', $user->id)->get();
        
        return view('admin.pengumuman', [
            'pengumuman' => $pengumuman,
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::all();
        return view('pengumuman.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'isi_pesan' => 'required|string',
                'users' => 'required|array|min:1',
                'users.*' => 'exists:users,id',
                'lampiran' => 'nullable|file|max:10240',
                'is_important' => 'nullable|boolean',
                'is_pinned' => 'nullable|boolean',
            ]);

            // Upload lampiran jika ada
            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $lampiranPath = $file->storeAs('pengumuman', $filename, 'public');
            }

            // Create pengumuman
            $pengumuman = Pengumuman::create([
                'user_id' => auth()->id(),
                'judul' => $validated['judul'],
                'isi_pesan' => $validated['isi_pesan'],
                'lampiran' => $lampiranPath,
                'is_important' => $validated['is_important'] ?? false,
                'is_pinned' => $validated['is_pinned'] ?? false,
            ]);

            // Attach users
            $pengumuman->users()->sync($validated['users']);

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil dibuat',
                'data' => $pengumuman->load(['creator:id,name', 'users:id,name'])
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $pengumuman = Pengumuman::with(['creator:id,name,email', 'users:id,name,email'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pengumuman
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pengumuman tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pengumuman = Pengumuman::with('users')->findOrFail($id);
        $users = User::all();
        return view('pengumuman.edit', compact('pengumuman', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $pengumuman = Pengumuman::findOrFail($id);

            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'isi_pesan' => 'required|string',
                'users' => 'required|array|min:1',
                'users.*' => 'exists:users,id',
                'lampiran' => 'nullable|file|max:10240',
                'is_important' => 'nullable|boolean',
                'is_pinned' => 'nullable|boolean',
            ]);

            // Handle lampiran
            if ($request->hasFile('lampiran')) {
                // Delete old file if exists
                if ($pengumuman->lampiran && Storage::disk('public')->exists($pengumuman->lampiran)) {
                    Storage::disk('public')->delete($pengumuman->lampiran);
                }
                
                $file = $request->file('lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $validated['lampiran'] = $file->storeAs('pengumuman', $filename, 'public');
            }

            // Update pengumuman
            $pengumuman->update([
                'judul' => $validated['judul'],
                'isi_pesan' => $validated['isi_pesan'],
                'lampiran' => $validated['lampiran'] ?? $pengumuman->lampiran,
                'is_important' => $validated['is_important'] ?? false,
                'is_pinned' => $validated['is_pinned'] ?? false,
            ]);

            // Update users
            $pengumuman->users()->sync($validated['users']);

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil diperbarui',
                'data' => $pengumuman->load(['creator:id,name', 'users:id,name'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $pengumuman = Pengumuman::findOrFail($id);
            
            // Delete lampiran if exists
            if ($pengumuman->lampiran && Storage::disk('public')->exists($pengumuman->lampiran)) {
                Storage::disk('public')->delete($pengumuman->lampiran);
            }
            
            // Detach users first
            $pengumuman->users()->detach();
            
            // Delete pengumuman
            $pengumuman->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan data pengumuman (JSON)
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $search = $request->get('search', '');
            
            $query = Pengumuman::with(['creator:id,name,email', 'users:id,name,email'])
                ->latest();
            
            // Filter berdasarkan role
            if (!$user->isAdmin()) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }
            
            // Search filter
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('isi_pesan', 'like', "%{$search}%");
                });
            }
            
            $pengumuman = $query->paginate($request->get('per_page', 10));
            
            return response()->json([
                'success' => true,
                'data' => $pengumuman->items(),
                'pagination' => [
                    'current_page' => $pengumuman->currentPage(),
                    'last_page' => $pengumuman->lastPage(),
                    'per_page' => $pengumuman->perPage(),
                    'total' => $pengumuman->total(),
                ],
                'message' => $pengumuman->total() > 0 
                    ? 'Ditemukan ' . $pengumuman->total() . ' pengumuman' 
                    : 'Belum ada pengumuman'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data pengumuman'
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan daftar user
     */
    public function getUsers(): JsonResponse
    {
        try {
            $users = User::select('id', 'name', 'email', 'role')
                ->where('id', '!=', auth()->id())
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data user'
            ], 500);
        }
    }
}