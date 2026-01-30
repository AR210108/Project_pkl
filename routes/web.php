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

// API endpoints public
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/contact', [SettingController::class, 'getContactData'])->name('contact');
    Route::get('/about', [SettingController::class, 'getAboutData'])->name('about');
    Route::get('/articles', [SettingController::class, 'getArticlesData'])->name('articles');
    Route::get('/portfolios', [SettingController::class, 'getPortfoliosData'])->name('portfolios');
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.public');
});

// Auth routes
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Perlu Login)
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

    // Global API Routes
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

        // Admin/GM Absensi API
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
        // Dashboard
        Route::get('/beranda', [AdminController::class, 'beranda'])->name('beranda');
        Route::get('/home', function () { return redirect()->route('admin.beranda'); });
        
        // API Dashboard
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

        // User Management
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
            Route::get('/data', [UserController::class, 'getData'])->name('data');
        });

        // Karyawan Management
        Route::prefix('karyawan')->name('karyawan.')->group(function () {
            Route::get('/', [AdminKaryawanController::class, 'index'])->name('index');
            Route::post('/store', [AdminKaryawanController::class, 'store'])->name('store');
            Route::post('/update/{id}', [AdminKaryawanController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [AdminKaryawanController::class, 'destroy'])->name('delete');
        });

        // Absensi
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

        // KEUANGAN
        Route::get('/keuangan', function () {
            return view('admin.keuangan');
        })->name('keuangan.index');

        // Route untuk sidebar: /admin/data_order (hanya placeholder)
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

        // Data Project
        Route::get('/data_project', [DataProjectController::class, 'admin'])->name('data_project');
        Route::post('/project', [DataProjectController::class, 'store'])->name('project.store');
        Route::put('/project/{id}', [DataProjectController::class, 'update'])->name('project.update');
        Route::delete('/project/{id}', [DataProjectController::class, 'destroy'])->name('project.destroy');

        Route::get('/template_surat', function () { return view('admin.template_surat'); })->name('template_surat');

        // Invoice
        Route::prefix('invoice')->name('invoice.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/create', [InvoiceController::class, 'create'])->name('create');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
        });

        // Kwitansi
        Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            Route::get('/', [KwitansiController::class, 'index'])->name('index');
            Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
        });

        // CUTI MANAGEMENT WITH QUOTA - PERBAIKAN ROUTE
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

            // Contact
            Route::get('/contact', [SettingController::class, 'contact'])->name('contact');
            Route::post('/contact', [SettingController::class, 'updateContact'])->name('contact.update');
            
            // About
            Route::get('/about', [SettingController::class, 'about'])->name('about');
            Route::post('/about', [SettingController::class, 'updateAbout'])->name('about.update');
            
            // Articles
            Route::get('/articles', [SettingController::class, 'articles'])->name('articles');

            // Mendapatkan data satu artikel untuk diedit
            Route::get('/articles/{id}', [SettingController::class, 'getArticle'])->name('articles.get');

            // Menyimpan artikel baru
            Route::post('/articles', [SettingController::class, 'storeArticle'])->name('articles.store');

            // Mengupdate artikel
            Route::put('/articles/{id}', [SettingController::class, 'updateArticle'])->name('articles.update');

            // Menghapus artikel
            Route::delete('/articles/{id}', [SettingController::class, 'deleteArticle'])->name('articles.delete');
            
            // Portfolios
            Route::get('/portfolios', [SettingController::class, 'portfolios'])->name('portfolios');

            // Mendapatkan data satu portofolio untuk diedit
            Route::get('/portfolios/{id}', [SettingController::class, 'getPortfolio'])->name('portfolios.get');

            // Menyimpan portofolio baru
            Route::post('/portfolios', [SettingController::class, 'storePortfolio'])->name('portfolios.store');

            // Mengupdate portofolio
            Route::put('/portfolios/{id}', [SettingController::class, 'updatePortfolio'])->name('portfolios.update');

            // Menghapus portofolio
            Route::delete('/portfolios/{id}', [SettingController::class, 'deletePortfolio'])->name('portfolios.delete');
        });
    });

