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
use App\Http\Controllers\OwnerController;
use Illuminate\Http\Request;
use App\Http\Controllers\FinanceController;

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
Route::get('/users/data', [UserController::class, 'data'])->middleware('auth');

// Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('home');

// API endpoint untuk kontak (public)
Route::get('/api/contact', [SettingController::class, 'getContactData'])->name('api.contact');

// API endpoint untuk tentang (public)
Route::get('/api/about', [SettingController::class, 'getAboutData'])->name('api.about');

// API endpoint untuk artikel (public)
Route::get('/api/articles', [SettingController::class, 'getArticlesData'])->name('api.articles');

// API endpoint portofolio (public)
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

// Orders & invoices routes
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class)->only(['index','show','store','update','destroy']);
    Route::get('invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
});

// Pegawai resource routes
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

        // API ROUTES
        Route::get('/catatan_rapat/data', [CatatanRapatController::class, 'getData'])->name('catatan_rapat.data');
        Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');

        // USER MANAGEMENT
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
        Route::get('/data_user', function () { return redirect()->route('admin.user'); });

        // KARYAWAN MANAGEMENT
        Route::get('/data_karyawan', [AdminKaryawanController::class, 'index'])->name('data_karyawan');
        Route::controller(AdminKaryawanController::class)->group(function () {
            Route::get('/karyawan', 'index')->name('karyawan.index');
            Route::post('/karyawan/store', 'store')->name('karyawan.store');
            Route::post('/karyawan/update/{id}', 'update')->name('karyawan.update');
            Route::delete('/karyawan/delete/{id}', 'destroy')->name('karyawan.delete');
        });

        // ABSENSI
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/keuangan', function () { return view('admin.keuangan'); })->name('keuangan.index');
        Route::get('/data_order', function () { return view('admin.data_order'); })->name('data_order');

        // TASK MANAGEMENT
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('index');
            Route::get('/create', [TaskController::class, 'create'])->name('create');
            Route::post('/', [TaskController::class, 'store'])->name('store');
            Route::get('/{id}', [TaskController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [TaskController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TaskController::class, 'update'])->name('update');
            Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/upload-file', [TaskController::class, 'uploadFileAdmin'])->name('upload.file');
            Route::get('/{id}/files', [TaskController::class, 'getTaskFiles'])->name('files');
            Route::get('/files/{file}/download', [TaskController::class, 'downloadFile'])->name('files.download');
            Route::delete('/files/{file}', [TaskController::class, 'deleteFile'])->name('files.delete');
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
        });

        // LAYANAN
        Route::prefix('layanan')->name('layanan.')->group(function () {
            Route::get('/', [LayananController::class, 'index'])->name('index');
            Route::post('/', [LayananController::class, 'store'])->name('store');
            Route::put('/{id}', [LayananController::class, 'update'])->name('update');
            Route::delete('/{id}', [LayananController::class, 'destroy'])->name('delete');
        });

        // SURAT KERJASAMA
        Route::prefix('surat-kerjasama')->name('surat_kerjasama.')->group(function () {
            Route::get('/', [SuratKerjasamaController::class, 'index'])->name('index');
            Route::get('/create', [SuratKerjasamaController::class, 'create'])->name('create');
            Route::post('/', [SuratKerjasamaController::class, 'store'])->name('store');
            Route::get('/{id}', [SuratKerjasamaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SuratKerjasamaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SuratKerjasamaController::class, 'update'])->name('update');
            Route::delete('/{id}', [SuratKerjasamaController::class, 'destroy'])->name('destroy');
        });

        // DATA PROJECT
        Route::get('/data_project', [DataProjectController::class, 'admin'])->name('data_project');
        Route::post('/project', [DataProjectController::class, 'store'])->name('project.store');
        Route::put('/project/{id}', [DataProjectController::class, 'update'])->name('project.update');
        Route::delete('/project/{id}', [DataProjectController::class, 'destroy'])->name('project.destroy');

        Route::get('/surat_kerjasama', function () { return redirect()->route('admin.surat_kerjasama.index'); });
        Route::get('/template_surat', function () { return view('admin.template_surat'); })->name('template_surat');

        // INVOICE
        Route::prefix('invoice')->name('invoice.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/create', [InvoiceController::class, 'create'])->name('create');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
        });

        // KWITANSI
        Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            Route::get('/', [KwitansiController::class, 'index'])->name('index');
            Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
        });

        // CUTI (ADMIN) - ROUTE YANG BENAR
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index'); // Gunakan index() bukan indexAdmin()
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::post('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
            Route::get('/export', [CutiController::class, 'export'])->name('export');
        });

        Route::get('/settings', function () { return view('admin.settings'); })->name('settings');
        Route::get('/catatan_rapat', function () { return redirect()->route('catatan_rapat.index'); });
        Route::get('/pengumuman', function () { return redirect()->route('pengumuman.index'); });

        // SETTINGS
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::post('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
            Route::post('/account', [SettingController::class, 'updateAccount'])->name('account.update');
            Route::post('/notifications', [SettingController::class, 'updateNotifications'])->name('notifications.update');
            Route::post('/password', [SettingController::class, 'updatePassword'])->name('password.update');
            Route::post('/logout-all', [SettingController::class, 'logoutAll'])->name('logout.all');

            Route::get('/contact', [SettingController::class, 'contact'])->name('contact');
            Route::post('/contact', [SettingController::class, 'updateContact'])->name('contact.update');
            Route::get('/contact-data', [SettingController::class, 'getContactData'])->name('contact.data');

            Route::get('/about', [SettingController::class, 'about'])->name('about');
            Route::post('/about', [SettingController::class, 'updateAbout'])->name('about.update');
            Route::get('/about-data', [SettingController::class, 'getAboutData'])->name('about.data');

            // ARTICLES
            Route::get('/articles', [SettingController::class, 'articles'])->name('articles');
            Route::get('/articles/{id}', [SettingController::class, 'getArticle'])->name('articles.get');
            Route::post('/articles', [SettingController::class, 'storeArticle'])->name('articles.store');
            Route::put('/articles/{id}', [SettingController::class, 'updateArticle'])->name('articles.update');
            Route::delete('/articles/{id}', [SettingController::class, 'deleteArticle'])->name('articles.delete');
            Route::get('/articles-data', [SettingController::class, 'getArticlesData'])->name('articles.data');

            // PORTFOLIOS
            Route::get('/portfolios', [SettingController::class, 'portfolios'])->name('portfolios');
            Route::get('/portfolios/{id}', [SettingController::class, 'getPortfolio'])->name('portfolios.get');
            Route::post('/portfolios', [SettingController::class, 'storePortfolio'])->name('portfolios.store');
            Route::put('/portfolios/{id}', [SettingController::class, 'updatePortfolio'])->name('portfolios.update');
            Route::delete('/portfolios/{id}', [SettingController::class, 'deletePortfolio'])->name('portfolios.delete');
            Route::get('/portfolios-data', [SettingController::class, 'getPortfoliosData'])->name('portfolios.data');
        });

        Route::get('/catatan_rapat', function () { return redirect()->route('catatan_rapat.index'); });
        Route::get('/pengumuman', function () { return redirect()->route('pengumuman.index'); });

        // API DASHBOARD
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

        Route::get('/home', [KaryawanController::class, 'home'])->name('home');

        // CUTI - ROUTE YANG DIREVISI
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index'); // Gunakan index() bukan indexKaryawan()
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            
            // HAPUS ROUTE YANG TIDAK ADA DI CONTROLLER:
            // Route::get('/check-available-days', [CutiController::class, 'checkAvailableDays'])->name('check-available-days');
            
            Route::post('/calculate-duration', [CutiController::class, 'calculateDuration'])->name('calculate-duration');
            Route::get('/create', [CutiController::class, 'create'])->name('create');
            Route::post('/', [CutiController::class, 'store'])->name('store');
            Route::get('/{cuti}', [CutiController::class, 'show'])->name('show');
            Route::get('/{cuti}/edit', [CutiController::class, 'edit'])->name('edit');
            Route::put('/{cuti}', [CutiController::class, 'update'])->name('update');
            Route::delete('/{cuti}', [CutiController::class, 'destroy'])->name('destroy');
            Route::post('/{cuti}/cancel', [CutiController::class, 'cancel'])->name('cancel');
            Route::get('/export', [CutiController::class, 'export'])->name('export');
        });

        // PROFILE
        Route::get('/profile', [KaryawanProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [KaryawanProfileController::class, 'update'])->name('profile.update');

        // ABSENSI
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
        Route::get('/absensi/list', [KaryawanController::class, 'absensiListPage'])->name('absensi.list');
        
        // TUGAS
        Route::get('/tugas', [KaryawanController::class, 'listPage'])->name('tugas');
        Route::prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
            Route::get('/{id}', [TaskController::class, 'karyawanShow'])->name('show');
            Route::put('/{id}/status', [TaskController::class, 'updateStatus'])->name('update.status');
            Route::post('/{id}/upload', [TaskController::class, 'uploadFile'])->name('upload');
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
        });

        // API
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

        // LEGACY
        Route::get('/detail/{id}', [KaryawanController::class, 'detailPage'])->name('detail');
        Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
        Route::get('/pengajuan_cuti', function () { return redirect()->route('karyawan.cuti.index'); });
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

        Route::get('/data_karyawan', [AdminKaryawanController::class, 'karyawanGeneral'])->name('data_karyawan');
        Route::get('/layanan', [LayananController::class, 'Generalindex'])->name('layanan');

        // DATA PROJECT
        Route::get('/data_project', [DataProjectController::class, 'index'])->name('data_project');
        Route::post('/data_project', [DataProjectController::class, 'store'])->name('data_project.store');
        Route::put('/data_project/{id}', [DataProjectController::class, 'update'])->name('data_project.update');
        Route::delete('/data_project/{id}', [DataProjectController::class, 'destroy'])->name('data_project.destroy');

        // TUGAS
        Route::get('/kelola-tugas', [GeneralManagerTaskController::class, 'index'])->name('kelola_tugas');

        // ==================== CUTI MANAGEMENT ====================
        // Gunakan method index() yang universal
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index'); // Gunakan index() bukan indexGeneralManager()
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::post('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
        });

        // ROUTE GROUP TASKS (GM)
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::post('/', [GeneralManagerTaskController::class, 'store'])->name('store');
            Route::get('/{id}', [GeneralManagerTaskController::class, 'show'])->name('show');
            Route::put('/{id}', [GeneralManagerTaskController::class, 'update'])->name('update');
            Route::put('/{id}/status', [GeneralManagerTaskController::class, 'updateStatus'])->name('update.status');
            Route::post('/{id}/assign', [GeneralManagerTaskController::class, 'assignToKaryawan'])->name('assign');
            Route::delete('/{id}', [GeneralManagerTaskController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/delete', [GeneralManagerTaskController::class, 'destroy'])->name('delete');
        });

        // API GM
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/tasks', [GeneralManagerTaskController::class, 'getTasksApi'])->name('tasks');
            Route::get('/tasks/statistics', [GeneralManagerTaskController::class, 'getStatistics'])->name('tasks.statistics');
            Route::get('/karyawan-by-divisi/{divisi}', [GeneralManagerTaskController::class, 'getKaryawanByDivisi'])->name('karyawan.by_divisi');
        });
        
        Route::get('/kelola-absen', [AbsensiController::class, 'kelolaAbsen'])->name('kelola_absen');
        Route::get('/kelola-absensi', [AbsensiController::class, 'kelolaAbsensi'])->name('kelola_absensi');
        Route::get('/tim_dan_divisi', function () { return view('general_manajer/tim_dan_divisi'); });
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
        
        // Cuti - Gunakan method index() universal
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index'); // Gunakan index() bukan indexOwner()
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
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
        Route::get('/pembayaran', function () { return view('finance.data_orderan'); })->name('pembayaran');
        Route::get('/laporan-keuangan', function () { return view('finance.laporan_keuangan'); })->name('laporan_keuangan');
        Route::get('/daftar_karyawan', [AdminKaryawanController::class, 'karyawanFinance'])->name('daftar_karyawan');
        Route::put('/karyawan/{karyawan}', [AdminKaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/karyawan/{karyawan}', [AdminKaryawanController::class, 'destroy'])->name('karyawan.destroy');
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

        // Cuti - Gunakan method index() universal
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index'); // Gunakan index() bukan indexManagerDivisi()
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::post('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
        });

        // Tugas
        Route::get('/kelola-tugas', [ManagerDivisiTaskController::class, 'index'])->name('kelola_tugas');
        Route::get('/data_project', [DataProjectController::class, 'managerDivisi'])->name('data_project');
        Route::put('/data_project/{id}', [DataProjectController::class, 'update'])->name('data_project.update');

        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::post('/', [ManagerDivisiTaskController::class, 'store'])->name('store');
            Route::get('/{id}', [ManagerDivisiTaskController::class, 'show'])->name('show');
            Route::put('/{id}', [ManagerDivisiTaskController::class, 'update'])->name('update');
            Route::put('/{id}/status', [ManagerDivisiTaskController::class, 'updateStatus'])->name('update.status');
            Route::post('/{id}/assign', [ManagerDivisiTaskController::class, 'assignToKaryawan'])->name('assign');
            Route::delete('/{id}', [ManagerDivisiTaskController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/delete', [ManagerDivisiTaskController::class, 'destroy'])->name('delete');
        });

        // API MD
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/tasks', [ManagerDivisiTaskController::class, 'getTasksApi'])->name('tasks');
            Route::get('/tasks/statistics', [ManagerDivisiTaskController::class, 'getStatistics'])->name('tasks.statistics');
            Route::get('/karyawan-by-divisi/{divisi}', [ManagerDivisiTaskController::class, 'getKaryawanByDivisi'])->name('karyawan.by_divisi');
            Route::get('/daftar_karyawan/{divisi}', [AdminKaryawanController::class, 'karyawanDivisi'])->name('karyawan.divisi');
        });

        Route::get('/pengelola_tugas', function () { return view('manager_divisi.pengelola_tugas'); })->name('pengelola_tugas');
        Route::get('/absensi-tim', function () { return view('manager_divisi.absensi_tim'); })->name('absensi_tim');
        
        Route::get('/tim-saya', function () {
            $user = Auth::user();
            $tim = \App\Models\User::where('divisi', $user->divisi)->where('role', 'karyawan')->get();
            return view('manager_divisi.tim_saya', compact('tim'));
        })->name('tim_saya');
    });

