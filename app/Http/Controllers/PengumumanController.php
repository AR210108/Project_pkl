<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        if ($user->role !== 'admin') {
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
            if ($user->role !== 'admin') {
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

    /**
     * =================================================================
     * METHOD-METHOD API BARU UNTUK KARYAWAN
     * =================================================================
     */

    /**
     * API: Mengambil tanggal-tanggal yang memiliki pengumuman (untuk /karyawan/api/announcements-dates)
     */
    public function getAnnouncementDatesApi(): JsonResponse
    {
        try {
            Log::info('=== GET ANNOUNCEMENT DATES API ===');

            // Cek authentication
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $userId = Auth::id();
            $user = Auth::user();

            // Check if table exists
            if (!Schema::hasTable('pengumuman')) {
                Log::warning('Table pengumuman does not exist');
                return response()->json([
                    'success' => true,
                    'dates' => []
                ]);
            }

            Log::info('User Info:', [
                'user_id' => $userId,
                'user_name' => $user->name,
                'user_role' => $user->role
            ]);

            // Query untuk mendapatkan tanggal pengumuman
            // Untuk admin, tampilkan semua pengumuman
            // Untuk non-admin, hanya tampilkan pengumuman yang ditugaskan ke user tersebut
            if ($user->role === 'admin') {
                $datesQuery = Pengumuman::where('status', 'published')
                    ->select('tanggal')
                    ->distinct();
            } else {
                $datesQuery = Pengumuman::where('status', 'published')
                    ->whereHas('users', function ($query) use ($userId) {
                        $query->where('users.id', $userId);
                    })
                    ->select('tanggal')
                    ->distinct();
            }

            // Get dates
            $dates = $datesQuery->orderBy('tanggal', 'desc')
                ->get()
                ->pluck('tanggal')
                ->map(function ($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })
                ->toArray();

            Log::info('Found announcement dates:', [
                'count' => count($dates),
                'dates' => $dates
            ]);

            return response()->json([
                'success' => true,
                'dates' => $dates
            ]);

        } catch (\Exception $e) {
            Log::error('Announcement Dates API Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load announcement dates',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * API: Mengambil daftar pengumuman (untuk /karyawan/api/announcements)
     */
    public function getAnnouncementsApi(Request $request): JsonResponse
    {
        try {
            Log::info('=== GET ANNOUNCEMENTS API ===');

            // Cek authentication
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $userId = Auth::id();
            $user = Auth::user();

            // Check if table exists
            if (!Schema::hasTable('pengumuman')) {
                Log::warning('Table pengumuman does not exist');
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            Log::info('User Info:', [
                'user_id' => $userId,
                'user_name' => $user->name,
                'user_role' => $user->role
            ]);

            // Query untuk mendapatkan pengumuman
            // Untuk admin, tampilkan semua pengumuman
            // Untuk non-admin, hanya tampilkan pengumuman yang ditugaskan ke user tersebut
            if ($user->role === 'admin') {
                $announcementsQuery = Pengumuman::where('status', 'published')
                    ->with(['creator:id,name']);
            } else {
                $announcementsQuery = Pengumuman::where('status', 'published')
                    ->whereHas('users', function ($query) use ($userId) {
                        $query->where('users.id', $userId);
                    })
                    ->with(['creator:id,name']);
            }

            // Get latest announcements
            $announcements = $announcementsQuery->orderBy('created_at', 'desc')
                ->limit(20)
                ->get([
                    'id',
                    'judul',
                    'isi_pesan',
                    'tanggal',
                    'lampiran',
                    'is_important',
                    'is_pinned',
                    'status',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]);

            Log::info('Found announcements:', ['count' => $announcements->count()]);

            // Format data untuk response
            $formattedAnnouncements = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'judul' => $announcement->judul,
                    'isi' => $announcement->isi_pesan,
                    'ringkasan' => $this->getExcerpt($announcement->isi_pesan, 100),
                    'tanggal' => $announcement->tanggal,
                    'formatted_tanggal' => $announcement->tanggal ?
                        Carbon::parse($announcement->tanggal)->translatedFormat('d F Y') :
                        Carbon::parse($announcement->created_at)->translatedFormat('d F Y'),
                    'lampiran' => $announcement->lampiran,
                    'lampiran_url' => $announcement->lampiran ?
                        asset('storage/' . $announcement->lampiran) : null,
                    'is_important' => (bool) $announcement->is_important,
                    'is_pinned' => (bool) $announcement->is_pinned,
                    'status' => $announcement->status,
                    'creator_id' => $announcement->user_id,
                    'creator_name' => $announcement->creator ? $announcement->creator->name : 'System',
                    'created_at' => $announcement->created_at->format('Y-m-d H:i:s'),
                    'formatted_created_at' => $announcement->created_at->translatedFormat('d F Y H:i'),
                    'updated_at' => $announcement->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            Log::info('Formatted announcements:', [
                'count' => $formattedAnnouncements->count(),
                'first_announcement' => $formattedAnnouncements->first()
            ]);

            return response()->json([
                'success' => true,
                'data' => $formattedAnnouncements,
                'count' => $formattedAnnouncements->count(),
                'message' => $formattedAnnouncements->count() > 0 ?
                    'Ditemukan ' . $formattedAnnouncements->count() . ' pengumuman' :
                    'Tidak ada pengumuman'
            ]);

        } catch (\Exception $e) {
            Log::error('Announcements API Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load announcements',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * API: Mengambil pengumuman berdasarkan tanggal tertentu
     */
    public function getAnnouncementsByDateApi(Request $request): JsonResponse
    {
        try {
            $date = $request->query('date');

            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date parameter is required'
                ], 400);
            }

            Log::info('=== GET ANNOUNCEMENTS BY DATE API ===', ['date' => $date]);

            // Cek authentication
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $userId = Auth::id();
            $user = Auth::user();

            // Check if table exists
            if (!Schema::hasTable('pengumuman')) {
                Log::warning('Table pengumuman does not exist');
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Query untuk mendapatkan pengumuman berdasarkan tanggal
            if ($user->role === 'admin') {
                $announcementsQuery = Pengumuman::where('status', 'published')
                    ->whereDate('tanggal', $date)
                    ->with(['creator:id,name']);
            } else {
                $announcementsQuery = Pengumuman::where('status', 'published')
                    ->whereDate('tanggal', $date)
                    ->whereHas('users', function ($query) use ($userId) {
                        $query->where('users.id', $userId);
                    })
                    ->with(['creator:id,name']);
            }

            $announcements = $announcementsQuery->orderBy('created_at', 'desc')
                ->get();

            // Format data untuk response
            $formattedAnnouncements = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'judul' => $announcement->judul,
                    'isi' => $announcement->isi_pesan,
                    'ringkasan' => $this->getExcerpt($announcement->isi_pesan, 100),
                    'tanggal' => $announcement->tanggal,
                    'formatted_tanggal' => Carbon::parse($announcement->tanggal)->translatedFormat('d F Y'),
                    'lampiran' => $announcement->lampiran,
                    'lampiran_url' => $announcement->lampiran ?
                        asset('storage/' . $announcement->lampiran) : null,
                    'is_important' => (bool) $announcement->is_important,
                    'is_pinned' => (bool) $announcement->is_pinned,
                    'creator_name' => $announcement->creator ? $announcement->creator->name : 'System',
                    'created_at' => $announcement->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedAnnouncements,
                'date' => $date,
                'count' => $formattedAnnouncements->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Announcements by Date API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load announcements by date'
            ], 500);
        }
    }

    /**
     * API: Debug endpoint untuk testing API pengumuman
     */
    public function debugAnnouncementApis(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user = Auth::user();
            $userId = $user->id;

            // Test getAnnouncementDatesApi
            $datesResponse = $this->getAnnouncementDatesApi();
            $datesData = json_decode($datesResponse->getContent(), true);

            // Test getAnnouncementsApi
            $announcementsResponse = $this->getAnnouncementsApi(new Request());
            $announcementsData = json_decode($announcementsResponse->getContent(), true);

            // Check database
            $tables = [
                'pengumuman' => Schema::hasTable('pengumuman'),
                'pengumuman_user' => Schema::hasTable('pengumuman_user'),
            ];

            // Get counts
            $counts = [
                'total_pengumuman' => Pengumuman::count(),
                'published_pengumuman' => Pengumuman::where('status', 'published')->count(),
                'user_assigned_pengumuman' => $user->role === 'admin' ?
                    Pengumuman::where('status', 'published')->count() :
                    Pengumuman::where('status', 'published')
                        ->whereHas('users', function ($query) use ($userId) {
                            $query->where('users.id', $userId);
                        })->count(),
            ];

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'is_admin' => $user->role === 'admin',
                ],
                'api_tests' => [
                    'getAnnouncementDatesApi' => $datesData['success'] ? 'SUCCESS' : 'FAILED',
                    'getAnnouncementsApi' => $announcementsData['success'] ? 'SUCCESS' : 'FAILED',
                ],
                'database' => [
                    'tables_exist' => $tables,
                    'counts' => $counts,
                ],
                'routes' => [
                    'announcements_dates' => '/karyawan/api/announcements-dates',
                    'announcements' => '/karyawan/api/announcements',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Helper method untuk mendapatkan excerpt/ringkasan
     */
    private function getExcerpt(string $text, int $length = 100): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        $excerpt = substr($text, 0, $length);
        $lastSpace = strrpos($excerpt, ' ');

        if ($lastSpace !== false) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }

        return $excerpt . '...';
    }

    /**
     * API: Get pengumuman for specific user (karyawan)
     * Ini method yang akan dipanggil oleh CatatanRapatController
     */
    public function getAnnouncementsForUserApi(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $userId = Auth::id();
            $user = Auth::user();

            Log::info('Getting announcements for user:', [
                'user_id' => $userId,
                'user_name' => $user->name,
                'user_role' => $user->role
            ]);

            // Query untuk mendapatkan pengumuman untuk user ini
            if ($user->role === 'admin') {
                $announcements = Pengumuman::where('status', 'published')
                    ->with(['creator:id,name'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $announcements = Pengumuman::where('status', 'published')
                    ->whereHas('users', function ($query) use ($userId) {
                        $query->where('users.id', $userId);
                    })
                    ->with(['creator:id,name'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $formattedAnnouncements = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'judul' => $announcement->judul,
                    'isi' => $announcement->isi_pesan,
                    'tanggal' => $announcement->tanggal,
                    'formatted_tanggal' => Carbon::parse($announcement->tanggal)->translatedFormat('d F Y'),
                    'lampiran' => $announcement->lampiran,
                    'lampiran_url' => $announcement->lampiran ?
                        asset('storage/' . $announcement->lampiran) : null,
                    'creator_name' => $announcement->creator ? $announcement->creator->name : 'System',
                    'created_at' => $announcement->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedAnnouncements
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getAnnouncementsForUserApi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load announcements'
            ], 500);
        }
    }

    /**
     * API: Get announcement dates for user (karyawan)
     * Ini method yang akan dipanggil oleh CatatanRapatController
     */
    public function getAnnouncementDatesForUserApi(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $userId = Auth::id();
            $user = Auth::user();

            Log::info('Getting announcement dates for user:', [
                'user_id' => $userId,
                'user_name' => $user->name,
                'user_role' => $user->role
            ]);

            // Query untuk mendapatkan tanggal pengumuman untuk user ini
            if ($user->role === 'admin') {
                $dates = Pengumuman::where('status', 'published')
                    ->select('tanggal')
                    ->distinct()
                    ->orderBy('tanggal', 'desc')
                    ->get()
                    ->pluck('tanggal');
            } else {
                $dates = Pengumuman::where('status', 'published')
                    ->whereHas('users', function ($query) use ($userId) {
                        $query->where('users.id', $userId);
                    })
                    ->select('tanggal')
                    ->distinct()
                    ->orderBy('tanggal', 'desc')
                    ->get()
                    ->pluck('tanggal');
            }

            $formattedDates = $dates->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })->toArray();

            return response()->json([
                'success' => true,
                'dates' => $formattedDates
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getAnnouncementDatesForUserApi: ' . $e->getMessage());
            return response()->json([
                'success' => true,
                'dates' => []
            ]);
        }
    }

    /**
     * API: Mendapatkan tanggal-tanggal pengumuman yang relevan untuk General Manager.
     * Hanya menampilkan pengumuman yang dibuat oleh atau ditugaskan ke GM.
     */
    public function getAnnouncementDatesForGM(): JsonResponse
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'general_manager') {
                return response()->json(['message' => 'Access denied'], 403);
            }

            // Ambil pengumuman yang dibuat oleh GM atau ditugaskan ke GM
            $dates = Pengumuman::where(function ($query) use ($user) {
                $query->where('user_id', $user->id) // Yang dibuat olehnya
                    ->orWhereHas('users', function ($q) use ($user) { // Yang ditugaskan kepadanya
                        $q->where('users.id', $user->id);
                    });
            })
                ->select('created_at') // Gunakan created_at karena kolom 'tanggal' tidak ada
                ->distinct()
                ->orderBy('created_at', 'asc')
                ->get()
                ->pluck('created_at');

            // Format tanggal ke Y-m-d
            return response()->json($dates->map(function ($date) {
                return $date->format('Y-m-d');
            })->toArray());

        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * API: Mendapatkan daftar pengumuman yang relevan untuk General Manager.
     * Hanya menampilkan pengumuman yang dibuat oleh atau ditugaskan ke GM.
     */
    public function getAnnouncementsForGM(): JsonResponse
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'general_manager') {
                return response()->json(['message' => 'Access denied'], 403);
            }

            // Ambil pengumuman yang dibuat oleh GM atau ditugaskan ke GM
            $announcements = Pengumuman::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('users', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })
                ->with(['creator:id,name'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            // Format data untuk response
            $formattedData = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'judul' => $announcement->judul,
                    'isi_pesan' => $announcement->isi_pesan,
                    'ringkasan' => substr(strip_tags($announcement->isi_pesan), 0, 100) . '...',
                    'tanggal_indo' => $announcement->created_at->translatedFormat('d F Y'), // Gunakan created_at
                    'lampiran_url' => $announcement->lampiran ? asset('storage/' . $announcement->lampiran) : null,
                    'creator' => $announcement->creator ? $announcement->creator->name : 'System',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedData
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * API: Debug endpoint untuk testing API pengumuman untuk GM
     */
    public function debugGMAnnouncementApis(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user = Auth::user();

            // Check if user has general_manager role
            if ($user->role !== 'general_manager') {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Only General Manager can access this resource.'
                ], 403);
            }

            // Test getAnnouncementDatesForGM
            $datesResponse = $this->getAnnouncementDatesForGM();
            $datesData = json_decode($datesResponse->getContent(), true);

            // Test getAnnouncementsForGM
            $announcementsResponse = $this->getAnnouncementsForGM();
            $announcementsData = json_decode($announcementsResponse->getContent(), true);

            // Check database
            $tables = [
                'pengumuman' => Schema::hasTable('pengumuman'),
                'pengumuman_user' => Schema::hasTable('pengumuman_user'),
            ];

            // Get counts
            $counts = [
                'total_pengumuman' => Pengumuman::count(),
                'published_pengumuman' => Pengumuman::where('status', 'published')->count(),
            ];

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                ],
                'api_tests' => [
                    'getAnnouncementDatesForGM' => $datesResponse->getStatusCode() === 200 ? 'SUCCESS' : 'FAILED',
                    'getAnnouncementsForGM' => $announcementsResponse->getStatusCode() === 200 ? 'SUCCESS' : 'FAILED',
                ],
                'database' => [
                    'tables_exist' => $tables,
                    'counts' => $counts,
                ],
                'routes' => [
                    'gm_announcements_dates' => '/api/general_manager/announcements-dates',
                    'gm_announcements' => '/api/general_manager/announcements',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * API: Mendapatkan tanggal-tanggal pengumuman untuk Owner.
     * Owner melihat semua pengumuman.
     */
    public function getAnnouncementDatesForOwner(): JsonResponse
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'owner') {
                return response()->json(['message' => 'Access denied'], 403);
            }

            $dates = Pengumuman::select('created_at')->distinct()->orderBy('created_at', 'asc')->pluck('created_at');
            return response()->json($dates->map(fn($date) => $date->format('Y-m-d'))->toArray());

        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * API: Mendapatkan daftar pengumuman untuk Owner.
     */
    public function getAnnouncementsForOwner(): JsonResponse
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'owner') {
                return response()->json(['message' => 'Access denied'], 403);
            }

            $announcements = Pengumuman::with(['creator:id,name'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            $formattedData = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'judul' => $announcement->judul,
                    'isi_pesan' => $announcement->isi_pesan,
                    'ringkasan' => substr(strip_tags($announcement->isi_pesan), 0, 100) . '...',
                    'tanggal_indo' => $announcement->created_at->translatedFormat('d F Y'),
                    'lampiran_url' => $announcement->lampiran ? asset('storage/' . $announcement->lampiran) : null,
                    'creator' => $announcement->creator ? $announcement->creator->name : 'System',
                ];
            });

            return response()->json(['success' => true, 'data' => $formattedData]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * API: Mendapatkan tanggal-tanggal pengumuman untuk Manager Divisi.
     * Hanya menampilkan pengumuman yang ditujukan langsung ke manager.
     */
    public function getAnnouncementDatesForManager(): JsonResponse
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'manager_divisi') {
                return response()->json(['message' => 'Access denied'], 403);
            }

            // Query yang lebih aman: Hanya pengumuman yang ditujukan ke user ini
            $dates = Pengumuman::whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
                ->select('created_at') // Gunakan created_at
                ->distinct()
                ->orderBy('created_at', 'asc')
                ->get()
                ->pluck('created_at');

            return response()->json($dates->map(fn($date) => $date->format('Y-m-d'))->toArray());

        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * API: Mendapatkan daftar pengumuman untuk Manager Divisi.
     * Hanya menampilkan pengumuman yang ditujukan langsung ke manager.
     */
    public function getAnnouncementsForManager(): JsonResponse
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'manager_divisi') {
                return response()->json(['message' => 'Access denied'], 403);
            }

            // Query yang lebih aman: Hanya pengumuman yang ditujukan ke user ini
            $announcements = Pengumuman::whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
                ->with(['creator:id,name'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            $formattedData = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'judul' => $announcement->judul,
                    'isi_pesan' => $announcement->isi_pesan,
                    'ringkasan' => substr(strip_tags($announcement->isi_pesan), 0, 100) . '...',
                    'tanggal_indo' => $announcement->created_at->translatedFormat('d F Y'),
                    'lampiran_url' => $announcement->lampiran ? asset('storage/' . $announcement->lampiran) : null,
                    'creator' => $announcement->creator ? $announcement->creator->name : 'System',
                ];
            });

            return response()->json(['success' => true, 'data' => $formattedData]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }
}