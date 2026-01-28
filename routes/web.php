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
use App\Http\Controllers\OrderController;
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

use App\Http\Controllers\OwnerController;

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
Route::get('/users/data', [UserController::class, 'data'])
    ->middleware('auth');

// Landing Page (ubah dari LayananController ke LandingPageController)
Route::get('/', [LandingPageController::class, 'index'])->name('home');

// API endpoint untuk kontak (public)
Route::get('/api/contact', [SettingController::class, 'getContactData'])->name('api.contact');

// API endpoint untuk tentang (public)
Route::get('/api/about', [SettingController::class, 'getAboutData'])->name('api.about');

// API endpoint untuk artikel (public)
Route::get('/api/articles', [SettingController::class, 'getArticlesData'])->name('api.articles');

// --- TAMBAHKAN ROUTE API PORTOFOLIO (PUBLIC) ---
Route::get('/api/portfolios', [SettingController::class, 'getPortfoliosData'])->name('api.portfolios');

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
});

// Orders & invoices routes (simple resource for authenticated users)
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class)->only(['index','show','store','update','destroy']);
    Route::get('invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
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
            
            // Upload & File Admin
            Route::post('/{id}/upload-file', [TaskController::class, 'uploadFileAdmin'])->name('upload.file');
            Route::get('/{id}/files', [TaskController::class, 'getTaskFiles'])->name('files');
            Route::get('/files/{file}/download', [TaskController::class, 'downloadFile'])->name('files.download');
            Route::delete('/files/{file}', [TaskController::class, 'deleteFile'])->name('files.delete');
            
            // Comments
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
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

        Route::get('/data_project', [DataProjectController::class, 'admin'])->name('data_project');
        Route::post('/project', [DataProjectController::class, 'store'])->name('project.store');
        Route::put('/project/{id}', [DataProjectController::class, 'update'])->name('project.update');
        Route::delete('/project/{id}', [DataProjectController::class, 'destroy'])->name('project.destroy');

        // Route untuk sidebar: /admin/surat_kerjasama → redirect ke index
        Route::get('/surat_kerjasama', function () {
            return redirect()->route('admin.surat_kerjasama.index');
        });

        // Route untuk sidebar: /admin/template_surat (hanya placeholder)
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
        // SYSTEM SETTINGS
        Route::prefix('settings')->name('settings.')->group(function () {
            // Halaman utama pengaturan
            Route::get('/', [SettingController::class, 'index'])->name('index');

            // Pengaturan Profil
            Route::post('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');

            // Pengaturan Akun
            Route::post('/account', [SettingController::class, 'updateAccount'])->name('account.update');

            // Pengaturan Notifikasi
            Route::post('/notifications', [SettingController::class, 'updateNotifications'])->name('notifications.update');

            // Pengaturan Kata Sandi
            Route::post('/password', [SettingController::class, 'updatePassword'])->name('password.update');

            // Keluar dari semua perangkat
            Route::post('/logout-all', [SettingController::class, 'logoutAll'])->name('logout.all');

            // Pengaturan Kontak (untuk landing page)
            Route::get('/contact', [SettingController::class, 'contact'])->name('contact');
            Route::post('/contact', [SettingController::class, 'updateContact'])->name('contact.update');

            // API untuk mendapatkan data kontak
            Route::get('/contact-data', [SettingController::class, 'getContactData'])->name('contact.data');

            // Pengaturan Tentang (untuk landing page)
            Route::get('/about', [SettingController::class, 'about'])->name('about');
            Route::post('/about', [SettingController::class, 'updateAbout'])->name('about.update');

            // API untuk mendapatkan data tentang
            Route::get('/about-data', [SettingController::class, 'getAboutData'])->name('about.data');

            // ==========================================================
            // ROUTE UNTUK PENGELOLAAN ARTIKEL
            // ==========================================================
            // Menampilkan halaman pengaturan artikel
            Route::get('/articles', [SettingController::class, 'articles'])->name('articles');

            // >>> TAMBAHKAN ROUTE INI UNTUK MENGAMBIL DATA SATU ARTIKEL <<<
            Route::get('/articles/{id}', [SettingController::class, 'getArticle'])->name('articles.get');

            // Menyimpan artikel baru
            Route::post('/articles', [SettingController::class, 'storeArticle'])->name('articles.store');

            // Mengupdate artikel
            Route::put('/articles/{id}', [SettingController::class, 'updateArticle'])->name('articles.update');

            // Menghapus artikel
            Route::delete('/articles/{id}', [SettingController::class, 'deleteArticle'])->name('articles.delete');

            // Mendapatkan data artikel untuk API (internal)
            Route::get('/articles-data', [SettingController::class, 'getArticlesData'])->name('articles.data');

            // ==========================================================
            // --- TAMBAHKAN ROUTE UNTUK PENGELOLAAN PORTOFOLIO ---
            // ==========================================================
            // Menampilkan halaman pengaturan portofolio
            Route::get('/portfolios', [SettingController::class, 'portfolios'])->name('portfolios');

            // Mendapatkan data satu portofolio untuk diedit
            Route::get('/portfolios/{id}', [SettingController::class, 'getPortfolio'])->name('portfolios.get');

            // Menyimpan portofolio baru
            Route::post('/portfolios', [SettingController::class, 'storePortfolio'])->name('portfolios.store');

            // Mengupdate portofolio
            Route::put('/portfolios/{id}', [SettingController::class, 'updatePortfolio'])->name('portfolios.update');

            // Menghapus portofolio
            Route::delete('/portfolios/{id}', [SettingController::class, 'deletePortfolio'])->name('portfolios.delete');

            // Mendapatkan data portofolio untuk API (internal)
            Route::get('/portfolios-data', [SettingController::class, 'getPortfoliosData'])->name('portfolios.data');

        });

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
| Role-Based Routes - KARYAWAN (FIXED & CLEANED)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Role-Based Routes - KARYAWAN (FINAL & CLEAN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        // ==================== HALAMAN UTAMA & DASHBOARD ====================
        Route::get('/home', [KaryawanController::class, 'home'])->name('home');

        // ==================== CUTI ROUTES (FINAL FIX - FLAT STRUCTURE) ====================
        // Menggunakan struktur flat untuk menghindari error naming conflict

        Route::get('/cuti', [CutiController::class, 'index'])->name('cuti.index');

        // --- DATA & API ROUTES ---
        Route::get('/cuti/data', [CutiController::class, 'getData'])->name('cuti.data');
        Route::get('/cuti/data-table', [CutiController::class, 'getDataTable'])->name('cuti.data-table');
        Route::get('/cuti/stats', [CutiController::class, 'getStats'])->name('cuti.stats'); 
        Route::get('/cuti/check-available-days', [CutiController::class, 'checkAvailableDays'])->name('cuti.check-available-days');
        Route::get('/cuti/available-days', [CutiController::class, 'getAvailableDays'])->name('cuti.available-days');
        Route::post('/cuti/calculate-duration', [CutiController::class, 'calculateDuration'])->name('cuti.calculate-duration');
        Route::get('/cuti/dashboard-stats', [CutiController::class, 'dashboardStats'])->name('cuti.dashboard-stats');

        // --- CRUD CUTI ---
        Route::get('/cuti/create', [CutiController::class, 'create'])->name('cuti.create');
        Route::post('/cuti', [CutiController::class, 'store'])->name('cuti.store');
        Route::get('/cuti/{cuti}', [CutiController::class, 'show'])->name('cuti.show');
        Route::get('/cuti/{cuti}/edit', [CutiController::class, 'edit'])->name('cuti.edit');
        Route::put('/cuti/{cuti}', [CutiController::class, 'update'])->name('cuti.update');
        Route::delete('/cuti/{cuti}', [CutiController::class, 'destroy'])->name('cuti.destroy');

        // --- ACTIONS ---
        Route::post('/cuti/{cuti}/cancel', [CutiController::class, 'cancel'])->name('cuti.cancel');
        Route::get('/cuti/export', [CutiController::class, 'export'])->name('cuti.export');

        // ==================== PROFILE ====================
        Route::get('/profile', [KaryawanProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [KaryawanProfileController::class, 'update'])->name('profile.update');

        // ==================== ABSENSI ====================
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
        Route::get('/absensi/list', [KaryawanController::class, 'absensiListPage'])->name('absensi.list');
        
        // ==================== TUGAS (TASKS) ====================
        Route::get('/tugas', [KaryawanController::class, 'listPage'])->name('tugas');
        
        Route::prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
            Route::get('/{id}', [TaskController::class, 'karyawanShow'])->name('show');
            Route::put('/{id}/status', [TaskController::class, 'updateStatus'])->name('update.status');
            Route::post('/{id}/upload', [TaskController::class, 'uploadFile'])->name('upload');
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
        });

        // ==================== API ROUTES (KHUSUS) ====================
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/dashboard', [KaryawanController::class, 'getDashboardData'])->name('dashboard');
            Route::get('/today-status', [KaryawanController::class, 'getTodayStatus'])->name('today.status');
            Route::get('/history', [KaryawanController::class, 'getHistory'])->name('history');
            Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
            Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');
            Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('submit.izin');
            Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('submit.dinas');
            Route::get('/tasks/statistics', [TaskController::class, 'getStatistics'])->name('tasks.statistics');
        });

        // ==================== LEGACY / REDIRECTS ====================
        Route::get('/detail/{id}', [KaryawanController::class, 'detailPage'])->name('detail');
        Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
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
        Route::get('/home', function () {
            return view('general_manajer.home');
        })->name('home');

        Route::get('/data_karyawan', [AdminKaryawanController::class, 'karyawanGeneral'])
            ->name('data_karyawan');

        Route::get('/layanan', [LayananController::class, 'Generalindex'])
            ->name('layanan');

        // DATA PROJECT (GENERAL MANAGER)
        Route::get('/data_project', [DataProjectController::class, 'index'])
            ->name('data_project');

        Route::post('/data_project', [DataProjectController::class, 'store'])
            ->name('data_project.store');

        Route::put('/data_project/{id}', [DataProjectController::class, 'update'])
            ->name('data_project.update');

        Route::delete('/data_project/{id}', [DataProjectController::class, 'destroy'])
            ->name('data_project.destroy');

        // TUGAS MANAGEMENT UNTUK GENERAL MANAGER
        Route::get('/kelola-tugas', [GeneralManagerTaskController::class, 'index'])
            ->name('kelola_tugas');

        // Cuti Management untuk General Manager
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'indexGeneralManager'])->name('index');
            Route::get('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
        });

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
        
        // Absensi Management - MODIFIED
        Route::get('/kelola-absen', [AbsensiController::class, 'kelolaAbsen'])->name('kelola_absen');
        Route::get('/kelola-absensi', [AbsensiController::class, 'kelolaAbsensi'])->name('kelola_absensi');
        
        Route::get('/tim_dan_divisi', function () {
            return view('general_manajer/tim_dan_divisi');
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
        Route::get('/home', function () { return view('pemilik.home'); })->name('home');
        Route::get('/rekap-absen', [AbsensiController::class, 'rekapAbsensi'])->name('rekap_absen');
        Route::get('/laporan', function () { return view('pemilik.laporan'); })->name('laporan');
        
        // Cuti Management untuk Owner
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'indexOwner'])->name('index');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
        });
        Route::get('/home', function () {
            return view('pemilik.home');
        })->name('home');
        Route::get('/laporan', function () {
            return view('pemilik.laporan');
        })->name('laporan');
        Route::get('/rekap-absensi', [AbsensiController::class, 'rekapAbsensi'])->name('rekap_absen'); // MODIFIED
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
            return view('finance.data_orderan');
        })->name('pembayaran');

        Route::get('/laporan-keuangan', function () {
            return view('finance.laporan_keuangan');
        })->name('laporan_keuangan');
        Route::get('/daftar_karyawan', [AdminKaryawanController::class, 'karyawanFinance'])
            ->name('daftar_karyawan');
        // EDIT (UPDATE)
        Route::put('/karyawan/{karyawan}', [AdminKaryawanController::class, 'update'])
            ->name('karyawan.update');

        // HAPUS
        Route::delete('/karyawan/{karyawan}', [AdminKaryawanController::class, 'destroy'])
            ->name('karyawan.destroy');
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

        // Cuti Management untuk Manager Divisi
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'indexManagerDivisi'])->name('index');
            Route::get('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
        });

        // Tugas untuk Manager Divisi
        Route::get('/kelola-tugas', [ManagerDivisiTaskController::class, 'index'])
            ->name('kelola_tugas');

        Route::get('/data_project', [DataProjectController::class, 'managerDivisi'])
            ->name('data_project');
        Route::put(
            '/data_project/{id}',
            [DataProjectController::class, 'update']
        )->name('data_project.update');
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
            Route::get('/daftar_karyawan/{divisi}', [AdminKaryawanController::class, 'karyawanDivisi'])
                ->name('karyawan.divisi');
        });

        Route::get('/pengelola_tugas', function () {
            return view('manager_divisi.pengelola_tugas');
        })->name('pengelola_tugas');
        
        Route::get('/absensi-tim', function () { return view('manager_divisi.absensi_tim'); })->name('absensi_tim'); // MODIFIED
        
        Route::get('/tim-saya', function () {
            $user = Auth::user();
            $tim = \App\Models\User::where('divisi', $user->divisi)->where('role', 'karyawan')->get();
            return view('manager_divisi.tim_saya', compact('tim'));
        })->name('tim_saya');
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
            'general_manager' => redirect()->route('general_manajer.kelola_absensi'), // MODIFIED
            'owner' => redirect()->route('owner.rekap_absen'), // MODIFIED
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
| Resource Routes
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
| Debug Routes untuk Testing
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Routes untuk Finance
|--------------------------------------------------------------------------
*/
Route::get('/data', [LayananController::class, 'financeIndex']);
Route::get('/data_orderan', function () {
    return view('finance/data_orderan');
});
Route::get('/finance', function () {
    return view('finance/beranda');
});
Route::get('/pemasukan', function () {
    return view('finance/pemasukan');
});
Route::get('/pengeluaran', function () {
    return view('finance/pengeluaran');
});
Route::get('/finance/invoice', function () {
    return view('finance/invoice');
});

