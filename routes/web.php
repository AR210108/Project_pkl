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
use App\Http\Controllers\ManagerDivisiTaskController;
use App\Http\Controllers\KaryawanProfileController;

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
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return redirectToRolePage($user);
    }
    return view('home');
})->name('landing');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Route logout dengan POST
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // Route GET untuk logout (backup/fallback)
    Route::get('/logout-get', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout.get');
});

// Pegawai resource routes (using KaryawanController methods for pegawai management)
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
        // Halaman beranda admin
        Route::get('/beranda', [AdminController::class, 'beranda'])->name('beranda');

        // Route untuk sidebar: /admin/home → redirect ke /admin/beranda
        Route::get('/home', function () {
            return redirect()->route('admin.beranda');
        });

        // ========== API ROUTES FOR DATA ==========
        // API untuk catatan rapat dan users
        Route::get('/catatan_rapat/data', [CatatanRapatController::class, 'getData'])->name('catatan_rapat.data');
        Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');

        // USER MANAGEMENT
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

        // Route untuk sidebar: /admin/data_user → redirect ke /admin/user
        Route::get('/data_user', function () {
            return redirect()->route('admin.user');
        });

        // KARYAWAN MANAGEMENT
        Route::get('/data_karyawan', [AdminKaryawanController::class, 'index'])->name('data_karyawan');
        Route::controller(AdminKaryawanController::class)->group(function () {
            Route::get('/karyawan', 'index')->name('karyawan.index');
            Route::post('/karyawan/store', 'store')->name('karyawan.store');
            Route::post('/karyawan/update/{id}', 'update')->name('karyawan.update');
            Route::delete('/karyawan/delete/{id}', 'destroy')->name('karyawan.delete');
        });

        // ABSENSI MANAGEMENT
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

        // KEUANGAN
        Route::get('/keuangan', function () {
            return view('admin.keuangan');
        })->name('keuangan.index');

        // Route untuk sidebar: /admin/data_order (hanya placeholder)
        Route::get('/data_order', function () {
            return view('admin.data_order');
        })->name('data_order');

        // TASK MANAGEMENT
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('index');
            Route::get('/create', [TaskController::class, 'create'])->name('create');
            Route::post('/', [TaskController::class, 'store'])->name('store');
            Route::get('/{id}', [TaskController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [TaskController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TaskController::class, 'update'])->name('update');
            Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy');
        });

        // LAYANAN MANAGEMENT
        Route::prefix('layanan')->name('layanan.')->group(function () {
            Route::get('/', [LayananController::class, 'index'])->name('index');
            Route::post('/', [LayananController::class, 'store'])->name('store');
            Route::put('/{id}', [LayananController::class, 'update'])->name('update');
            Route::delete('/{id}', [LayananController::class, 'destroy'])->name('delete');
        });

        // SURAT KERJASAMA MANAGEMENT
        Route::prefix('surat-kerjasama')->name('surat_kerjasama.')->group(function () {
            Route::get('/', [SuratKerjasamaController::class, 'index'])->name('index');
            Route::get('/create', [SuratKerjasamaController::class, 'create'])->name('create');
            Route::post('/', [SuratKerjasamaController::class, 'store'])->name('store');
            Route::get('/{id}', [SuratKerjasamaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SuratKerjasamaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SuratKerjasamaController::class, 'update'])->name('update');
            Route::delete('/{id}', [SuratKerjasamaController::class, 'destroy'])->name('destroy');
        });

        // Route untuk sidebar: /admin/surat_kerjasama → redirect ke index
        Route::get('/surat_kerjasama', function () {
            return redirect()->route('admin.surat_kerjasama.index');
        });

        // Route untuk sidebar: /template_surat (hanya placeholder)
        Route::get('/template_surat', function () {
            return view('admin.template_surat');
        })->name('template_surat');

        // INVOICE MANAGEMENT
        Route::prefix('invoice')->name('invoice.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/create', [InvoiceController::class, 'create'])->name('create');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
        });

        // KWITANSI MANAGEMENT
        Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            Route::get('/', [KwitansiController::class, 'index'])->name('index');
            Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
        });

        // SYSTEM SETTINGS
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');

        // Route untuk sidebar: /admin/catatan_rapat → redirect ke global route
        Route::get('/catatan_rapat', function () {
            return redirect()->route('catatan_rapat.index');
        });

        // Route untuk sidebar: /admin/pengumuman → redirect ke global route
        Route::get('/pengumuman', function () {
            return redirect()->route('pengumuman.index');
        });

        // API untuk dashboard data
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
| Role-Based Routes - KARYAWAN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        // HALAMAN UTAMA
        Route::get('/home', [KaryawanController::class, 'home'])->name('home');

        // ABSENSI
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
        Route::get('/absensi/list', [KaryawanController::class, 'absensiListPage'])->name('absensi.list');
        Route::get('/detail/{id}', [KaryawanController::class, 'detailPage'])->name('detail');

        // TUGAS - PERBAIKAN: Gunakan KaryawanController@listPage
        Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
        Route::get('/tugas', [KaryawanController::class, 'listPage'])->name('tugas');

        // TUGAS DETAIL
        Route::prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/{id}', [TaskController::class, 'karyawanShow'])->name('show');

            // API untuk update status tugas
            Route::put('/{id}/status', [TaskController::class, 'updateStatus'])->name('update.status');

            // API untuk upload file tugas
            Route::post('/{id}/upload', [TaskController::class, 'uploadFile'])->name('upload');

            // API untuk comment
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
        });

        // API Routes untuk Karyawan
        Route::prefix('api')->name('api.')->group(function () {
            // Dashboard data - FIXED ROUTE
            Route::get('/dashboard', [KaryawanController::class, 'getDashboardData'])->name('dashboard');
            Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardData'])->name('dashboard.data'); // Alias untuk kompatibilitas
    
            // Absensi - Status Hari Ini
            Route::get('/today-status', [KaryawanController::class, 'getTodayStatus'])->name('today.status');

            // History absensi
            Route::get('/history', [KaryawanController::class, 'getHistory'])->name('history');
            Route::get('/history/list', [KaryawanController::class, 'getHistory'])->name('history.list');

            Route::get('/pengajuan-status', [KaryawanController::class, 'getPengajuanStatus'])->name('pengajuan.status');

            // Action routes
            Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
            Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');
            Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('submit.izin');
            Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('submit.dinas');

            // Task statistics
            Route::get('/tasks/statistics', [TaskController::class, 'getStatistics'])->name('tasks.statistics');

            // Task list for karyawan
            Route::get('/tasks', [KaryawanController::class, 'getTasksApi'])->name('tasks');
        });

        //profile
        // PROFILE (BENAR)
        Route::get('/profile', [KaryawanProfileController::class, 'index'])
            ->name('profile');

        Route::post('/profile/update', [KaryawanProfileController::class, 'update'])
            ->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| Role-Based Routes - GENERAL MANAGER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:general_manager'])
    ->prefix('general-manajer')
    ->name('general_manajer.')
    ->group(function () {
        Route::get('/home', function () {
            return view('general_manajer.home');
        })->name('home');

        Route::get('/data-karyawan', function () {
            return view('general_manajer.data_karyawan');
        })->name('data_karyawan');

        Route::get('/layanan', function () {
            return view('general_manajer.data_layanan');
        })->name('layanan');

        Route::get('/kelola-order', function () {
            return view('general_manajer.kelola_order');
        })->name('kelola_order');

        // TUGAS MANAGEMENT UNTUK GENERAL MANAGER
        Route::get('/kelola-tugas', [GeneralManagerTaskController::class, 'index'])
            ->name('kelola_tugas');

        // ROUTE GROUP UNTUK TASKS
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::post('/', [GeneralManagerTaskController::class, 'store'])
                ->name('store');

            Route::get('/{id}', [GeneralManagerTaskController::class, 'show'])
                ->name('show');

            Route::put('/{id}', [GeneralManagerTaskController::class, 'update'])
                ->name('update');

            Route::put('/{id}/status', [GeneralManagerTaskController::class, 'updateStatus'])
                ->name('update.status');

            Route::post('/{id}/assign', [GeneralManagerTaskController::class, 'assignToKaryawan'])
                ->name('assign');

            // Tambahkan route DELETE untuk destroy
            Route::delete('/{id}', [GeneralManagerTaskController::class, 'destroy'])
                ->name('destroy');

            // Tambahkan route POST khusus untuk delete (fallback)
            Route::post('/{id}/delete', [GeneralManagerTaskController::class, 'destroy'])
                ->name('delete');
        });

        // API Routes untuk General Manager
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/tasks', [GeneralManagerTaskController::class, 'getTasksApi'])
                ->name('tasks');

            Route::get('/tasks/statistics', [GeneralManagerTaskController::class, 'getStatistics'])
                ->name('tasks.statistics');

            Route::get('/karyawan-by-divisi/{divisi}', [GeneralManagerTaskController::class, 'getKaryawanByDivisi'])
                ->name('karyawan.by_divisi');
        });

        // KELOLA ABSENSI
        Route::get('/kelola-absen', [AbsensiController::class, 'kelolaAbsen'])->name('kelola_absen');
        
        // NEW: KELOLA ABSENSI DASHBOARD (with API)
        Route::get('/kelola-absensi', [AbsensiController::class, 'kelolaAbsensi'])->name('kelola_absensi');
        
        // API Routes untuk Absensi (General Manager)
        Route::prefix('api')->group(function () {
            Route::get('/absensi', [AbsensiController::class, 'apiIndex'])->name('api.absensi');
            Route::get('/absensi/ketidakhadiran', [AbsensiController::class, 'apiIndexKetidakhadiran'])->name('api.ketidakhadiran');
            Route::get('/absensi/stats', [AbsensiController::class, 'apiStatistics'])->name('api.stats');
        });
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
        Route::get('/home', function () {
            return view('owner.home');
        })->name('home');

        Route::get('/rekap-absen', [AbsensiController::class, 'rekapAbsensi'])->name('rekap_absen');

        Route::get('/laporan', function () {
            return view('owner.laporan');
        })->name('laporan');
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
        Route::get('/beranda', function () {
            return view('finance.beranda');
        })->name('beranda');

        Route::get('/data-layanan', function () {
            return view('finance.data_layanan');
        })->name('data_layanan');

        Route::get('/pembayaran', function () {
            return view('finance.data_pembayaran');
        })->name('pembayaran');

        Route::get('/laporan-keuangan', function () {
            return view('finance.laporan_keuangan');
        })->name('laporan_keuangan');
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
        Route::get('/home', function () {
            return view('manager_divisi.home');
        })->name('home');

        // Tugas untuk Manager Divisi
        Route::get('/kelola-tugas', [ManagerDivisiTaskController::class, 'index'])
            ->name('kelola_tugas');

        // ROUTE GROUP UNTUK TASKS
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::post('/', [ManagerDivisiTaskController::class, 'store'])
                ->name('store');

            Route::get('/{id}', [ManagerDivisiTaskController::class, 'show'])
                ->name('show');

            Route::put('/{id}', [ManagerDivisiTaskController::class, 'update'])
                ->name('update');

            Route::put('/{id}/status', [ManagerDivisiTaskController::class, 'updateStatus'])
                ->name('update.status');

            Route::post('/{id}/assign', [ManagerDivisiTaskController::class, 'assignToKaryawan'])
                ->name('assign');

            // Tambahkan route DELETE untuk destroy
            Route::delete('/{id}', [ManagerDivisiTaskController::class, 'destroy'])
                ->name('destroy');

            // Tambahkan route POST khusus untuk delete (fallback)
            Route::post('/{id}/delete', [ManagerDivisiTaskController::class, 'destroy'])
                ->name('delete');
        });

        // API Routes untuk Manager Divisi
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/tasks', [ManagerDivisiTaskController::class, 'getTasksApi'])
                ->name('tasks');

            Route::get('/tasks/statistics', [ManagerDivisiTaskController::class, 'getStatistics'])
                ->name('tasks.statistics');

            Route::get('/karyawan-by-divisi/{divisi}', [ManagerDivisiTaskController::class, 'getKaryawanByDivisi'])
                ->name('karyawan.by_divisi');
        });

        Route::get('/pengelola-tugas', function () {
            return view('manager_divisi.pengelola_tugas');
        })->name('pengelola_tugas');

        Route::get('/tim-saya', function () {
            $user = auth()->user();
            $tim = \App\Models\User::where('divisi', $user->divisi)
                ->where('role', 'karyawan')
                ->get();
            return view('manager_divisi.tim_saya', compact('tim'));
        })->name('tim_saya');

        Route::get('/absensi-tim', function () {
            return view('manager_divisi.absensi_tim');
        })->name('absensi_tim');

        Route::get('/laporan-kinerja', function () {
            return view('manager_divisi.laporan_kinerja');
        })->name('laporan_kinerja');
    });

