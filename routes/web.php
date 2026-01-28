<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminKaryawanController;
use App\Http\Controllers\CatatanRapatController;
use App\Http\Controllers\DataProjectController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\SuratKerjasamaController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GeneralManagerTaskController;
use App\Http\Controllers\KaryawanProfileController;
use App\Http\Controllers\ManagerDivisiTaskController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

if (!function_exists('redirectToRolePage')) {
    function redirectToRolePage($user)
    {
        return match ($user->role) {
            'admin', 'finance' => redirect()->route("{$user->role}.beranda"),
            'general_manager' => redirect()->route('general_manajer.home'),
            'karyawan', 'manager_divisi', 'owner' => redirect()->route("{$user->role}.home"),
            default => redirect('/login')
        };
    }
}

/*
|--------------------------------------------------------------------------
| Guest Routes (Pengunjung Belum Login)
|--------------------------------------------------------------------------
*/

Route::get('/', [LayananController::class, 'landingPage'])->name('home');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/logout-get', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout.get');
    
    Route::get('/redirect-login', function () {
        return redirectToRolePage(Auth::user());
    })->name('redirect.login');
});

/*
|--------------------------------------------------------------------------
| Pegawai Management Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/pegawai', [KaryawanController::class, 'indexPegawai'])->name('pegawai.index');
    Route::post('/pegawai', [KaryawanController::class, 'storePegawai'])->name('pegawai.store');
    Route::get('/pegawai/{id}/edit', [KaryawanController::class, 'editPegawai'])->name('pegawai.edit');
    Route::put('/pegawai/{id}', [KaryawanController::class, 'updatePegawai'])->name('pegawai.update');
    Route::delete('/pegawai/{id}', [KaryawanController::class, 'destroyPegawai'])->name('pegawai.destroy');
});

/*
|--------------------------------------------------------------------------
| Role-Based Routes - ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/beranda', [AdminController::class, 'beranda'])->name('beranda');
        Route::get('/home', function () { return redirect()->route('admin.beranda'); });

        // API Data
        Route::get('/catatan_rapat/data', [CatatanRapatController::class, 'getData'])->name('catatan_rapat.data');
        Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');

        // User Management
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
        Route::get('/data_user', function () { return redirect()->route('admin.user'); });

        // Karyawan Management
        Route::get('/data_karyawan', [AdminKaryawanController::class, 'index'])->name('data_karyawan');
        Route::controller(AdminKaryawanController::class)->group(function () {
            Route::get('/karyawan', 'index')->name('karyawan.index');
            Route::post('/karyawan/store', 'store')->name('karyawan.store');
            Route::post('/karyawan/update/{id}', 'update')->name('karyawan.update');
            Route::delete('/karyawan/delete/{id}', 'destroy')->name('karyawan.delete');
        });

        // Absensi & Keuangan
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/keuangan', function () { return view('admin.keuangan'); })->name('keuangan.index');
        Route::get('/data_order', function () { return view('admin.data_order'); })->name('data_order');

        // Task Management
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('index');
            Route::get('/create', [TaskController::class, 'create'])->name('create');
            Route::post('/', [TaskController::class, 'store'])->name('store');
            Route::get('/{id}', [TaskController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [TaskController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TaskController::class, 'update'])->name('update');
            Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy');
            
            // Upload & File Admin
            Route::post('/{id}/upload-file', [TaskController::class, 'uploadFileAdmin'])->name('upload.file');
            Route::get('/{id}/files', [TaskController::class, 'getTaskFiles'])->name('files');
            Route::get('/files/{file}/download', [TaskController::class, 'downloadFile'])->name('files.download');
            Route::delete('/files/{file}', [TaskController::class, 'deleteFile'])->name('files.delete');
            
            // Comments
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
        });

        // Layanan
        Route::prefix('layanan')->name('layanan.')->group(function () {
            Route::get('/', [LayananController::class, 'index'])->name('index');
            Route::post('/', [LayananController::class, 'store'])->name('store');
            Route::put('/{id}', [LayananController::class, 'update'])->name('update');
            Route::delete('/{id}', [LayananController::class, 'destroy'])->name('delete');
        });

        // Surat Kerjasama
        Route::prefix('surat-kerjasama')->name('surat_kerjasama.')->group(function () {
            Route::get('/', [SuratKerjasamaController::class, 'index'])->name('index');
            Route::get('/create', [SuratKerjasamaController::class, 'create'])->name('create');
            Route::post('/', [SuratKerjasamaController::class, 'store'])->name('store');
            Route::get('/{id}', [SuratKerjasamaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SuratKerjasamaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SuratKerjasamaController::class, 'update'])->name('update');
            Route::delete('/{id}', [SuratKerjasamaController::class, 'destroy'])->name('destroy');
        });
        Route::get('/surat_kerjasama', function () { return redirect()->route('admin.surat_kerjasama.index'); });
        Route::get('/template_surat', function () { return view('admin.template_surat'); })->name('template_surat');

        // Invoice & Kwitansi
        Route::prefix('invoice')->name('invoice.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/create', [InvoiceController::class, 'create'])->name('create');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            Route::get('/', [KwitansiController::class, 'index'])->name('index');
            Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
        });

        // Cuti Management untuk Admin (melihat semua cuti karyawan)
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'indexAdmin'])->name('index');
            Route::get('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
            Route::get('/export', [CutiController::class, 'export'])->name('export');
        });

        Route::get('/settings', function () { return view('admin.settings'); })->name('settings');
        Route::get('/catatan_rapat', function () { return redirect()->route('catatan_rapat.index'); });
        Route::get('/pengumuman', function () { return redirect()->route('pengumuman.index'); });

        Route::get('/api/dashboard-data', function () {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_users' => \App\Models\User::count(),
                    'total_karyawan' => \App\Models\User::where('role', 'karyawan')->count(),
                    'total_tasks' => \App\Models\Task::count(),
                    'completed_tasks' => \App\Models\Task::where('status', 'selesai')->count(),
                ]
            ]);
        });
    });

/*
|--------------------------------------------------------------------------
| Role-Based Routes - KARYAWAN (COMPLETE FIXED VERSION)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        // ==================== BASIC ROUTES ====================
        Route::get('/home', [KaryawanController::class, 'home'])->name('home');
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
        Route::get('/absensi/list', [KaryawanController::class, 'absensiListPage'])->name('absensi.list');
        Route::get('/detail/{id}', [KaryawanController::class, 'detailPage'])->name('detail');
        Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
        Route::get('/tugas', [TaskController::class, 'karyawanTasks'])->name('tugas');
        
        // Profile routes
        Route::get('/profile', [KaryawanProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [KaryawanProfileController::class, 'update'])->name('profile.update');
        
        // ==================== CUTI ROUTES (COMPLETE FIX - SEMUA ROUTE DITAMBAH) ====================
        Route::prefix('cuti')->name('cuti.')->group(function () {
            // Halaman utama cuti
            Route::get('/', [CutiController::class, 'index'])->name('index');
            
            // API Routes untuk AJAX/JavaScript - SEMUA ROUTE ADA DI SINI
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/data-table', [CutiController::class, 'getDataTable'])->name('data-table');
            Route::get('/stats', [CutiController::class, 'getStats'])->name('stats');
            Route::get('/check-available-days', [CutiController::class, 'checkAvailableDays'])->name('check-available-days');
            Route::get('/available-days', [CutiController::class, 'getAvailableDays'])->name('available-days');
            Route::post('/calculate-duration', [CutiController::class, 'calculateDuration'])->name('calculate-duration');
            Route::get('/dashboard-stats', [CutiController::class, 'dashboardStats'])->name('dashboard-stats');
            
            // CRUD Operations
            Route::get('/create', [CutiController::class, 'create'])->name('create');
            Route::post('/', [CutiController::class, 'store'])->name('store');
            Route::get('/{cuti}', [CutiController::class, 'show'])->name('show');
            Route::get('/{cuti}/edit', [CutiController::class, 'edit'])->name('edit');
            Route::put('/{cuti}', [CutiController::class, 'update'])->name('update');
            Route::delete('/{cuti}', [CutiController::class, 'destroy'])->name('destroy');
            
            // Cancel cuti
            Route::post('/{cuti}/cancel', [CutiController::class, 'cancel'])->name('cancel');
            
            // Export
            Route::get('/export', [CutiController::class, 'export'])->name('export');
        });
        
        // Route legacy untuk kompatibilitas
        Route::get('/pengajuan_cuti', function () {
            return redirect()->route('karyawan.cuti.index');
        });
    });

/*
|--------------------------------------------------------------------------
| Role-Based Routes - GENERAL MANAGER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:general_manager'])
    ->prefix('general_manajer')
    ->name('general_manajer.')
    ->group(function () {
        Route::get('/home', function () { return view('general_manajer.home'); })->name('home');
        Route::get('/data_karyawan', [AdminKaryawanController::class, 'generalKaryawan'])->name('data_karyawan');
        
        Route::get('/layanan', function () { return view('general_manajer.data_layanan'); })->name('layanan');
        Route::get('/kelola-order', function () { return view('general_manajer.kelola_order'); })->name('kelola_order');
        Route::get('/kelola-tugas', [GeneralManagerTaskController::class, 'index'])->name('kelola_tugas');

        // Cuti Management untuk General Manager
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'indexGeneralManager'])->name('index');
            Route::get('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
        });

        // ROUTE GROUP UNTUK TASKS
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::post('/', [GeneralManagerTaskController::class, 'store'])->name('store');
            Route::get('/{id}', [GeneralManagerTaskController::class, 'show'])->name('show');
            Route::put('/{id}', [GeneralManagerTaskController::class, 'update'])->name('update');
            Route::put('/{id}/status', [GeneralManagerTaskController::class, 'updateStatus'])->name('update.status');
            Route::post('/{id}/assign', [GeneralManagerTaskController::class, 'assignToKaryawan'])->name('assign');
            Route::delete('/{id}', [GeneralManagerTaskController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/delete', [GeneralManagerTaskController::class, 'destroy'])->name('delete');
        });

        // Absensi Management
        Route::get('/kelola-absen', [AbsensiController::class, 'kelolaAbsen'])->name('kelola_absen');
        Route::get('/kelola-absensi', [AbsensiController::class, 'kelolaAbsensi'])->name('kelola_absensi');
    });

/*
|--------------------------------------------------------------------------
| Role-Based Routes - OWNER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/home', function () { return view('pemilik.home'); })->name('home');
        Route::get('/rekap-absen', [AbsensiController::class, 'rekapAbsensi'])->name('rekap_absen');
        Route::get('/laporan', function () { return view('pemilik.laporan'); })->name('laporan');
        
        // Cuti Management untuk Owner
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'indexOwner'])->name('index');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
        });
    });

/*
|--------------------------------------------------------------------------
| Role-Based Routes - FINANCE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:finance'])
    ->prefix('finance')
    ->name('finance.')
    ->group(function () {
        Route::get('/beranda', function () { return view('finance.beranda'); })->name('beranda');
        Route::get('/data-layanan', function () { return view('finance.data_layanan'); })->name('data_layanan');
        Route::get('/pembayaran', function () { return view('finance.data_pembayaran'); })->name('pembayaran');
        Route::get('/laporan-keuangan', function () { return view('finance.laporan_keuangan'); })->name('laporan_keuangan');
    });

/*
|--------------------------------------------------------------------------
| Role-Based Routes - MANAGER DIVISI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:manager_divisi'])
    ->prefix('manager_divisi')
    ->name('manager_divisi.')
    ->group(function () {
        Route::get('/home', function () { return view('manager_divisi.home'); })->name('home');
        Route::get('/kelola-tugas', [ManagerDivisiTaskController::class, 'index'])->name('kelola_tugas');

        // Cuti Management untuk Manager Divisi
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'indexManagerDivisi'])->name('index');
            Route::get('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
        });

        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::post('/', [ManagerDivisiTaskController::class, 'store'])->name('store');
            Route::get('/{id}', [ManagerDivisiTaskController::class, 'show'])->name('show');
            Route::put('/{id}', [ManagerDivisiTaskController::class, 'update'])->name('update');
            Route::put('/{id}/status', [ManagerDivisiTaskController::class, 'updateStatus'])->name('update.status');
            Route::post('/{id}/assign', [ManagerDivisiTaskController::class, 'assignToKaryawan'])->name('assign');
            Route::delete('/{id}', [ManagerDivisiTaskController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/delete', [ManagerDivisiTaskController::class, 'destroy'])->name('delete');
        });

        Route::get('/pengelola-tugas', function () { return view('manager_divisi.pengelola_tugas'); })->name('pengelola_tugas');
        
        Route::get('/tim-saya', function () {
            $user = Auth::user();
            $tim = \App\Models\User::where('divisi', $user->divisi)->where('role', 'karyawan')->get();
            return view('manager_divisi.tim_saya', compact('tim'));
        })->name('tim_saya');

        Route::get('/absensi-tim', function () { return view('manager_divisi.absensi_tim'); })->name('absensi_tim');
        Route::get('/laporan-kinerja', function () { return view('manager_divisi.laporan_kinerja'); })->name('laporan_kinerja');
    });

/*
|--------------------------------------------------------------------------
| Global Routes (Auth Required) 
|--------------------------------------------------------------------------
*/

