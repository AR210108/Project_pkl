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
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\TimDivisiController;
use App\Http\Controllers\OwnerController;
use Illuminate\Http\Request;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\ProfileController;

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
| Public Routes (Tidak Perlu Login)
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('home');

// Public API endpoints
Route::prefix('api')->name('api.public.')->group(function () {
    Route::get('/contact', [SettingController::class, 'getContactData'])->name('contact');
    Route::get('/about', [SettingController::class, 'getAboutData'])->name('about');
    Route::get('/articles', [SettingController::class, 'getArticlesData'])->name('articles');
    Route::get('/portfolios', [SettingController::class, 'getPortfoliosData'])->name('portfolios');
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan');
});

// Auth routes
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

// API Test routes (public)
Route::prefix('api')->group(function() {
    Route::get('/test', function() {
        return response()->json([
            'success' => true,
            'message' => 'API Works!',
            'timestamp' => now()->toDateTimeString()
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
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

    // Profile Redirect
    Route::get('/profile', function () {
        $user = Auth::user();
        return match ($user->role) {
            'admin' => redirect()->route('admin.settings.index'),
            'karyawan' => redirect()->route('karyawan.profile'),
            'manager_divisi' => redirect()->route('manager_divisi.home'),
            'general_manager' => redirect()->route('general_manajer.home'),
            'owner' => redirect()->route('owner.home'),
            'finance' => redirect()->route('finance.beranda'),
            default => abort(404)
        };
    })->name('profile');

    // Orders & Invoices
    Route::resource('orders', OrderController::class)->only(['index','show','store','update','destroy']);
    Route::get('invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    
    // Pegawai Management
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [KaryawanController::class, 'indexPegawai'])->name('index');
        Route::post('/', [KaryawanController::class, 'storePegawai'])->name('store');
        Route::get('/{id}/edit', [KaryawanController::class, 'editPegawai'])->name('edit');
        Route::put('/{id}', [KaryawanController::class, 'updatePegawai'])->name('update');
        Route::delete('/{id}', [KaryawanController::class, 'destroyPegawai'])->name('destroy');
    });

    // Pengumuman & Catatan Rapat (Global)
    Route::resource('pengumuman', PengumumanController::class);
    
    Route::prefix('catatan-rapat')->name('catatan_rapat.')->group(function () {
        Route::get('/', [CatatanRapatController::class, 'index'])->name('index');
        Route::post('/', [CatatanRapatController::class, 'store'])->name('store');
        Route::put('/{id}', [CatatanRapatController::class, 'update'])->name('update');
        Route::delete('/{id}', [CatatanRapatController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [CatatanRapatController::class, 'show'])->name('show');
        Route::get('/data', [CatatanRapatController::class, 'getData'])->name('data');
    });

    // =========== GLOBAL API ROUTES ===========
    Route::prefix('api')->group(function () {
        // Users
        Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
        
        // Cuti API
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/check-leave-status', [CutiController::class, 'checkLeaveStatusApi'])->name('check-leave-status');
            Route::get('/{id}/history', [CutiController::class, 'getHistory'])->name('history');
            Route::get('/summary', [CutiController::class, 'getSummary'])->name('summary');
            Route::post('/calculate-duration', [CutiController::class, 'calculateDuration'])->name('calculate-duration');
        });

        // Tasks API - Global
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/{id}', [TaskController::class, 'show'])->name('show');
            Route::get('/{id}/detail', [TaskController::class, 'getTaskDetailApi'])->name('detail');
            Route::post('/{id}/upload-file', [TaskController::class, 'uploadTaskFile'])->name('upload.file');
            Route::post('/{id}/complete', [TaskController::class, 'markAsComplete'])->name('complete');
            Route::post('/{id}/status', [TaskController::class, 'updateTaskStatus'])->name('status');
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
            Route::get('/{id}/files', [TaskController::class, 'getTaskFiles'])->name('files');
            Route::get('/files/{file}/download', [TaskController::class, 'downloadFileById'])->name('files.download');
            Route::delete('/files/{file}', [TaskController::class, 'deleteFile'])->name('files.delete');
            Route::get('/{id}/download', [TaskController::class, 'downloadSubmission'])->name('download.submission');
            Route::get('/statistics', [TaskController::class, 'getStatistics'])->name('statistics');
            Route::get('/karyawan/statistics', [TaskController::class, 'getKaryawanStatistics'])->name('karyawan.statistics');
        });

        // INVOICE API ROUTES
        Route::prefix('invoices')->name('api.invoices.')->group(function() {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/mark-printed', [InvoiceController::class, 'markPrinted'])->name('mark.printed');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Role: ADMIN Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/beranda', [AdminController::class, 'beranda'])->name('beranda');
        Route::get('/home', function () {
            return redirect()->route('admin.beranda');
        });

        // Profile
        Route::get('/profile', function () {
            return view('admin.profile');
        })->name('profile');

        // API untuk data
        Route::get('/catatan_rapat/data', [CatatanRapatController::class, 'getData'])->name('catatan_rapat.data');
        Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');

        // USER MANAGEMENT
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

        // KARYAWAN MANAGEMENT
        Route::get('/data_karyawan', [AdminKaryawanController::class, 'index'])->name('admin.karyawan');
        Route::post('/karyawan/store', [AdminKaryawanController::class, 'store'])->name('admin.karyawan.store');
        Route::put('/karyawan/update/{id}', [AdminKaryawanController::class, 'update'])->name('admin.karyawan.update');
        Route::delete('/karyawan/delete/{id}', [AdminKaryawanController::class, 'destroy'])->name('admin.karyawan.delete');

        // ABSENSI MANAGEMENT
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

        // KEUANGAN
        Route::get('/keuangan', function () {
            return view('admin.keuangan');
        })->name('keuangan.index');

        Route::get('/data_order', function () {
            return view('admin.data_order');
        })->name('data_order');

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
            Route::get('/files/{file}/download', [TaskController::class, 'downloadFileById'])->name('files.download');
            Route::delete('/files/{file}', [TaskController::class, 'deleteFile'])->name('files.delete');

            // Comments
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');

            // Status updates
            Route::post('/{id}/status', [TaskController::class, 'updateTaskStatus'])->name('update.status');
            Route::post('/{id}/complete', [TaskController::class, 'markAsComplete'])->name('complete');
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

        // Data Project
        Route::get('/data_project', [DataProjectController::class, 'admin'])->name('data_project');
        Route::post('/project', [DataProjectController::class, 'store'])->name('project.store');
        Route::put('/project/{id}', [DataProjectController::class, 'update'])->name('project.update');
        Route::delete('/project/{id}', [DataProjectController::class, 'destroy'])->name('project.destroy');

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
            Route::get('/{id}/print', [InvoiceController::class, 'print'])->name('print');
        });

        // Kwitansi
        Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            Route::get('/', [KwitansiController::class, 'index'])->name('index');
            Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
        });

        // CUTI MANAGEMENT
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index');
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::get('/quota-info', [CutiController::class, 'getQuotaInfo'])->name('quota.info');
            Route::post('/reset-quota', [CutiController::class, 'resetQuota'])->name('reset.quota');
            Route::get('/create', [CutiController::class, 'create'])->name('create');
            Route::post('/', [CutiController::class, 'store'])->name('store');
            Route::post('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
            Route::post('/{cuti}/cancel-refund', [CutiController::class, 'cancelWithRefund'])->name('cancel.refund');
            Route::get('/{cuti}', [CutiController::class, 'show'])->name('show');
            Route::get('/{cuti}/edit', [CutiController::class, 'edit'])->name('edit');
            Route::put('/{cuti}', [CutiController::class, 'update'])->name('update');
            Route::delete('/{cuti}', [CutiController::class, 'destroy'])->name('destroy');
            Route::get('/{cuti}/history', [CutiController::class, 'getHistory'])->name('history');
            Route::get('/summary', [CutiController::class, 'getSummary'])->name('summary');
            Route::get('/export', [CutiController::class, 'export'])->name('export');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
            Route::post('/calculate-duration', [CutiController::class, 'calculateDuration'])->name('calculate-duration');
            Route::get('/karyawan-by-divisi', [CutiController::class, 'getKaryawanByDivisi'])->name('karyawan.by-divisi');
            Route::get('/check-leave-status', [CutiController::class, 'checkLeaveStatusApi'])->name('check-leave-status');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::post('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
            Route::post('/account', [SettingController::class, 'updateAccount'])->name('account.update');
            Route::post('/notifications', [SettingController::class, 'updateNotifications'])->name('notifications.update');
            Route::post('/password', [SettingController::class, 'updatePassword'])->name('password.update');
            Route::post('/logout-all', [SettingController::class, 'logoutAll'])->name('logout.all');
            
            Route::get('/contact', [SettingController::class, 'contact'])->name('contact');
            Route::post('/contact', [SettingController::class, 'updateContact'])->name('contact.update');
            
            Route::get('/about', [SettingController::class, 'about'])->name('about');
            Route::post('/about', [SettingController::class, 'updateAbout'])->name('about.update');
            
            Route::get('/articles', [SettingController::class, 'articles'])->name('articles');
            Route::get('/articles/{id}', [SettingController::class, 'getArticle'])->name('articles.get');
            Route::post('/articles', [SettingController::class, 'storeArticle'])->name('articles.store');
            Route::put('/articles/{id}', [SettingController::class, 'updateArticle'])->name('articles.update');
            Route::delete('/articles/{id}', [SettingController::class, 'deleteArticle'])->name('articles.delete');
            
            // Portfolios
            Route::get('/portfolios', [SettingController::class, 'portfolios'])->name('portfolios');
            Route::get('/portfolios/{id}', [SettingController::class, 'getPortfolio'])->name('portfolios.get');
            Route::post('/portfolios', [SettingController::class, 'storePortfolio'])->name('portfolios.store');
            Route::put('/portfolios/{id}', [SettingController::class, 'updatePortfolio'])->name('portfolios.update');
            Route::delete('/portfolios/{id}', [SettingController::class, 'deletePortfolio'])->name('portfolios.delete');
        });

        /* API UNTUK ADMIN/GENERAL MANAGER */
        Route::prefix('api')->name('api.')->group(function () {
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
    });