/*
|--------------------------------------------------------------------------
| Routes untuk Pengumuman & Catatan Rapat (Global untuk yang sudah login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->resource('pengumuman', PengumumanController::class);
Route::middleware(['auth'])->group(function () {
    // Halaman catatan rapat
    Route::get('/catatan-rapat', [CatatanRapatController::class, 'index'])->name('catatan_rapat.index');

    // CRUD catatan rapat
    Route::post('/catatan-rapat', [CatatanRapatController::class, 'store'])->name('catatan_rapat.store');
    Route::put('/catatan-rapat/{id}', [CatatanRapatController::class, 'update'])->name('catatan_rapat.update');
    Route::delete('/catatan-rapat/{id}', [CatatanRapatController::class, 'destroy'])->name('catatan_rapat.destroy');
    Route::get('/catatan-rapat/{id}', [CatatanRapatController::class, 'show'])->name('catatan_rapat.show');

    // API endpoints untuk catatan rapat dan users (accessible by all authenticated users)
    Route::get('/catatan_rapat/data', [CatatanRapatController::class, 'getData'])->name('catatan_rapat.data');
    Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
});

/*
|--------------------------------------------------------------------------
| Global API Routes (Tanpa prefix role) - UNTUK SEMUA USER YANG LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('api')->group(function () {
    // Routes yang bisa diakses semua role yang sudah login
    Route::get('/karyawan/history', [KaryawanController::class, 'getHistory'])->name('api.karyawan.history');
    Route::get('/karyawan/dashboard-data', [KaryawanController::class, 'getDashboardData'])->name('api.karyawan.dashboard-data');
    Route::get('/karyawan/today-status', [KaryawanController::class, 'getTodayStatus'])->name('api.karyawan.today-status');

    // Absensi actions
    Route::post('/karyawan/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('api.karyawan.absen-masuk');
    Route::post('/karyawan/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('api.karyawan.absen-pulang');
    Route::post('/karyawan/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('api.karyawan.submit-izin');
    Route::post('/karyawan/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('api.karyawan.submit-dinas');

    // Pengajuan status
    Route::get('/karyawan/pengajuan-status', [KaryawanController::class, 'getPengajuanStatus'])->name('api.karyawan.pengajuan-status');

    // Task related APIs
    Route::get('/tasks/{id}', [TaskController::class, 'getTaskApi'])->name('api.tasks.show');
    Route::get('/tasks/{id}/comments', [TaskController::class, 'getComments'])->name('api.tasks.comments');
    Route::get('/tasks/statistics', [TaskController::class, 'getStatistics'])->name('api.tasks.statistics');

    // Untuk karyawan tasks
    Route::get('/karyawan/tasks', [KaryawanController::class, 'getTasksApi'])->name('api.karyawan.tasks');
});

/*
|--------------------------------------------------------------------------
| Pintasan Routes (Shortcut)
|--------------------------------------------------------------------------
*/