// Catatan Rapat
Route::middleware(['auth'])->group(function () {
    // Catatan Rapat
    Route::get('/catatan-rapat', [CatatanRapatController::class, 'index'])->name('catatan_rapat.index');
    Route::post('/catatan-rapat', [CatatanRapatController::class, 'store'])->name('catatan_rapat.store');
    Route::put('/catatan-rapat/{id}', [CatatanRapatController::class, 'update'])->name('catatan_rapat.update');
    Route::delete('/catatan-rapat/{id}', [CatatanRapatController::class, 'destroy'])->name('catatan_rapat.destroy');
    Route::get('/catatan-rapat/{id}', [CatatanRapatController::class, 'show'])->name('catatan_rapat.show');
    Route::get('/catatan-rapat/data', [CatatanRapatController::class, 'getData'])->name('catatan_rapat.data');
    Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
});

Route::middleware(['auth'])->resource('pengumuman', PengumumanController::class);

/*
|--------------------------------------------------------------------------
| TEST ROUTES UNTUK DEBUG CUTI (TEMP ROUTES)
|--------------------------------------------------------------------------
*/

// Route untuk testing jika controller belum siap
Route::middleware(['auth'])->group(function() {
    // Fallback routes untuk testing - accessible tanpa role khusus
    Route::get('/karyawan/test/cuti-data', function(Request $request) {
        return response()->json([
            'success' => true,
            'data' => [],
            'pagination' => [
                'total' => 0,
                'per_page' => 10,
                'current_page' => 1,
                'last_page' => 1
            ]
        ]);
    })->name('test.cuti.data');
    
    Route::get('/karyawan/test/cuti-stats', function() {
        return response()->json([
            'success' => true,
            'data' => [
                'total_cuti_tahunan' => 12,
                'cuti_terpakai' => 0,
                'sisa_cuti' => 12,
                'total_menunggu' => 0,
                'total_disetujui' => 0,
                'total_ditolak' => 0,
            ]
        ]);
    })->name('test.cuti.stats');
    
    Route::get('/debug/cuti-routes', function() {
        $routes = [];
        
        try {
            $routes['karyawan.cuti.index'] = route('karyawan.cuti.index');
            $routes['karyawan.cuti.data'] = route('karyawan.cuti.data');
            $routes['karyawan.cuti.stats'] = route('karyawan.cuti.stats');
            $routes['karyawan.cuti.store'] = route('karyawan.cuti.store');
            $routes['karyawan.cuti.check-available-days'] = route('karyawan.cuti.check-available-days');
            $routes['karyawan.cuti.available-days'] = route('karyawan.cuti.available-days');
            $routes['karyawan.cuti.calculate-duration'] = route('karyawan.cuti.calculate-duration');
            $routes['karyawan.cuti.dashboard-stats'] = route('karyawan.cuti.dashboard-stats');
        } catch (\Exception $e) {
            $routes['error'] = $e->getMessage();
        }
        
        return response()->json([
            'status' => 'debug',
            'routes' => $routes,
            'urls' => [
                'cuti_index' => url('/karyawan/cuti'),
                'cuti_data' => url('/karyawan/cuti/data'),
                'cuti_stats' => url('/karyawan/cuti/stats'),
                'check_available_days' => url('/karyawan/cuti/check-available-days'),
                'available_days' => url('/karyawan/cuti/available-days'),
                'calculate_duration' => url('/karyawan/cuti/calculate-duration'),
                'dashboard_stats' => url('/karyawan/cuti/dashboard-stats'),
            ],
            'user' => auth()->check() ? [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'role' => auth()->user()->role,
                'karyawan_id' => auth()->user()->karyawan_id
            ] : null
        ]);
    })->name('debug.cuti.routes');
    
    // Test routes untuk bypass middleware sementara
    Route::get('/api-test/cuti/data', [CutiController::class, 'getData']);
    Route::get('/api-test/cuti/stats', [CutiController::class, 'getStats']);
});