/*
|--------------------------------------------------------------------------
| Global Routes (Pengumuman & Catatan Rapat)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->resource('pengumuman', PengumumanController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/catatan-rapat', [CatatanRapatController::class, 'index'])->name('catatan_rapat.index');
    Route::post('/catatan-rapat', [CatatanRapatController::class, 'store'])->name('catatan_rapat.store');
    Route::put('/catatan-rapat/{id}', [CatatanRapatController::class, 'update'])->name('catatan_rapat.update');
    Route::delete('/catatan-rapat/{id}', [CatatanRapatController::class, 'destroy'])->name('catatan_rapat.destroy');
    Route::get('/catatan-rapat/{id}', [CatatanRapatController::class, 'show'])->name('catatan_rapat.show');
    Route::get('/catatan-rapat/data', [CatatanRapatController::class, 'getData'])->name('catatan_rapat.data');
    Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
});

/*
|--------------------------------------------------------------------------
| TEST & DEBUG ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function() {
    // Debug Route untuk Cuti
    Route::get('/debug/cuti-fix', function() {
        try {
            $controller = new \App\Http\Controllers\CutiController();
            $request = new \Illuminate\Http\Request();
            
            // Test getData method
            $response = $controller->getData($request);
            $data = json_decode($response->getContent(), true);
            
            echo "<h1>Debug Cuti Controller</h1>";
            echo "Status: " . ($data['success'] ? 'SUCCESS' : 'FAILED') . "<br>";
            echo "Message: " . ($data['message'] ?? 'No message') . "<br>";
            
            if ($data['success'] && isset($data['data'])) {
                echo "Jumlah Data: " . count($data['data']) . "<br>";
                if (count($data['data']) > 0) {
                    $first = $data['data'][0];
                    echo "<h3>Data Pertama:</h3>";
                    echo "ID: " . $first['id'] . "<br>";
                    echo "Nama: " . ($first['nama'] ?? 'NULL') . "<br>";
                    echo "Divisi: " . ($first['divisi'] ?? 'NULL') . "<br>";
                    echo "Status: " . ($first['status'] ?? 'NULL') . "<br>";
                }
            }
            
            echo "<hr>";
            
            // Test stats method
            $statsResponse = $controller->stats();
            $statsData = json_decode($statsResponse->getContent(), true);
            
            echo "<h2>Stats Test:</h2>";
            echo "Status: " . ($statsData['success'] ? 'SUCCESS' : 'FAILED') . "<br>";
            if ($statsData['success']) {
                echo "<pre>" . print_r($statsData['data'], true) . "</pre>";
            }
            
        } catch (\Exception $e) {
            echo "<h1>ERROR: " . $e->getMessage() . "</h1>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    });
    
    // Test route untuk memastikan routing bekerja
    Route::get('/test/cuti-routes', function() {
        $user = auth()->user();
        
        return response()->json([
            'user_role' => $user->role,
            'routes' => [
                'karyawan_cuti_index' => route('karyawan.cuti.index'),
                'karyawan_cuti_data' => route('karyawan.cuti.data'),
                'general_manager_cuti_index' => route('general_manajer.cuti.index'),
                'general_manager_cuti_data' => route('general_manajer.cuti.data'),
            ],
            'current_url' => url()->current()
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| GLOBAL API ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('api')->group(function () {
    // Karyawan API
    Route::prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/dashboard-data', [AbsensiController::class, 'apiTodayStatus'])->name('dashboard-data');
        Route::get('/today-status', [AbsensiController::class, 'apiTodayStatus'])->name('today-status');
        Route::get('/history', [AbsensiController::class, 'apiHistory'])->name('history');
        Route::post('/absen-masuk', [AbsensiController::class, 'apiAbsenMasuk'])->name('absen-masuk');
        Route::post('/absen-pulang', [AbsensiController::class, 'apiAbsenPulang'])->name('absen-pulang');
        Route::post('/submit-izin', [AbsensiController::class, 'apiSubmitIzin'])->name('submit-izin');
    });

    // Tasks API
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/{id}', [TaskController::class, 'show'])->name('show');
        Route::get('/{id}/detail', [TaskController::class, 'getTaskDetailApi'])->name('detail');
        Route::post('/{id}/upload-file', [TaskController::class, 'uploadTaskFile'])->name('upload.file');
        Route::post('/{id}/complete', [TaskController::class, 'markAsComplete'])->name('complete');
        Route::post('/{id}/status', [TaskController::class, 'updateTaskStatus'])->name('status');
        Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
        Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
        Route::get('/{id}/files', [TaskController::class, 'getTaskFiles'])->name('files');
        Route::get('/files/{file}/download', [TaskController::class, 'downloadFile'])->name('files.download');
        Route::delete('/files/{file}', [TaskController::class, 'deleteFile'])->name('files.delete');
        Route::get('/{id}/download', [TaskController::class, 'downloadSubmission'])->name('download.submission');
        Route::get('/statistics', [TaskController::class, 'getStatistics'])->name('statistics');
    });

    // Admin/GM API
    Route::prefix('admin')->middleware(['role:admin,general_manager'])->name('admin.')->group(function () {
        Route::get('/absensi', [AbsensiController::class, 'apiIndex'])->name('absensi');
        Route::get('/absensi/ketidakhadiran', [AbsensiController::class, 'apiIndexKetidakhadiran'])->name('ketidakhadiran');
        Route::get('/absensi/stats', [AbsensiController::class, 'apiStatistics'])->name('stats');
        Route::get('/kehadiran-per-divisi', [AbsensiController::class, 'apiKehadiranPerDivisi'])->name('kehadiran.divisi');
        Route::post('/absensi', [AbsensiController::class, 'apiStore'])->name('absensi.store');
        Route::get('/absensi/{id}', [AbsensiController::class, 'apiShow'])->name('absensi.show');
        Route::put('/absensi/{id}', [AbsensiController::class, 'apiUpdate'])->name('absensi.update');
        Route::delete('/absensi/{id}', [AbsensiController::class, 'apiDestroy'])->name('absensi.destroy');
        Route::post('/absensi/cuti', [AbsensiController::class, 'apiStoreCuti'])->name('absensi.cuti.store');
        Route::put('/absensi/cuti/{id}', [AbsensiController::class, 'apiUpdateCuti'])->name('absensi.cuti.update');
        Route::post('/absensi/{id}/verify', [AbsensiController::class, 'apiVerify'])->name('absensi.verify');
    });
    
    // Owner API
    Route::prefix('owner')->middleware(['role:owner'])->name('owner.')->group(function () {
        Route::get('/data', [OwnerController::class, 'getData'])->name('data');
    });
});

/*
|--------------------------------------------------------------------------
| Shortcuts & Redirects
|--------------------------------------------------------------------------
*/