// Redirect setelah login
Route::get('/redirect-login', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return redirectToRolePage($user);
    }
    return redirect('/login');
})->name('redirect.login');

// Pintasan untuk tugas berdasarkan role
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

// Pintasan untuk absensi berdasarkan role
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

/*
|--------------------------------------------------------------------------
| Resource Routes
|--------------------------------------------------------------------------
*/

Route::resource('invoices', InvoiceController::class);
Route::get('/invoices/{invoice}/print', function (\App\Models\Invoice $invoice) {
    return view('invoices.print', compact('invoice'));
})->name('invoices.print');

/*
|--------------------------------------------------------------------------
| Debug Routes untuk Testing
|--------------------------------------------------------------------------
*/
if (config('app.debug')) {
    // DEBUG ABSENSI GENERAL MANAGER
    Route::get('/debug/absensi-gm', function () {
        if (!auth()->check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $user = auth()->user();
        if ($user->role !== 'general_manager') {
            return response()->json(['error' => 'Only for general manager'], 403);
        }
        
        echo "<h2>Debug Absensi General Manager</h2>";
        
        // 1. Cek database
        echo "<h3>1. Database Check</h3>";
        $totalUsers = \App\Models\User::where('role', 'karyawan')->count();
        $totalAbsensi = \App\Models\Absensi::count();
        $absensiThisMonth = \App\Models\Absensi::whereBetween('tanggal', [
            now()->startOfMonth()->format('Y-m-d'),
            now()->endOfMonth()->format('Y-m-d')
        ])->count();
        
        echo "Total karyawan: {$totalUsers}<br>";
        echo "Total data absensi: {$totalAbsensi}<br>";
        echo "Absensi bulan ini: {$absensiThisMonth}<br>";
        
        // 2. Cek query dari controller
        echo "<h3>2. Test Query dari kelolaAbsen()</h3>";
        
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');
        
        // Query yang seharusnya di controller
        $queries = [
            'Hadir' => \App\Models\Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereNotNull('jam_masuk')
                ->where('approval_status', 'approved'),
            
            'Izin' => \App\Models\Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('jenis_ketidakhadiran', 'izin')
                ->where('approval_status', 'approved'),
            
            'Cuti' => \App\Models\Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('jenis_ketidakhadiran', 'cuti')
                ->where('approval_status', 'approved'),
            
            'Sakit' => \App\Models\Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('jenis_ketidakhadiran', 'sakit')
                ->where('approval_status', 'approved'),
            
            'Dinas Luar' => \App\Models\Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('jenis_ketidakhadiran', 'dinas-luar')
                ->where('approval_status', 'approved'),
        ];
        
        foreach ($queries as $label => $query) {
            $count = $query->count();
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            
            echo "<strong>{$label}:</strong> {$count}<br>";
            echo "SQL: {$sql}<br>";
            echo "Bindings: " . json_encode($bindings) . "<br><br>";
        }
        
        // 3. Cek sample data
        echo "<h3>3. Sample Data (10 terbaru)</h3>";
        $sample = \App\Models\Absensi::with('user')
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();
        
        if ($sample->isEmpty()) {
            echo "<p style='color: red;'>TIDAK ADA DATA ABSENSI!</p>";
        } else {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>User</th><th>Tanggal</th><th>Jam Masuk</th><th>Jenis</th><th>Approval</th></tr>";
            
            foreach ($sample as $item) {
                echo "<tr>";
                echo "<td>{$item->id}</td>";
                echo "<td>" . ($item->user ? $item->user->name : 'N/A') . "</td>";
                echo "<td>{$item->tanggal}</td>";
                echo "<td>" . ($item->jam_masuk ?? 'NULL') . "</td>";
                echo "<td>" . ($item->jenis_ketidakhadiran ?? 'NULL') . "</td>";
                echo "<td>{$item->approval_status}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // 4. Buat data test jika tidak ada
        if ($totalAbsensi === 0 && $totalUsers > 0) {
            echo "<h3>4. Membuat Data Test</h3>";
            
            $users = \App\Models\User::where('role', 'karyawan')->take(2)->get();
            $created = 0;
            
            foreach ($users as $user) {
                // Hadir hari ini
                \App\Models\Absensi::create([
                    'user_id' => $user->id,
                    'tanggal' => now()->format('Y-m-d'),
                    'jam_masuk' => '08:00',
                    'jam_pulang' => '17:00',
                    'approval_status' => 'approved',
                ]);
                $created++;
                
                // Izin kemarin
                \App\Models\Absensi::create([
                    'user_id' => $user->id,
                    'tanggal' => now()->subDay()->format('Y-m-d'),
                    'jenis_ketidakhadiran' => 'izin',
                    'keterangan' => 'Ijin keluarga',
                    'approval_status' => 'approved',
                ]);
                $created++;
                
                // Cuti 2 hari lalu
                \App\Models\Absensi::create([
                    'user_id' => $user->id,
                    'tanggal' => now()->subDays(2)->format('Y-m-d'),
                    'jenis_ketidakhadiran' => 'cuti',
                    'keterangan' => 'Cuti tahunan',
                    'approval_status' => 'approved',
                ]);
                $created++;
            }
            
            echo "Created {$created} test records<br>";
            echo "Total absensi sekarang: " . \App\Models\Absensi::count() . "<br>";
        }
        
        dd('Debug selesai');
    })->middleware('auth');

    Route::get('/debug/url-test', function () {
        $user = auth()->user();
        if (!$user)
            return 'Not authenticated';

        $routes = [
            // General Manager routes
            'general_manajer.kelola_tugas' => route('general_manajer.kelola_tugas'),
            'general_manajer.tasks.store' => route('general_manajer.tasks.store'),
            'general_manajer.tasks.show' => route('general_manajer.tasks.show', ['id' => 1]),
            'general_manajer.tasks.update' => route('general_manajer.tasks.update', ['id' => 1]),
            'general_manajer.tasks.destroy' => route('general_manajer.tasks.destroy', ['id' => 1]),
            'general_manajer.tasks.delete' => route('general_manajer.tasks.delete', ['id' => 1]),
            'general_manajer.tasks.update.status' => route('general_manajer.tasks.update.status', ['id' => 1]),
            'general_manajer.tasks.assign' => route('general_manajer.tasks.assign', ['id' => 1]),
            'general_manajer.api.tasks' => route('general_manajer.api.tasks'),
            'general_manajer.api.tasks.statistics' => route('general_manajer.api.tasks.statistics'),
            
            // General Manager Absensi routes
            'general_manajer.kelola_absensi' => route('general_manajer.kelola_absensi'),
            'general_manajer.kelola_absen' => route('general_manajer.kelola_absen'),
            'general_manajer.api.absensi' => route('general_manajer.api.absensi'),
            'general_manajer.api.ketidakhadiran' => route('general_manajer.api.ketidakhadiran'),
            'general_manajer.api.stats' => route('general_manajer.api.stats'),

            // Karyawan routes
            'karyawan.home' => route('karyawan.home'),
            'karyawan.list' => route('karyawan.list'),
            'karyawan.tugas' => route('karyawan.tugas'),
            'karyawan.tugas.show' => route('karyawan.tugas.show', ['id' => 1]),
            'karyawan.api.dashboard' => route('karyawan.api.dashboard'),
            'karyawan.api.dashboard-data' => route('karyawan.api.dashboard.data'),
            'karyawan.api.history' => route('karyawan.api.history'),
            'karyawan.api.today-status' => route('karyawan.api.today.status'),
            'karyawan.api.tasks.statistics' => route('karyawan.api.tasks.statistics'),

            // Manager Divisi routes
            'manager_divisi.home' => route('manager_divisi.home'),
            'manager_divisi.kelola_tugas' => route('manager_divisi.kelola_tugas'),

            // Admin routes
            'admin.beranda' => route('admin.beranda'),
            'admin.absensi.index' => route('admin.absensi.index'),

            // Owner routes
            'owner.rekap_absen' => route('owner.rekap_absen'),

            // Global API routes
            'api.karyawan.history' => route('api.karyawan.history'),
            'api.karyawan.dashboard-data' => route('api.karyawan.dashboard-data'),
            'api.karyawan.today-status' => route('api.karyawan.today-status'),
            'api.karyawan.absen-masuk' => route('api.karyawan.absen-masuk'),
            'api.karyawan.absen-pulang' => route('api.karyawan.absen-pulang'),
            'api.karyawan.submit-izin' => route('api.karyawan.submit-izin'),
            'api.karyawan.submit-dinas' => route('api.karyawan.submit-dinas'),
            'api.karyawan.pengajuan-status' => route('api.karyawan.pengajuan-status'),
            'api.karyawan.tasks' => route('api.karyawan.tasks'),
        ];

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'divisi' => $user->divisi
            ],
            'routes' => $routes,
        ]);
    })->middleware('auth');

    Route::get('/test-karyawan-tugas', function () {
        $user = auth()->user();

        if ($user->role !== 'karyawan') {
            return response()->json(['error' => 'Only for karyawan role']);
        }

        $userDivisi = $user->divisi;

        $tasksAssignedToMe = \App\Models\Task::where('assigned_to', $user->id)->get();
        $tasksToDivisi = \App\Models\Task::where('target_type', 'divisi')
            ->where('target_divisi', $userDivisi)
            ->get();

        $allTasks = \App\Models\Task::where(function ($query) use ($user, $userDivisi) {
            $query->where('assigned_to', $user->id)
                ->orWhere(function ($q) use ($userDivisi) {
                    $q->where('target_type', 'divisi')
                        ->where('target_divisi', $userDivisi);
                });
        })->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'divisi' => $userDivisi,
            ],
            'tasks_assigned_to_me' => $tasksAssignedToMe->count(),
            'tasks_to_divisi' => $tasksToDivisi->count(),
            'total_tasks' => $allTasks->count(),
            'tasks_detail' => $allTasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'judul' => $task->judul,
                    'target_type' => $task->target_type,
                    'assigned_to' => $task->assigned_to,
                    'target_divisi' => $task->target_divisi,
                ];
            }),
        ]);
    })->middleware('auth');
}