/*
|--------------------------------------------------------------------------
| GLOBAL API ROUTES - UNTUK SEMUA USER YANG LOGIN (SIMPLIFIED)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    
    /* =====================================================
     |  API ABSENSI UNTUK KARYAWAN
     ===================================================== */
    Route::prefix('karyawan')->name('karyawan.')->group(function () {
        // Dashboard & Status
        Route::get('/dashboard-data', [AbsensiController::class, 'apiTodayStatus'])->name('dashboard-data');
        Route::get('/today-status', [AbsensiController::class, 'apiTodayStatus'])->name('today-status');
        Route::get('/history', [AbsensiController::class, 'apiHistory'])->name('history');
        
        // Actions
        Route::post('/absen-masuk', [AbsensiController::class, 'apiAbsenMasuk'])->name('absen-masuk');
        Route::post('/absen-pulang', [AbsensiController::class, 'apiAbsenPulang'])->name('absen-pulang');
        Route::post('/submit-izin', [AbsensiController::class, 'apiSubmitIzin'])->name('submit-izin');
    });
    
    /* =====================================================
     |  API TASKS UNTUK SEMUA ROLE
     ===================================================== */
    Route::prefix('tasks')->name('tasks.')->group(function () {
        // Detail & Specific Actions
        Route::get('/{id}', [TaskController::class, 'show'])->name('show');
        Route::get('/{id}/detail', [TaskController::class, 'getTaskDetailApi'])->name('detail');
        
        // File Upload (Global)
        Route::post('/{id}/upload-file', [TaskController::class, 'uploadTaskFile'])->name('upload.file');
        
        // Status & Completion
        Route::post('/{id}/complete', [TaskController::class, 'markAsComplete'])->name('complete');
        Route::post('/{id}/status', [TaskController::class, 'updateTaskStatus'])->name('status');
        
        // Comments
        Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
        Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
        
        // Files
        Route::get('/{id}/files', [TaskController::class, 'getTaskFiles'])->name('files');
        Route::get('/files/{file}/download', [TaskController::class, 'downloadFile'])->name('files.download');
        Route::delete('/files/{file}', [TaskController::class, 'deleteFile'])->name('files.delete');
        
        // Submission Download
        Route::get('/{id}/download', [TaskController::class, 'downloadSubmission'])->name('download.submission');
        
        // Statistics
        Route::get('/statistics', [TaskController::class, 'getStatistics'])->name('statistics');
        Route::get('/karyawan/statistics', [TaskController::class, 'getKaryawanStatistics'])->name('karyawan.statistics');
    });
    
    /* =====================================================
     |  API TASKS UNTUK KARYAWAN (Tugas yang ditugaskan ke karyawan)
     ===================================================== */
    Route::prefix('karyawan-tasks')->name('karyawan.tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'getKaryawanTasks'])->name('index');
        Route::get('/{id}/detail', [TaskController::class, 'getTaskDetailForKaryawan'])->name('detail');
    });
    
    /* =====================================================
     |  API UNTUK ADMIN/GENERAL MANAGER (Data Management)
     ===================================================== */
    Route::prefix('admin')->middleware(['role:admin,general_manager'])->name('admin.')->group(function () {
        // Absensi Data
        Route::get('/absensi', [AbsensiController::class, 'apiIndex'])->name('absensi');
        Route::get('/absensi/ketidakhadiran', [AbsensiController::class, 'apiIndexKetidakhadiran'])->name('ketidakhadiran');
        Route::get('/absensi/stats', [AbsensiController::class, 'apiStatistics'])->name('stats');
        Route::get('/kehadiran-per-divisi', [AbsensiController::class, 'apiKehadiranPerDivisi'])->name('kehadiran.divisi');
        
        // Absensi CRUD
        Route::post('/absensi', [AbsensiController::class, 'apiStore'])->name('absensi.store');
        Route::get('/absensi/{id}', [AbsensiController::class, 'apiShow'])->name('absensi.show');
        Route::put('/absensi/{id}', [AbsensiController::class, 'apiUpdate'])->name('absensi.update');
        Route::delete('/absensi/{id}', [AbsensiController::class, 'apiDestroy'])->name('absensi.destroy');
        
        // Cuti Management
        Route::post('/absensi/cuti', [AbsensiController::class, 'apiStoreCuti'])->name('absensi.cuti.store');
        Route::put('/absensi/cuti/{id}', [AbsensiController::class, 'apiUpdateCuti'])->name('absensi.cuti.update');
        
        // Approval
        Route::post('/absensi/{id}/verify', [AbsensiController::class, 'apiVerify'])->name('absensi.verify');
    });
    
    /* =====================================================
     |  API UNTUK GENERAL MANAGER TASKS
     ===================================================== */
    Route::prefix('general-manager')->middleware(['role:general_manager'])->name('general.manager.')->group(function () {
        Route::get('/tasks', [GeneralManagerTaskController::class, 'getTasksApi'])->name('tasks');
        Route::get('/tasks/statistics', [GeneralManagerTaskController::class, 'getStatistics'])->name('tasks.statistics');
        Route::get('/karyawan-by-divisi/{divisi}', [GeneralManagerTaskController::class, 'getKaryawanByDivisi'])->name('karyawan.by_divisi');
    });
    
    /* =====================================================
     |  API UNTUK MANAGER DIVISI TASKS
     ===================================================== */
    Route::prefix('manager-divisi')->middleware(['role:manager_divisi'])->name('manager.divisi.')->group(function () {
        Route::get('/tasks', [ManagerDivisiTaskController::class, 'getTasksApi'])->name('tasks');
        Route::get('/tasks/statistics', [ManagerDivisiTaskController::class, 'getStatistics'])->name('tasks.statistics');
        Route::get('/karyawan-by-divisi/{divisi}', [ManagerDivisiTaskController::class, 'getKaryawanByDivisi'])->name('karyawan.by_divisi');
    });
    
    /* =====================================================
     |  API DASHBOARD DATA UNTUK KARYAWAN
     ===================================================== */
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/karyawan', [KaryawanController::class, 'getDashboardData'])->name('karyawan');
        Route::get('/pengajuan-status', [KaryawanController::class, 'getPengajuanStatus'])->name('pengajuan.status');
        Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('submit.dinas');
    });
});