/*
|--------------------------------------------------------------------------
| Role: KARYAWAN Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        // Dashboard
        Route::get('/home', [KaryawanController::class, 'home'])->name('home');

        // Profile
        Route::get('/profile', [KaryawanProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [KaryawanProfileController::class, 'update'])->name('profile.update');

        // CUTI
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index');
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::get('/quota-info', [CutiController::class, 'getQuotaInfo'])->name('quota.info');
            Route::get('/create', [CutiController::class, 'create'])->name('create');
            Route::post('/calculate-duration', [CutiController::class, 'calculateDuration'])->name('calculate-duration');
            Route::post('/', [CutiController::class, 'store'])->name('store');
            Route::get('/{cuti}', [CutiController::class, 'show'])->name('show');
            Route::get('/{cuti}/edit', [CutiController::class, 'edit'])->name('edit');
            Route::put('/{cuti}', [CutiController::class, 'update'])->name('update');
            Route::delete('/{cuti}', [CutiController::class, 'destroy'])->name('destroy');
            Route::post('/{cuti}/cancel', [CutiController::class, 'cancel'])->name('cancel');
            Route::post('/{cuti}/cancel-refund', [CutiController::class, 'cancelWithRefund'])->name('cancel.refund');
            Route::get('/summary', [CutiController::class, 'getSummary'])->name('summary');
            Route::get('/export', [CutiController::class, 'export'])->name('export');
            Route::get('/{cuti}/history', [CutiController::class, 'getHistory'])->name('history');
        });

        // ABSENSI
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
        Route::get('/absensi/list', [KaryawanController::class, 'absensiListPage'])->name('absensi.list');
        Route::get('/detail/{id}', [KaryawanController::class, 'detailPage'])->name('detail');

        // TUGAS
        Route::prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/', [TaskController::class, 'karyawanTasks'])->name('index');
            Route::get('/list', [TaskController::class, 'karyawanTasks'])->name('list');
            Route::get('/{id}', [TaskController::class, 'karyawanShow'])->name('show');
            Route::post('/{id}/upload-file', [TaskController::class, 'uploadTaskFile'])->name('upload.file');
            Route::post('/{id}/complete', [TaskController::class, 'markAsComplete'])->name('complete');
            Route::post('/{id}/status', [TaskController::class, 'updateTaskStatus'])->name('update.status');
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
            Route::get('/{id}/files', [TaskController::class, 'getTaskFiles'])->name('files');
            Route::get('/files/{file}/download', [TaskController::class, 'downloadFileById'])->name('files.download');
        });

        // LIST PAGE
        Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');

        // =========== API KHUSUS KARYAWAN ===========
        Route::prefix('api')->name('api.')->group(function () {
            // DASHBOARD DATA
            Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardDataApi'])->name('dashboard.data');
            
            // MEETING NOTES
            Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingDatesApi'])->name('meeting.notes.dates');
            Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesApi'])->name('meeting.notes.get');
            
            // ANNOUNCEMENTS
            Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesApi'])->name('announcements.dates');
            Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsApi'])->name('announcements.get');
            
            // =========== ABSENSI API ROUTES YANG DIPERBAIKI ===========
            // Status Hari Ini (API baru)
            Route::get('/today-status', [KaryawanController::class, 'getTodayStatusApi'])->name('today.status');
            
            // Riwayat Absensi (API baru dengan filter)
            Route::get('/history', [KaryawanController::class, 'getHistoryApi'])->name('history');
            
            // API Absensi Actions
            Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
            Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');
            Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('submit.izin');
            Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('submit.dinas');
            
            // Status Pengajuan
            Route::get('/pengajuan-status', [KaryawanController::class, 'getPengajuanStatus'])->name('pengajuan-status');
            
            // Task statistics
            Route::get('/tasks/statistics', [TaskController::class, 'getKaryawanStatistics'])->name('tasks.statistics');
            Route::get('/tasks', [TaskController::class, 'getKaryawanTasks'])->name('tasks');
            
            // API lama untuk kompatibilitas (akan dihapus bertahap)
            Route::get('/dashboard-data-old', [KaryawanController::class, 'getDashboardData'])->name('dashboard.data.old');
            Route::get('/today-status-old', [KaryawanController::class, 'getTodayStatus'])->name('today.status.old');
            Route::get('/history-old', [KaryawanController::class, 'getHistory'])->name('history.old');
        });
    });

/*
|--------------------------------------------------------------------------
| Role: GENERAL MANAGER Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:general_manager'])
    ->prefix('general_manajer')
    ->name('general_manajer.')
    ->group(function () {
        // Dashboard
        Route::get('/home', function () { return view('general_manajer.home'); })->name('home');

        // Profile
        Route::get('/profile', function () {
            return view('general_manajer.profile');
        })->name('profile');

        Route::get('/data_karyawan', [AdminKaryawanController::class, 'karyawanGeneral'])->name('data_karyawan');
        Route::get('/layanan', [LayananController::class, 'Generalindex'])->name('layanan');

        // DATA PROJECT
        Route::get('/data_project', [DataProjectController::class, 'index'])->name('data_project');
        Route::post('/data_project', [DataProjectController::class, 'store'])->name('data_project.store');
        Route::put('/data_project/{id}', [DataProjectController::class, 'updategeneral'])->name('data_project.update');
        Route::delete('/data_project/{id}', [DataProjectController::class, 'destroy'])->name('data_project.destroy');

        // CUTI MANAGEMENT
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index');
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::post('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
            Route::post('/{cuti}/cancel-refund', [CutiController::class, 'cancelWithRefund'])->name('cancel.refund');
            Route::get('/quota-info', [CutiController::class, 'getQuotaInfo'])->name('quota.info');
            Route::post('/reset-quota', [CutiController::class, 'resetQuota'])->name('reset.quota');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
            Route::get('/{cuti}', [CutiController::class, 'show'])->name('show');
            Route::get('/{cuti}/edit', [CutiController::class, 'edit'])->name('edit');
            Route::put('/{cuti}', [CutiController::class, 'update'])->name('update');
            Route::get('/summary', [CutiController::class, 'getSummary'])->name('summary');
            Route::get('/export', [CutiController::class, 'export'])->name('export');
            Route::get('/karyawan-by-divisi', [CutiController::class, 'getKaryawanByDivisi'])->name('karyawan.by-divisi');
        });

        // TUGAS MANAGEMENT
        Route::get('/kelola-tugas', [GeneralManagerTaskController::class, 'index'])->name('kelola_tugas');

        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::post('/', [GeneralManagerTaskController::class, 'store'])->name('store');
            Route::get('/{id}', [GeneralManagerTaskController::class, 'show'])->name('show');
            Route::put('/{id}', [GeneralManagerTaskController::class, 'update'])->name('update');
            Route::put('/{id}/status', [GeneralManagerTaskController::class, 'updateStatus'])->name('update.status');
            Route::post('/{id}/assign', [GeneralManagerTaskController::class, 'assignToKaryawan'])->name('assign');
            Route::delete('/{id}', [GeneralManagerTaskController::class, 'destroy'])->name('destroy');
        });

        // API Routes untuk General Manager
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/tasks', [GeneralManagerTaskController::class, 'getTasksApi'])
                ->name('tasks');
            Route::get('/tasks/statistics', [GeneralManagerTaskController::class, 'getStatistics'])
                ->name('tasks.statistics');
            Route::get('/karyawan-by-divisi/{parameter}', [GeneralManagerTaskController::class, 'getKaryawanByDivisi'])
                ->name('karyawan.by_divisi');
        });
        
        // Absensi
        Route::get('/kelola-absen', [AbsensiController::class, 'kelolaAbsen'])->name('kelola_absen');
        Route::get('/kelola_absensi', [AbsensiController::class, 'kelolaAbsensi'])->name('kelola_absensi');
        
        // Tim dan Divisi
        Route::get('/tim_dan_divisi', function () {
            return view('general_manajer.tim_dan_divisi');
        })->name('tim_dan_divisi');
        
        // Halaman utama
        Route::get('/tim_divisi', [TimDivisiController::class, 'index'])->name('tim_divisi');

        // Tim routes
        Route::prefix('tim')->group(function () {
            Route::post('/', [TimDivisiController::class, 'storeTim'])->name('tim.store');
            Route::put('/{id}', [TimDivisiController::class, 'updateTim'])->name('tim.update');
            Route::delete('/{id}', [TimDivisiController::class, 'destroyTim'])->name('tim.destroy');
            Route::get('/search', [TimDivisiController::class, 'searchTim'])->name('tim.search');
        });
        
        // Divisi routes
        Route::prefix('divisi')->group(function () {
            Route::post('/', [TimDivisiController::class, 'storeDivisi'])->name('divisi.store');
            Route::put('/{id}', [TimDivisiController::class, 'updateDivisi'])->name('divisi.update');
            Route::delete('/{id}', [TimDivisiController::class, 'destroyDivisi'])->name('divisi.destroy');
            Route::get('/search', [TimDivisiController::class, 'searchDivisi'])->name('divisi.search');
        });
        
        // Utility route
        Route::get('/divisis/list', [TimDivisiController::class, 'getDivisis'])->name('divisis.list');
    });

/*
|--------------------------------------------------------------------------
| Role: OWNER Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/home', function () {
            return view('pemilik.home');
        })->name('home');
        
        // Profile
        Route::get('/profile', function () {
            return view('pemilik.profile');
        })->name('profile');
        
        Route::get('/rekap_absen', [AbsensiController::class, 'rekapAbsensi'])->name('rekap_absen');
        Route::get('/laporan', function () {
            return view('pemilik.laporan');
        })->name('laporan');

        // CUTI MANAGEMENT
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index');
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::get('/quota-info', [CutiController::class, 'getQuotaInfo'])->name('quota.info');
            Route::post('/reset-quota', [CutiController::class, 'resetQuota'])->name('reset.quota');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
            Route::get('/{cuti}', [CutiController::class, 'show'])->name('show');
            Route::get('/{cuti}/edit', [CutiController::class, 'edit'])->name('edit');
            Route::put('/{cuti}', [CutiController::class, 'update'])->name('update');
            Route::get('/summary', [CutiController::class, 'getSummary'])->name('summary');
            Route::get('/export', [CutiController::class, 'export'])->name('export');
        });

        /* API UNTUK OWNER */
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/data', [OwnerController::class, 'getData'])->name('data');
        });
    });