Route::get('/redirect-login', function () {
    if (Auth::check()) { return redirectToRolePage(Auth::user()); }
    return redirect('/login');
})->name('redirect.login');

Route::get('/tugas', function () {
    if (!Auth::check()) return redirect('/login');
    return match (Auth::user()->role) {
        'karyawan' => redirect()->route('karyawan.tugas'),
        'admin' => redirect()->route('admin.tasks.index'),
        'general_manager' => redirect()->route('general_manajer.kelola_tugas'),
        'manager_divisi' => redirect()->route('manager_divisi.kelola_tugas'),
        default => redirect('/login')
    };
})->name('tugas.redirect');

Route::get('/absensi', function () {
    if (!Auth::check()) return redirect('/login');
    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.absensi.index'),
        'general_manager' => redirect()->route('general_manajer.kelola_absen'),
        'owner' => redirect()->route('owner.rekap_absen'),
        default => redirect()->route('karyawan.absensi.page')
    };
})->name('absensi.redirect');

Route::get('/cuti', function () {
    if (!Auth::check()) return redirect('/login');
    return match (Auth::user()->role) {
        'karyawan' => redirect()->route('karyawan.cuti.index'),
        'admin' => redirect()->route('admin.cuti.index'),
        'general_manager' => redirect()->route('general_manajer.cuti.index'),
        'manager_divisi' => redirect()->route('manager_divisi.cuti.index'),
        'owner' => redirect()->route('owner.cuti.index'),
        default => redirect('/login')
    };
})->name('cuti.redirect');