/*
|--------------------------------------------------------------------------
| Pintasan Routes (Shortcut) 
|--------------------------------------------------------------------------
*/

Route::get('/tugas', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return match ($user->role) {
            'karyawan' => redirect()->route('karyawan.tugas'),
            'admin' => redirect()->route('admin.tasks.index'),
            'general_manager' => redirect()->route('general_manajer.kelola_tugas'),
            'manager_divisi' => redirect()->route('manager_divisi.kelola_tugas'),
            default => redirect('/login')
        };
    }
    return redirect('/login');
})->name('tugas.redirect');

Route::get('/absensi', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return match ($user->role) {
            'admin' => redirect()->route('admin.absensi.index'),
            'general_manager' => redirect()->route('general_manajer.kelola_absensi'),
            'owner' => redirect()->route('owner.rekap_absen'),
            default => redirect()->route('karyawan.absensi.page')
        };
    }
    return redirect('/login');
})->name('absensi.redirect');

Route::get('/cuti', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return match ($user->role) {
            'karyawan' => redirect()->route('karyawan.cuti.index'),
            'admin' => redirect()->route('admin.cuti.index'),
            'general_manager' => redirect()->route('general_manajer.cuti.index'),
            'manager_divisi' => redirect()->route('manager_divisi.cuti.index'),
            'owner' => redirect()->route('owner.cuti.index'),
            default => redirect('/login')
        };
    }
    return redirect('/login');
})->name('cuti.redirect');

/*
|--------------------------------------------------------------------------
| Resource Routes & Additional
|--------------------------------------------------------------------------
*/

// Layanan Public & Auth
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.public');
Route::post('/layanan', [LayananController::class, 'store'])->middleware('auth');
Route::put('/layanan/{id}', [LayananController::class, 'update'])->middleware('auth');
Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->middleware('auth');

// Invoice Resource
Route::resource('invoices', InvoiceController::class);
Route::get('/invoices/{invoice}/print', function (\App\Models\Invoice $invoice) {
    return view('invoices.print', compact('invoice'));
})->name('invoices.print');

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    if (Auth::check()) {
        $user = Auth::user();
        return match ($user->role) {
            'admin' => redirect()->route('admin.beranda'),
            'karyawan' => redirect()->route('karyawan.home'),
            'general_manager' => redirect()->route('general_manajer.home'),
            'manager_divisi' => redirect()->route('manager_divisi.home'),
            'owner' => redirect()->route('owner.home'),
            'finance' => redirect()->route('finance.beranda'),
            default => redirect('/login'),
        };
    }
    return redirect('/login');
});