/*
|--------------------------------------------------------------------------
| Role: KARYAWAN Routes - PERBAIKAN COMPLETE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        // Dashboard
        Route::get('/home', [KaryawanController::class, 'home'])->name('home');

        // CUTI WITH QUOTA - PERBAIKAN ROUTE URUTAN
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

        // Profile
        Route::get('/profile', [KaryawanProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [KaryawanProfileController::class, 'update'])->name('profile.update');

        // ABSENSI - FIXED
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
        Route::get('/absensi/list', [KaryawanController::class, 'absensiListPage'])->name('absensi.list');
        
        // Tugas
        Route::prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/', [KaryawanController::class, 'listPage'])->name('index');
            Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
            Route::get('/{id}', [TaskController::class, 'karyawanShow'])->name('show');

            // API untuk update status tugas
            Route::put('/{id}/status', [TaskController::class, 'updateStatus'])->name('update.status');

            // API untuk upload file tugas
            Route::post('/{id}/upload', [TaskController::class, 'uploadFile'])->name('upload');

            // API untuk comment
            Route::get('/{id}/comments', [TaskController::class, 'getComments'])->name('comments');
            Route::post('/{id}/comments', [TaskController::class, 'storeComment'])->name('comments.store');
        });

        // LIST PAGE
        Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');

        // API khusus karyawan
        Route::prefix('api')->name('api.')->group(function () {
            // DASHBOARD DATA
            Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardDataApi'])->name('dashboard.data');
            
            // MEETING NOTES
            Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingDatesApi'])->name('meeting.notes.dates');
            Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesApi'])->name('meeting.notes.get');
            
            // ANNOUNCEMENTS
            Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesApi'])->name('announcements.dates');
            Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsApi'])->name('announcements.get');
            
            // Existing API routes...
            Route::get('/dashboard', [KaryawanController::class, 'getDashboardData'])->name('dashboard');
            Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardData'])->name('dashboard.data'); // Alias untuk kompatibilitas
    
            // Absensi - Status Hari Ini
            Route::get('/today-status', [KaryawanController::class, 'getTodayStatus'])->name('today.status');

            // History absensi
            Route::get('/history', [KaryawanController::class, 'getHistory'])->name('history');
            
            // API Absensi
            Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
            Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');
            Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('submit.izin');
            Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('submit.dinas');
            
            Route::get('/tasks/statistics', [TaskController::class, 'getStatistics'])->name('tasks.statistics');

            // Task list for karyawan
            Route::get('/tasks', [KaryawanController::class, 'getTasksApi'])->name('tasks');
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

        // Karyawan
        Route::get('/data_karyawan', [AdminKaryawanController::class, 'karyawanGeneral'])->name('data_karyawan');
        Route::get('/layanan', [LayananController::class, 'Generalindex'])->name('layanan');

        // Data Project
        Route::get('/data_project', [DataProjectController::class, 'index'])->name('data_project');
        Route::post('/data_project', [DataProjectController::class, 'store'])->name('data_project.store');
        Route::put('/data_project/{id}', [DataProjectController::class, 'update'])->name('data_project.update');
        Route::delete('/data_project/{id}', [DataProjectController::class, 'destroy'])->name('data_project.destroy');

        // Tugas
        Route::get('/kelola-tugas', [GeneralManagerTaskController::class, 'index'])->name('kelola_tugas');

        // CUTI MANAGEMENT - PERBAIKAN
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

        // Tasks Management
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

            Route::get('/karyawan-by-divisi/{divisi}', [GeneralManagerTaskController::class, 'getKaryawanByDivisi'])
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
        // Dashboard
        Route::get('/home', function () { return view('pemilik.home'); })->name('home');
        Route::get('/rekap_absen', [AbsensiController::class, 'rekapAbsensi'])->name('rekap_absen');
        Route::get('/laporan', function () { return view('pemilik.laporan'); })->name('laporan');
        
        // CUTI VIEW ONLY - PERBAIKAN
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
        // Dashboard
        Route::get('/beranda', function () { return view('finance.beranda'); })->name('beranda');
        Route::get('/data-layanan', function () { return view('finance.data_layanan'); })->name('data_layanan');
        Route::get('/pembayaran', function () { return view('finance.data_orderan'); })->name('pembayaran');
        Route::get('/laporan-keuangan', function () { return view('finance.laporan_keuangan'); })->name('laporan_keuangan');
        Route::get('/daftar_karyawan', [AdminKaryawanController::class, 'karyawanFinance'])->name('daftar_karyawan');
        Route::put('/karyawan/{karyawan}', [AdminKaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/karyawan/{karyawan}', [AdminKaryawanController::class, 'destroy'])->name('karyawan.destroy');
        
        // CUTI VIEW ONLY untuk finance
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index');
            Route::get('/data', [CutiController::class, 'getData'])->name('data');
            Route::get('/stats', [CutiController::class, 'stats'])->name('stats');
            Route::get('/quota-info', [CutiController::class, 'getQuotaInfo'])->name('quota.info');
            Route::get('/report', [CutiController::class, 'report'])->name('report');
            Route::get('/{cuti}', [CutiController::class, 'show'])->name('show');
        });
    });

/*
|--------------------------------------------------------------------------
| Role: MANAGER DIVISI Routes - PERBAIKAN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:manager_divisi'])
    ->prefix('manager_divisi')
    ->name('manager_divisi.')
    ->group(function () {
        // Dashboard
        Route::get('/home', function () { return view('manager_divisi.home'); })->name('home');

        // CUTI MANAGEMENT - PERBAIKAN
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

        // Tugas Management
        Route::get('/kelola-tugas', [ManagerDivisiTaskController::class, 'index'])->name('kelola_tugas');
        
        // Data Project
        Route::get('/data_project', [DataProjectController::class, 'managerDivisi'])
            ->name('data_project');
        Route::put('/data_project/{id}', [DataProjectController::class, 'update'])
            ->name('data_project.update');
            
        // ROUTE GROUP UNTUK TASKS - PERBAIKAN
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [ManagerDivisiTaskController::class, 'index'])->name('index');
            Route::get('/create', [ManagerDivisiTaskController::class, 'create'])->name('create');
            Route::post('/', [ManagerDivisiTaskController::class, 'store'])->name('store');
            Route::get('/{id}', [ManagerDivisiTaskController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ManagerDivisiTaskController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ManagerDivisiTaskController::class, 'update'])->name('update');
            Route::put('/{id}/status', [ManagerDivisiTaskController::class, 'updateStatus'])->name('update.status');
            Route::post('/{id}/assign', [ManagerDivisiTaskController::class, 'assignToKaryawan'])->name('assign');
            Route::delete('/{id}', [ManagerDivisiTaskController::class, 'destroy'])->name('destroy');
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
        
        Route::get('/kelola_absensi', [AbsensiController::class, 'kelolaAbsensiManagerDivisi'])->name('kelola_absensi');
        
        // Tim Saya
        Route::get('/tim-saya', function () {
            $user = Auth::user();
            $tim = \App\Models\User::where('divisi', $user->divisi)
                                  ->where('role', 'karyawan')
                                  ->get();
            return view('manager_divisi.tim_saya', compact('tim'));
        })->name('tim_saya');
    });

/*
|--------------------------------------------------------------------------
| Shortcuts & Redirects
|--------------------------------------------------------------------------
*/