/*
|--------------------------------------------------------------------------
| Public Routes & Resources
|--------------------------------------------------------------------------
*/
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.public');
Route::post('/layanan', [LayananController::class, 'store'])->middleware('auth');
Route::put('/layanan/{id}', [LayananController::class, 'update'])->middleware('auth');
Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->middleware('auth');

Route::resource('invoices', InvoiceController::class);
Route::get('/invoices/{invoice}/print', function (\App\Models\Invoice $invoice) {
    return view('invoices.print', compact('invoice'));
})->name('invoices.print');

// Finance Routes
Route::get('/data', [LayananController::class, 'financeIndex']);
Route::get('/data_orderan', function () { return view('finance/data_orderan'); });
Route::get('/finance', function () { return view('finance/beranda'); });
Route::get('/pemasukan', [FinanceController::class, 'index'])->name('finance.pemasukan');
Route::get('/pengeluaran', function () { return view('finance/pengeluaran'); });
Route::get('/finance/invoice', function () { return view('finance/invoice'); });

// Route Menyimpan Data (POST)
// URL bisa sama, bedanya methodnya (POST)
Route::post('/pemasukan', [FinanceController::class, 'store']);

// General Manager Shortcuts
Route::get('/general_manajer', function () { return view('general_manajer/home'); });
Route::get('/kelola_tugas', [TaskController::class, 'index'])->name('tugas.page');
Route::get('/kelola_absen', function () { return view('general_manajer/kelola_absen'); });

// Admin Template
Route::get('/admin/templat', function () { return view('admin.templet_surat'); });

// Redirect untuk legacy route acc_cuti
Route::middleware(['auth', 'role:general_manager'])
    ->get('/general_manajer/acc_cuti', function () {
        return redirect()->route('general_manajer.cuti.index');
    })->name('general_manajer.acc_cuti');

/*
|--------------------------------------------------------------------------
| MAIN FALLBACK ROUTE (FIXED)
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