/*
|--------------------------------------------------------------------------
| Role: FINANCE Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:finance'])
    ->prefix('finance')
    ->name('finance.')
    ->group(function () {
        Route::get('/beranda', function () {
            return view('finance.beranda');
        })->name('beranda');
        
        // Profile
        Route::get('/profile', function () {
            return view('finance.profile');
        })->name('profile');
        
        Route::get('/data-layanan', function () {
            return view('finance.data_layanan');
        })->name('data_layanan');
        Route::get('/pembayaran', function () {
            return view('finance.data_orderan');
        })->name('pembayaran');
        Route::get('/laporan-keuangan', function () {
            return view('finance.laporan_keuangan');
        })->name('laporan_keuangan');
        Route::get('/daftar_karyawan', [AdminKaryawanController::class, 'karyawanFinance'])->name('daftar_karyawan');

        // EDIT & DELETE KARYAWAN
        Route::put('/karyawan/{karyawan}', [AdminKaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/karyawan/{karyawan}', [AdminKaryawanController::class, 'destroy'])->name('karyawan.destroy');
        
        // CUTI VIEW ONLY
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index');
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::get('/quota-info', [CutiController::class, 'getQuotaInfo'])->name('quota.info');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
            Route::get('/{cuti}', [CutiController::class, 'show'])->name('show');
        });

        // INVOICE MANAGEMENT - FINANCE
        Route::prefix('invoice')->name('invoice.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/create', [InvoiceController::class, 'create'])->name('create');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/print', [InvoiceController::class, 'print'])->name('print');
        });
    });

/*
|--------------------------------------------------------------------------
| Role: MANAGER DIVISI Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:manager_divisi'])
    ->prefix('manager_divisi')
    ->name('manager_divisi.')
    ->group(function () {
        // Dashboard
        Route::get('/home', function () { 
            return view('manager_divisi.home'); 
        })->name('home');

        // Profile
        Route::get('/profile', function () {
            return view('manager_divisi.profile');
        })->name('profile');

        // =========== ROUTES UNTUK MANAGER DIVISI TASK CONTROLLER ===========
        
        // Halaman Kelola Tugas
        Route::get('/kelola-tugas', [ManagerDivisiTaskController::class, 'index'])
            ->name('kelola-tugas');
        
        // CRUD Tugas
        Route::post('/tasks', [ManagerDivisiTaskController::class, 'store'])
            ->name('tasks.store');
        
        Route::put('/tasks/status/{id}', [ManagerDivisiTaskController::class, 'updateStatus'])
            ->name('tasks.update-status');
        
        Route::post('/tasks/assign/{id}', [ManagerDivisiTaskController::class, 'assignToKaryawan'])
            ->name('tasks.assign');
        
        // Data Karyawan
        Route::get('/data-karyawan', [ManagerDivisiTaskController::class, 'dataKaryawan'])
            ->name('data-karyawan');
        
        Route::get('/tambah-karyawan', [ManagerDivisiTaskController::class, 'createKaryawan'])
            ->name('tambah-karyawan');
        
        Route::post('/store-karyawan', [ManagerDivisiTaskController::class, 'storeKaryawan'])
            ->name('store-karyawan');
        
        Route::get('/edit-karyawan/{id}', [ManagerDivisiTaskController::class, 'editKaryawan'])
            ->name('edit-karyawan');
        
        Route::put('/update-karyawan/{id}', [ManagerDivisiTaskController::class, 'updateKaryawan'])
            ->name('update-karyawan');
        
        Route::delete('/delete-karyawan/{id}', [ManagerDivisiTaskController::class, 'destroyKaryawan'])
            ->name('delete-karyawan');
        
        // API Routes untuk Manager Divisi Task Controller
        Route::prefix('api')->name('api.')->group(function () {
            // Karyawan dropdown
            Route::get('/karyawan-dropdown', [ManagerDivisiTaskController::class, 'getKaryawanDropdown'])
                ->name('karyawan.dropdown');
            
            Route::get('/karyawan-divisi', [ManagerDivisiTaskController::class, 'getKaryawanDivisiApi'])
                ->name('karyawan.divisi');
            
            Route::get('/karyawan-by-divisi/{parameter}', [ManagerDivisiTaskController::class, 'getKaryawanByDivisi'])
                ->name('karyawan.by_divisi');
            
            // Tasks API
            Route::get('/tasks', [ManagerDivisiTaskController::class, 'getTasksApi'])
                ->name('tasks');
            
            Route::get('/tasks/statistics', [ManagerDivisiTaskController::class, 'getStatistics'])
                ->name('tasks.statistics');
            
            Route::get('/tasks/{id}', [ManagerDivisiTaskController::class, 'show'])
                ->name('tasks.show');
            
            Route::get('/tasks-from-gm', [ManagerDivisiTaskController::class, 'getTasksFromGeneralManager'])
                ->name('tasks.from-gm');
            
            Route::post('/create-subtask/{parentTaskId}', [ManagerDivisiTaskController::class, 'createSubtaskFromGmTask'])
                ->name('create-subtask');
        });
        
        // =========== ROUTES DARI TASKCONTROLLER UNTUK KOMPATIBILITAS ===========
        
        // TUGAS MANAGEMENT
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [TaskController::class, 'managerTasks'])->name('index');
            Route::post('/', [TaskController::class, 'store'])->name('store');
            Route::get('/create', [TaskController::class, 'create'])->name('create');
            Route::get('/{id}', [TaskController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [TaskController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TaskController::class, 'update'])->name('update');
            Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy');
            
            // Comments dan Files
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
            Route::get('/{id}/files', [TaskController::class, 'getTaskFiles'])->name('files');
            Route::get('/files/{file}/download', [TaskController::class, 'downloadFileById'])->name('files.download');
            Route::delete('/files/{file}', [TaskController::class, 'deleteFile'])->name('files.delete');
            Route::post('/{id}/upload-file', [TaskController::class, 'uploadFileAdmin'])->name('upload.file');
            
            // Status update
            Route::put('/{id}/status', [TaskController::class, 'updateTaskStatus'])->name('update.status');
            Route::post('/{id}/complete', [TaskController::class, 'markAsComplete'])->name('complete');
        });

        // Halaman view untuk pengelolaan tugas
        Route::get('/pengelola_tugas', [TaskController::class, 'managerTasks'])->name('pengelola_tugas');
        
        // API untuk manager divisi (TaskController)
        Route::prefix('api/task-controller')->name('api.taskcontroller.')->group(function () {
            Route::get('/tasks', [TaskController::class, 'apiGetManagerTasks'])->name('tasks');
            Route::get('/tasks/statistics', [TaskController::class, 'apiGetStatistics'])->name('tasks.statistics');
            Route::get('/karyawan-by-divisi-id/{divisiId}', [TaskController::class, 'getKaryawanByDivisi'])->name('karyawan.by_divisi_id');
        });

        // ABSENSI
        Route::get('/kelola_absensi', [AbsensiController::class, 'kelolaAbsensiManagerDivisi'])->name('kelola_absensi');

        // CUTI MANAGEMENT
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index');
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::post('/{cuti}/approve', [CutiController::class, 'approve'])->name('approve');
            Route::post('/{cuti}/reject', [CutiController::class, 'reject'])->name('reject');
            Route::post('/{cuti}/cancel-refund', [CutiController::class, 'cancelWithRefund'])->name('cancel.refund');
            Route::get('/quota-info', [CutiController::class, 'getQuotaInfo'])->name('quota.info');
            Route::get('/{cuti}', [CutiController::class, 'show'])->name('show');
            Route::get('/{cuti}/edit', [CutiController::class, 'edit'])->name('edit');
            Route::put('/{cuti}', [CutiController::class, 'update'])->name('update');
            Route::get('/karyawan-by-divisi', [CutiController::class, 'getKaryawanByDivisi'])->name('karyawan.by-divisi');
            Route::get('/summary', [CutiController::class, 'getSummary'])->name('summary');
        });

        // DATA PROJECT
        Route::get('/data_project', [DataProjectController::class, 'managerDivisi'])->name('data_project');
        Route::post('/data_project/{id}/update', [DataProjectController::class, 'updateManager'])->name('data_project.update');
        Route::get('/data_project/filter', [DataProjectController::class, 'filterByUser'])->name('data_project.filter');

        // Tim Saya
        Route::get('/tim-saya', function () {
            $user = Auth::user();
            $tim = \App\Models\User::where('divisi', $user->divisi)
                                  ->where('role', 'karyawan')
                                  ->get();
            return view('manager_divisi.tim_saya', compact('tim'));
        })->name('tim_saya');

        /* API UNTUK MANAGER DIVISI TASKS */
        Route::prefix('api')->middleware(['role:manager_divisi'])->name('api.manager.divisi.')->group(function () {
            // Tasks API dari TaskController
            Route::get('/tasks', [TaskController::class, 'apiGetManagerTasks'])->name('tasks');
            Route::get('/tasks/statistics', [TaskController::class, 'apiGetStatistics'])->name('tasks.statistics');
            Route::get('/projects/managed', [TaskController::class, 'getManagedProjects']);
            Route::get('/karyawan-by-divisi-id/{divisiId}', [TaskController::class, 'getKaryawanByDivisi'])->name('karyawan.by_divisi_id');
        });
    });