/*
|--------------------------------------------------------------------------
| Routes untuk Manager Divisi
|--------------------------------------------------------------------------
*/

Route::get('/general_manajer', function () {
    return view('general_manajer/home');
});

Route::get('/kelola_tugas', [TaskController::class, 'index'])->name('tugas.page');
Route::get('/kelola_absen', function () {
    return view('general_manajer/kelola_absen');
});

Route::resource('pengumuman', PengumumanController::class);
// === ROUTE UNTUK CATATAN RAPAT ANDA ===
// Semua route di dalam group ini memerlukan user sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/catatan_rapat', [CatatanRapatController::class, 'index'])->name('catatan_rapat.index');
    Route::post('/catatan_rapat', [CatatanRapatController::class, 'store'])->name('catatan_rapat.store');
    Route::put('/catatan_rapat/{catatanRapat}', [CatatanRapatController::class, 'update'])->name('catatan_rapat.update');
    Route::delete('/catatan_rapat/{catatanRapat}', [CatatanRapatController::class, 'destroy'])->name('catatan_rapat.destroy');
});

// Rekap

Route::middleware(['auth'])->group(function () {
    Route::get('/api/karyawan/meeting-notes', [KaryawanController::class, 'getMeetingNotes']);
    Route::get('/api/karyawan/meeting-notes-dates', [KaryawanController::class, 'getMeetingNotesDates']);
    Route::get('/api/karyawan/announcements', [KaryawanController::class, 'getAnnouncements']);
    Route::get('/api/karyawan/announcements-by-date', [KaryawanController::class, 'getAnnouncementsByDate']);
    Route::get('/api/karyawan/announcements-dates', [KaryawanController::class, 'getAnnouncementsDates']);
    Route::get('/api/karyawan/calendar-dates', [KaryawanController::class, 'getCalendarDates']);
    Route::get('/debug-meeting-notes-penugasan', [KaryawanController::class, 'debugMeetingNotesPenugasan']);
    Route::get('/debug-pengumuman', [KaryawanController::class, 'debugPengumuman']);
    Route::get('/test-api', [KaryawanController::class, 'testApiEndpoints']);
});