// Redirect setelah login
Route::get('/redirect-login', function () {
    if (Auth::check()) { 
        return redirectToRolePage(Auth::user()); 
    }
    return redirect('/login');
})->name('redirect.login');

// Pintasan untuk tugas berdasarkan role
Route::get('/tugas', function () {
    if (!Auth::check()) return redirect('/login');
    return match (Auth::user()->role) {
        'karyawan' => redirect()->route('karyawan.tugas.index'),
        'admin' => redirect()->route('admin.tasks.index'),
        'general_manager' => redirect()->route('general_manajer.kelola_tugas'),
        'manager_divisi' => redirect()->route('manager_divisi.kelola_tugas'),
        default => redirect('/login')
    };
})->name('tugas.redirect');

// PERBAIKAN: Redirect /absensi yang lebih aman
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
        // Fallback ke home jika ada error
        return redirect('/redirect-login');
    }
})->name('absensi.redirect');

// Pintasan untuk cuti berdasarkan role
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
| Debug & Test Routes (Development Only)
|--------------------------------------------------------------------------
*/

if (env('APP_DEBUG', false)) {
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
                
                // Test quota info
                $quotaResponse = $controller->getQuotaInfo($request);
                $quotaData = json_decode($quotaResponse->getContent(), true);
                
                echo "<h2>Quota Info Test:</h2>";
                echo "Status: " . ($quotaData['success'] ? 'SUCCESS' : 'FAILED') . "<br>";
                if ($quotaData['success']) {
                    echo "<pre>" . print_r($quotaData['data'], true) . "</pre>";
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
                    'karyawan_cuti_edit' => route('karyawan.cuti.edit', ['cuti' => 1]),
                    'karyawan_cuti_update' => route('karyawan.cuti.update', ['cuti' => 1]),
                    'karyawan_quota_info' => route('karyawan.cuti.quota.info'),
                    'general_manager_cuti_index' => route('general_manajer.cuti.index'),
                    'general_manager_cuti_data' => route('general_manajer.cuti.data'),
                    'admin_reset_quota' => route('admin.cuti.reset.quota'),
                ],
                'current_url' => url()->current()
            ]);
        });
        
        // Database check
        Route::get('/check-db', [CutiController::class, 'checkDatabase'])->name('check.db');
        
        // Route list debug
        Route::get('/debug/routes', function() {
            $routes = Route::getRoutes();
            
            echo "<h1>All Routes</h1>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Method</th><th>URI</th><th>Name</th><th>Action</th></tr>";
            
            foreach ($routes as $route) {
                echo "<tr>";
                echo "<td>" . implode('|', $route->methods()) . "</td>";
                echo "<td>" . $route->uri() . "</td>";
                echo "<td>" . ($route->getName() ?? '-') . "</td>";
                echo "<td>" . ($route->getActionName() ?? '-') . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
            // Cek khusus karyawan routes
            echo "<h2>Karyawan Routes:</h2>";
            echo "<ul>";
            $karyawanRoutes = collect($routes)->filter(function($route) {
                return strpos($route->getName() ?? '', 'karyawan.') === 0;
            });
            
            foreach ($karyawanRoutes as $route) {
                echo "<li>" . ($route->getName() ?? $route->uri()) . "</li>";
            }
            echo "</ul>";
            
            // Cek khusus cuti routes
            echo "<h2>Cuti Routes:</h2>";
            echo "<ul>";
            $cutiRoutes = collect($routes)->filter(function($route) {
                return strpos($route->getName() ?? '', 'cuti.') !== false;
            });
            
            foreach ($cutiRoutes as $route) {
                echo "<li>" . ($route->getName() ?? $route->uri()) . " - " . implode('|', $route->methods()) . "</li>";
            }
            echo "</ul>";
        });
        
        // Test specific route
        Route::get('/test/route/{name}', function($name) {
            try {
                $url = route($name);
                return response()->json([
                    'success' => true,
                    'name' => $name,
                    'url' => $url,
                    'exists' => true
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'name' => $name,
                    'error' => $e->getMessage(),
                    'exists' => false
                ]);
            }
        });
        
        // Test cuti status for current user
        Route::get('/test/my-cuti-status', function() {
            $user = Auth::user();
            $today = now()->format('Y-m-d');
            
            $cutiAktif = \App\Models\Cuti::where('user_id', $user->id)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->get();
            
            return response()->json([
                'user_id' => $user->id,
                'user_role' => $user->role,
                'today' => $today,
                'on_leave' => $cutiAktif->count() > 0,
                'cuti_details' => $cutiAktif,
                'absensi_route' => route('karyawan.absensi.page'),
                'home_route' => route('karyawan.home')
            ]);
        });
        
        // Test edit cuti
        Route::get('/test/edit-cuti/{id}', function($id) {
            try {
                $cuti = \App\Models\Cuti::find($id);
                
                if (!$cuti) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cuti tidak ditemukan'
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'cuti' => $cuti,
                    'edit_route' => route('karyawan.cuti.edit', $cuti->id),
                    'update_route' => route('karyawan.cuti.update', $cuti->id),
                    'can_edit' => $cuti->status === 'menunggu'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
        });
    });
}

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

/*
|--------------------------------------------------------------------------
| FALLBACK ROUTE
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