/*
|--------------------------------------------------------------------------
| Shortcuts & Redirects
|--------------------------------------------------------------------------
*/
Route::get('/redirect-login', function () {
    if (Auth::check()) { 
        return redirectToRolePage(Auth::user()); 
    }
    return redirect('/login');
})->name('redirect.login');

Route::get('/tugas', function () {
    if (!Auth::check()) return redirect('/login');
    return match (Auth::user()->role) {
        'karyawan' => redirect()->route('karyawan.tugas.index'),
        'admin' => redirect()->route('admin.tasks.index'),
        'general_manager' => redirect()->route('general_manajer.kelola_tugas'),
        'manager_divisi' => redirect()->route('manager_divisi.kelola-tugas'),
        default => redirect('/login')
    };
})->name('tugas.redirect');

Route::get('/absensi', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }
    
    $user = Auth::user();
    
    try {
        return match ($user->role) {
            'admin' => redirect()->route('admin.absensi.index'),
            'general_manager' => redirect()->route('general_manajer.kelola_absen'),
            'owner' => redirect()->route('owner.rekap_absen'),
            'karyawan' => redirect()->route('karyawan.absensi.page'),
            'manager_divisi' => redirect()->route('manager_divisi.kelola_absensi'),
            'finance' => redirect()->route('finance.beranda'),
            default => redirect('/login')
        };
    } catch (\Exception $e) {
        return redirect('/redirect-login');
    }
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
            'finance' => redirect()->route('finance.cuti.index'),
            default => redirect('/login')
        };
    }
    return redirect('/login');
})->name('cuti.redirect');

