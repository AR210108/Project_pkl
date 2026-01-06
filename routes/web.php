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
use App\Models\Invoice;

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
    
    // Tambahkan juga route GET untuk logout (untuk testing/backup)
    Route::get('/logout-get', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout.get')->middleware('auth');
});

/*
|--------------------------------------------------------------------------
| Role-Based Routes - ADMIN (KONSISTEN: semua underscore)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Halaman beranda admin
        Route::get('/beranda', [AdminController::class, 'beranda'])->name('beranda');

        // USER MANAGEMENT
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

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
            Route::get('/', function() {
                return view('admin.kwitansi');
            })->name('index');
            Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
        });

        // SYSTEM SETTINGS
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
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
        
        Route::prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/{id}', [TaskController::class, 'karyawanShow'])->name('show');
        });
    });

/*
|--------------------------------------------------------------------------
| Role-Based Routes - GENERAL MANAGER (KONSISTEN: prefix dash, name underscore)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:general_manager'])
    ->prefix('general-manager') // URL: /general-manager (DASH)
    ->name('general_manager.')  // Route name: general_manager. (UNDERSCORE)
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
        
        Route::get('/kelola-tugas', [GeneralManagerTaskController::class, 'index'])->name('kelola_tugas');
        
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
| Role-Based Routes - MANAGER DIVISI (KONSISTEN: prefix dash, name underscore)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:manager_divisi'])
    ->prefix('manager-divisi') // URL: /manager-divisi (DASH)
    ->name('manager_divisi.')  // Route name: manager_divisi. (UNDERSCORE)
    ->group(function () {
        Route::get('/home', function () {
            return view('manager_divisi.home');
        })->name('home');
        
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
    Route::get('/catatan-rapat', [CatatanRapatController::class, 'index'])->name('catatan_rapat.index');
    Route::post('/catatan-rapat', [CatatanRapatController::class, 'store'])->name('catatan_rapat.store');
});

/*
|--------------------------------------------------------------------------
| Pintasan Routes (Shortcut) - UPDATE DENGAN ROUTE NAME YANG BENAR
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
            'manager_divisi' => redirect()->route('manager_divisi.pengelola_tugas'),
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
| Debug Routes untuk Testing
|--------------------------------------------------------------------------
*/
if (config('app.debug')) {
    Route::get('/debug/url-test', function() {
        $user = auth()->user();
        if (!$user) return 'Not authenticated';
        
        $routes = [
            // Admin routes
            'admin.beranda' => route('admin.beranda'),
            'admin.user' => route('admin.user'),
            'admin.data_karyawan' => route('admin.data_karyawan'),
            'admin.absensi.index' => route('admin.absensi.index'),
            
            // General Manager routes
            'general_manager.home' => route('general_manager.home'),
            'general_manager.data_karyawan' => route('general_manager.data_karyawan'),
            'general_manager.kelola_tugas' => route('general_manager.kelola_tugas'),
            
            // Manager Divisi routes
            'manager_divisi.home' => route('manager_divisi.home'),
            'manager_divisi.pengelola_tugas' => route('manager_divisi.pengelola_tugas'),
            
            // Karyawan routes
            'karyawan.home' => route('karyawan.home'),
            'karyawan.tugas' => route('karyawan.tugas'),
            
            // Finance routes
            'finance.beranda' => route('finance.beranda'),
            
            // Owner routes
            'owner.home' => route('owner.home'),
        ];
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'divisi' => $user->divisi
            ],
            'routes' => $routes,
            'accessible_routes' => array_filter($routes, function($url) {
                try {
                    // Coba akses route untuk cek apakah bisa diakses
                    return true;
                } catch (\Exception $e) {
                    return false;
                }
            }, ARRAY_FILTER_USE_KEY)
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