/*
|--------------------------------------------------------------------------
| Debug Routes (Hanya aktif di mode debug)
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
        return response()->json([
            'status' => 'success',
            'routes' => $routes,
            'current_user' => [
                'id' => auth::id(),
                'name' => auth::user()->name ?? 'Guest',
                'role' => auth::user()->role ?? 'none'
            ]
        ]);
    });
    
    Route::get('/debug/check-task/{id}', function ($id) {
        $task = \App\Models\Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        return response()->json([
            'task' => $task,
            'assignee' => $task->assignedUser,
            'permissions' => [
                'can_upload' => $task->assigned_to == auth::id(),
                'is_completed' => $task->status == 'selesai',
                'has_submission' => !is_null($task->submission_file)
            ]
        ]);
    });
    
    Route::get('/debug/check-absensi-routes', function () {
        $user = auth::user();
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $attendance = \App\Models\Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();
            
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ],
            'today' => $today,
            'attendance' => $attendance,
            'api_endpoints' => [
                'today_status' => url('/api/karyawan/today-status'),
                'history' => url('/api/karyawan/history'),
                'absen_masuk' => url('/api/karyawan/absen-masuk'),
                'absen_pulang' => url('/api/karyawan/absen-pulang'),
                'submit_izin' => url('/api/karyawan/submit-izin'),
            ]
        ]);
    });


// API untuk data owner
Route::middleware(['auth', 'role:owner'])->prefix('api/owner')->name('api.owner.')->group(function () {
    Route::get('/data', [OwnerController::class, 'getData'])->name('data');
});

// API untuk jumlah layanan
Route::middleware(['auth'])->prefix('api/services')->name('api.services.')->group(function () {
    Route::get('/count', [LayananController::class, 'getCount'])->name('count');
});