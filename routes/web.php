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
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\SuratKerjasamaController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GeneralManagerTaskController;
use App\Http\Controllers\ManagerDivisiTaskController;

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/
if (!function_exists('redirectToRolePage')) {
    function redirectToRolePage($user)
    {
        return match($user->role) {
            'admin', 'finance' => redirect()->route("{$user->role}.beranda"),
            'karyawan', 'general_manager', 'manager_divisi', 'owner' => redirect()->route("{$user->role}.home"),
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
        Route::get('/data-karyawan', [AdminKaryawanController::class, 'index'])->name('data_karyawan');
        Route::controller(AdminKaryawanController::class)->group(function () {
            Route::get('/karyawan', 'index')->name('karyawan.index');
            Route::post('/karyawan/store', 'store')->name('karyawan.store');
            Route::post('/karyawan/update/{id}', 'update')->name('karyawan.update');
            Route::delete('/karyawan/delete/{id}', 'destroy')->name('karyawan.delete');
        });

        // ABSENSI MANAGEMENT
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

        // KEUANGAN
        Route::get('/keuangan', function() {
            return view('admin.keuangan');
        })->name('keuangan.index');
        
        // Route untuk sidebar: /admin/data_order (hanya placeholder)
        Route::get('/data_order', function() {
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
        Route::get('/template_surat', function() {
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
        Route::get('/api/dashboard-data', function() {
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
        Route::get('/home', [KaryawanController::class, 'home'])->name('home');
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
        Route::get('/tugas', [TaskController::class, 'karyawanIndex'])->name('tugas');
        Route::get('/detail/{id}', [KaryawanController::class, 'detailPage'])->name('detail');
        
        // Route untuk list karyawan
        Route::get('/list', function () {
            return view('karyawan.list');
        })->name('list');
        
        Route::prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/{id}', [TaskController::class, 'karyawanShow'])->name('show');
        });
        
        // API untuk dashboard data karyawan
        Route::get('/dashboard-data', function() {
            $user = auth()->user();
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_tugas' => \App\Models\Task::where('assigned_to', $user->id)->count(),
                    'tugas_selesai' => \App\Models\Task::where('assigned_to', $user->id)
                                                ->where('status', 'selesai')
                                                ->count(),
                    'tugas_proses' => \App\Models\Task::where('assigned_to', $user->id)
                                                ->where('status', 'proses')
                                                ->count(),
                    'absensi_hari_ini' => 'Belum absen',
                    'user' => [
                        'name' => $user->name,
                        'role' => $user->role,
                        'divisi' => $user->divisi
                    ]
                ]
            ]);
        })->name('dashboard.data');
    });

/*
|--------------------------------------------------------------------------
| Role-Based Routes - GENERAL MANAGER (PERBAIKAN: semua route dengan prefix 'general-manajer')
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:general_manager'])
    ->prefix('general-manajer')  // Prefix: 'general-manajer'
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
        
        // ROUTE GROUP UNTUK TASKS (SEMUA DI DALAM GROUP INI)
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
        
        Route::get('/kelola-absen', function () {
            return view('general_manajer.kelola_absen');
        })->name('kelola_absen');
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
        
        Route::get('/rekap-absen', function () {
            return view('owner.rekap_absen');
        })->name('rekap_absen');
        
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
| Role-Based Routes - MANAGER DIVISI (PERBAIKAN: nama folder 'manajer_divisi' bukan 'manager_divisi')
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:manager_divisi'])
    ->prefix('manajer-divisi')  // PERUBAHAN: dari 'manager-divisi' ke 'manajer-divisi'
    ->name('manager_divisi.')
    ->group(function () {
        Route::get('/home', function () {
            return view('manajer_divisi.home');  // PERUBAHAN: dari 'manager_divisi.home' ke 'manajer_divisi.home'
        })->name('home');
        
        // Tugas untuk Manager Divisi
        Route::get('/kelola-tugas', [ManagerDivisiTaskController::class, 'index'])
            ->name('kelola_tugas');
        
        // ROUTE GROUP UNTUK TASKS (SEMUA DI DALAM GROUP INI)
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
            return view('manajer_divisi.pengelola_tugas');  // PERUBAHAN: dari 'manager_divisi' ke 'manajer_divisi'
        })->name('pengelola_tugas');
        
        Route::get('/tim-saya', function () {
            $user = auth()->user();
            $tim = \App\Models\User::where('divisi', $user->divisi)
                                   ->where('role', 'karyawan')
                                   ->get();
            return view('manajer_divisi.tim_saya', compact('tim'));  // PERUBAHAN: dari 'manager_divisi' ke 'manajer_divisi'
        })->name('tim_saya');
        
        Route::get('/absensi-tim', function () {
            return view('manajer_divisi.absensi_tim');  // PERUBAHAN: dari 'manager_divisi' ke 'manajer_divisi'
        })->name('absensi_tim');
        
        Route::get('/laporan-kinerja', function () {
            return view('manajer_divisi.laporan_kinerja');  // PERUBAHAN: dari 'manager_divisi' ke 'manajer_divisi'
        })->name('laporan_kinerja');
    });

/*
|--------------------------------------------------------------------------
| Routes untuk Pengumuman & Catatan Rapat (Global untuk yang sudah login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->resource('pengumuman', PengumumanController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/catatan-rapat', [CatatanRapatController::class, 'index'])->name('catatan_rapat.index');
    Route::post('/catatan-rapat', [CatatanRapatController::class, 'store'])->name('catatan_rapat.store');
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
        
        return match($user->role) {
            'karyawan' => redirect()->route('karyawan.tugas'),
            'admin' => redirect()->route('admin.tasks.index'),
            'general_manager' => redirect()->route('general_manager.kelola_tugas'),
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
        
        return match($user->role) {
            'admin' => redirect()->route('admin.absensi.index'),
            'general_manager' => redirect()->route('general_manager.kelola_absen'),
            default => redirect()->route('karyawan.absensi.page')
        };
    }
    return redirect('/login');
})->name('absensi.redirect');

/*
|--------------------------------------------------------------------------
| API Routes (di web.php untuk kemudahan)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('api')->group(function () {
    // API untuk karyawan dashboard data
    Route::get('/karyawan/dashboard-data', function() {
        $user = auth()->user();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_tugas' => \App\Models\Task::where('assigned_to', $user->id)->count(),
                'tugas_selesai' => \App\Models\Task::where('assigned_to', $user->id)
                                            ->where('status', 'selesai')
                                            ->count(),
                'tugas_proses' => \App\Models\Task::where('assigned_to', $user->id)
                                            ->where('status', 'proses')
                                            ->count(),
                'absensi_hari_ini' => 'Belum absen',
                'persentase_selesai' => 0,
                'user' => [
                    'name' => $user->name,
                    'role' => $user->role,
                    'divisi' => $user->divisi
                ]
            ]
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Debug Routes untuk Testing
|--------------------------------------------------------------------------
*/
if (config('app.debug')) {
    Route::get('/debug/url-test', function() {
        $user = auth()->user();
        if (!$user) return 'Not authenticated';
        
        $routes = [
            // General Manager routes - PERBAIKAN
            'general_manager.kelola_tugas' => route('general_manager.kelola_tugas'),
            'general_manager.tasks.store' => route('general_manager.tasks.store'),
            'general_manager.tasks.show' => route('general_manager.tasks.show', ['id' => 1]),
            'general_manager.tasks.update' => route('general_manager.tasks.update', ['id' => 1]),
            'general_manager.tasks.update.status' => route('general_manager.tasks.update.status', ['id' => 1]),
            'general_manager.tasks.assign' => route('general_manager.tasks.assign', ['id' => 1]),
            'general_manager.api.tasks' => route('general_manager.api.tasks'),
            'general_manager.api.tasks.statistics' => route('general_manager.api.tasks.statistics'),
            'general_manager.api.karyawan.by_divisi' => route('general_manager.api.karyawan.by_divisi', ['divisi' => 'Programmer']),
            
            // Manager Divisi routes - PERBAIKAN
            'manager_divisi.home' => route('manager_divisi.home'),
            'manager_divisi.kelola_tugas' => route('manager_divisi.kelola_tugas'),
            'manager_divisi.tasks.store' => route('manager_divisi.tasks.store'),
            'manager_divisi.tasks.show' => route('manager_divisi.tasks.show', ['id' => 1]),
            
            // Admin routes
            'admin.beranda' => route('admin.beranda'),
            
            // Karyawan routes
            'karyawan.home' => route('karyawan.home'),
        ];
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'divisi' => $user->divisi
            ],
            'routes' => $routes,
            'test_urls' => [
                // General Manager
                '/general-manajer/api/tasks' => url('/general-manajer/api/tasks'),
                '/general-manajer/api/tasks/statistics' => url('/general-manajer/api/tasks/statistics'),
                '/general-manajer/tasks' => url('/general-manajer/tasks'),
                // Manager Divisi
                '/manajer-divisi/api/tasks' => url('/manajer-divisi/api/tasks'),
                '/manajer-divisi/tasks' => url('/manajer-divisi/tasks'),
            ]
        ]);
    })->middleware('auth');
    
    Route::get('/test-route/{routeName}', function($routeName) {
        try {
            $url = route($routeName);
            return response()->json([
                'success' => true,
                'route_name' => $routeName,
                'url' => $url,
                'method' => 'GET',
                'status' => 'Exists'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'route_name' => $routeName,
                'error' => $e->getMessage(),
                'exists' => false
            ], 404);
        }
    })->middleware('auth');
    
    // Test URL langsung
    Route::get('/test-url/{url}', function($url) {
        $fullUrl = "/general-manajer/{$url}";
        return response()->json([
            'url' => $fullUrl,
            'full_url' => url($fullUrl),
            'exists' => Route::has($url) ? 'Unknown (needs specific route name)' : 'No'
        ]);
    })->middleware('auth');
}

/*
|--------------------------------------------------------------------------
| LoginController Revisi untuk Handle Admin
|--------------------------------------------------------------------------
*/
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'show')->name('login');
    Route::post('/login-process', 'login')->name('login.process');
});