Route::get('/quota', function () {
    if (!Auth::check()) return redirect('/login');
    return match (Auth::user()->role) {
        'karyawan' => redirect()->route('karyawan.cuti.quota.info'),
        'admin', 'general_manager', 'owner' => redirect()->route('admin.cuti.quota.info'),
        'manager_divisi' => redirect()->route('manager_divisi.cuti.quota.info'),
        'finance' => redirect()->route('finance.cuti.quota.info'),
        default => redirect('/login')
    };
})->name('quota.redirect');

/*
|--------------------------------------------------------------------------
| Legacy & Fallback Routes
|--------------------------------------------------------------------------
*/

// Legacy redirect untuk acc_cuti
Route::middleware(['auth', 'role:general_manager'])
    ->get('/general_manajer/acc_cuti', function () {
        return redirect()->route('general_manajer.cuti.index');
    })->name('general_manajer.acc_cuti');

// Finance legacy routes
Route::get('/data', [LayananController::class, 'financeIndex']);
Route::get('/data_orderan', function () { return view('finance.data_orderan'); });
Route::get('/finance', function () { return view('finance.beranda'); });
Route::get('/pemasukan', function () { return view('finance.pemasukan'); });
Route::get('/pengeluaran', function () { return view('finance.pengeluaran'); });
Route::get('/finance/invoice', function () { return view('finance.invoice'); });

// General Manager legacy shortcuts
Route::get('/general_manajer', function () { return view('general_manajer.home'); });
Route::get('/kelola_tugas', function () { 
    return redirect()->route('general_manajer.kelola_tugas'); 
});
Route::get('/kelola_absen', function () { return view('general_manajer.kelola_absen'); });

// Admin legacy
Route::get('/admin/templat', function () { return view('admin.templet_surat'); });

// Invoice print
Route::get('/invoices/{invoice}/print', function (\App\Models\Invoice $invoice) {
    return view('invoices.print', compact('invoice'));
})->name('invoices.print');

// Additional routes
Route::get('/data', [LayananController::class, 'financeIndex']);
Route::get('/data_orderan', function () {
    return view('finance/data_orderan');
});
Route::get('/finance', function () {
    return view('finance/beranda');
});
Route::get('/pemasukan', [FinanceController::class, 'index']);
Route::post('/pemasukan', [FinanceController::class, 'store']);
Route::get('/pengeluaran', function () {
    return view('finance/pengeluaran');
});
Route::get('/finance/invoice', function () {
    return view('finance/invoice');
});

Route::get('/general_manajer', function () {
    return view('general_manajer/home');
});

Route::get('/kelola_tugas', [TaskController::class, 'index'])->name('tugas.page');
Route::get('/kelola_absen', function () {
    return view('general_manajer/kelola_absen');
});

// API untuk jumlah layanan
Route::middleware(['auth'])->prefix('api/services')->name('api.services.')->group(function () {
    Route::get('/count', [LayananController::class, 'getCount'])->name('count');
});

// Admin Template
Route::get('/admin/templat', function () {
    return view('admin.templet_surat');
});

/*
|--------------------------------------------------------------------------
| MAIN FALLBACK ROUTE
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (Auth::check()) {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.beranda');
            case 'karyawan':
                return redirect()->route('karyawan.home');
            case 'general_manager':
                return redirect()->route('general_manajer.home');
            case 'manager_divisi':
                return redirect()->route('manager_divisi.home');
            case 'owner':
                return redirect()->route('owner.home');
            case 'finance':
                return redirect()->route('finance.beranda');
            default:
                return redirect('/login');
        }
    }

    return redirect('/login');
});