<?php

use App\Http\Controllers\CashflowController;
use App\Http\Controllers\OwnerBerandaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\PerusahaanController;
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
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\BerandaFinanceController;
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
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.public');

    // Test API untuk finance (temporary)
    Route::get('/finance/layanan-test', function () {
        return response()->json([
            'success' => true,
            'data' => [
                ['id' => 1, 'nama_layanan' => 'Web Development', 'harga' => 10000000, 'deskripsi' => 'Pembuatan website'],
                ['id' => 2, 'nama_layanan' => 'Mobile App Development', 'harga' => 15000000, 'deskripsi' => 'Pembuatan aplikasi mobile'],
                ['id' => 3, 'nama_layanan' => 'UI/UX Design', 'harga' => 5000000, 'deskripsi' => 'Desain antarmuka'],
            ],
            'message' => 'Data dummy untuk testing finance layanan'
        ]);
    })->name('api.finance.layanan.test');
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
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
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

        // Projects API (Global - untuk semua role yang membutuhkan)
        Route::get('/projects', [DataProjectController::class, 'getAllProjects'])->name('projects.all');

        // Cuti API
        Route::prefix('cuti')->name('cuti.')->group(function () {
            Route::get('/check-leave-status', [CutiController::class, 'checkLeaveStatusApi'])->name('check-leave-status');
            Route::get('/{id}/history', [CutiController::class, 'getHistory'])->name('history');
            Route::get('/summary', [CutiController::class, 'getSummary'])->name('summary');
            Route::post('/calculate-duration', [CutiController::class, 'calculateDuration'])->name('calculate-duration');
        });

        // Tasks API - Global
        Route::prefix('tasks')->name('tasks.')->group(function () {
            // Store task global
            Route::post('/', [TaskController::class, 'store'])->name('store');
            
            // Route khusus untuk manager divisi
            Route::post('/store-for-manager', [TaskController::class, 'storeForManager'])
                ->middleware(['role:manager_divisi'])
                ->name('store.for-manager');
            
            // Test endpoint untuk debugging
            Route::post('/test', [TaskController::class, 'testCreateTask'])->name('test');
            
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
            Route::get('/statistics', [TaskController::class, 'apiGetStatistics'])->name('statistics');
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
            // Owner Specific APIs for Meeting/Announcements
            Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingNotesDatesForOwner']);
            Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesByDateForOwner']);
            Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesForOwner']);
            Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForOwner']);
        });
        
        // Manager Divisi Specific APIs (Global prefix)
        Route::prefix('manager-divisi')->middleware(['role:manager_divisi'])->name('manager.divisi.')->group(function () {
            Route::get('/tasks', [ManagerDivisiTaskController::class, 'getTasksApi'])->name('tasks');
            Route::get('/tasks/statistics', [ManagerDivisiTaskController::class, 'getStatistics'])->name('tasks.statistics');
            Route::get('/karyawan-by-divisi/{divisi}', [ManagerDivisiTaskController::class, 'getKaryawanByDivisi'])->name('karyawan.by_divisi');
            
            // Route khusus untuk projects-dropdown
            Route::get('/project-dropdown', [ManagerDivisiTaskController::class, 'getProjectsDropdown'])->name('project.dropdown');
            
            // Route alternatif untuk kompatibilitas
            Route::get('/projects', [ManagerDivisiTaskController::class, 'getProjectsDropdown'])->name('projects');
            
            // Manager Divisi Specific APIs for Meeting/Announcements
            Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingNotesDatesForManager']);
            Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesByDateForManager']);
            Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesForManager']);
            Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForManager']);
        });

        // General Manager Specific APIs
        Route::prefix('general-manager')->middleware(['role:general_manager'])->name('general.manager.')->group(function () {
            Route::get('/tasks', [GeneralManagerTaskController::class, 'getTasksApi'])->name('tasks');
            Route::get('/tasks/statistics', [GeneralManagerTaskController::class, 'getStatistics'])->name('tasks.statistics');
            Route::get('/karyawan-by-divisi/{divisi}', [GeneralManagerTaskController::class, 'getKaryawanByDivisi'])->name('karyawan.by_divisi');
            // GM Specific APIs for Meeting/Announcements
            Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingNotesDatesForGM']);
            Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesByDateForGM']);
            Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesForGM']);
            Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForGM']);
        });

        // Finance API
        Route::prefix('finance')->middleware(['role:finance'])->name('finance.')->group(function () {
            Route::get('/layanan', [LayananController::class, 'financeApi'])->name('layanan.api');
            Route::get('/invoices', [InvoiceController::class, 'getFinanceInvoices'])->name('invoices.api');
        });

        // ============== PERBAIKAN: Tambahkan Kwitansi API Routes di sini ==============
        Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            Route::get('/', [KwitansiController::class, 'getKwitansiData'])->name('index');
            Route::get('/{id}', [KwitansiController::class, 'show'])->name('show');
            Route::post('/', [KwitansiController::class, 'store'])->name('store');
            Route::put('/{id}', [KwitansiController::class, 'update'])->name('update');
            Route::delete('/{id}', [KwitansiController::class, 'destroy'])->name('destroy');
        });

        // Invoice API untuk dropdown
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [InvoiceController::class, 'getInvoicesForDropdown'])->name('dropdown');
            Route::get('/{id}', [InvoiceController::class, 'getInvoiceDetail'])->name('detail');
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
        Route::get('/data_user', function () {
            return redirect()->route('admin.user');
        });

        Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
        Route::get('/perusahaan/create', [PerusahaanController::class, 'create'])->name('perusahaan.create');
        Route::post('/perusahaan', [PerusahaanController::class, 'store'])->name('perusahaan.store');
        Route::get('/perusahaan/{perusahaan}/edit', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
        Route::put('/perusahaan/{perusahaan}', [PerusahaanController::class, 'update'])->name('perusahaan.update'); // INI YANG PENTING
        Route::delete('/perusahaan/{perusahaan}', [PerusahaanController::class, 'destroy'])->name('perusahaan.delete'); // Nama route 'delete' atau 'destroy' harus konsisten
        Route::get('/perusahaan/data', [PerusahaanController::class, 'getDataForDropdown'])
            ->name('perusahaan.data');

        // KARYAWAN MANAGEMENT - ROUTE YANG DIPERBAIKI
        Route::get('/data_karyawan', [AdminKaryawanController::class, 'index'])->name('karyawan.index');
        Route::post('/karyawan/store', [AdminKaryawanController::class, 'store'])->name('karyawan.store');

        // Untuk update gunakan PUT dan POST untuk fallback
        Route::put('/karyawan/update/{id}', [AdminKaryawanController::class, 'update'])->name('karyawan.update');
        Route::post('/karyawan/update/{id}', [AdminKaryawanController::class, 'update'])->name('karyawan.update.post');

        // Untuk delete
        Route::delete('/karyawan/delete/{id}', [AdminKaryawanController::class, 'destroy'])->name('karyawan.delete');

        // Untuk get data
        Route::get('/karyawan/get/{id}', [AdminKaryawanController::class, 'getKaryawanData'])->name('karyawan.get.data');
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
            Route::get('/dropdown', [LayananController::class, 'getForInvoiceDropdown'])->name('dropdown');
            Route::get('/{id}', [LayananController::class, 'show'])->name('show');
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

            Route::get('/data/layanan', [InvoiceController::class, 'getLayananData'])->name('data.layanan');
            Route::get('/list/layanan', [InvoiceController::class, 'getLayananList'])->name('list.layanan');
            Route::get('/list/status-pembayaran', [InvoiceController::class, 'getStatusPembayaranList'])->name('list.status');
        });

        // Data Project
        Route::get('/data_project', [DataProjectController::class, 'admin'])->name('data_project');
        Route::get('/project/{id}', [DataProjectController::class, 'show'])->name('project.show');
        Route::post('/project', [DataProjectController::class, 'store'])->name('project.store');
        Route::put('/project/{id}', [DataProjectController::class, 'update'])->name('project.update');
        Route::delete('/project/{id}', [DataProjectController::class, 'destroy'])->name('project.destroy');
        Route::post('/project/sync-from-invoice/{id}', [DataProjectController::class, 'syncFromInvoice'])
            ->name('admin.project.sync');
        Route::get('/project/invoice/{id}/details', [DataProjectController::class, 'getInvoiceDetails']);

        Route::get('/surat_kerjasama', function () {
            return redirect()->route('admin.surat_kerjasama.index');
        });

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
        Route::get('/layanan-data', [InvoiceController::class, 'getLayananForDropdown'])
            ->name('layanan-data');
        Route::get('/invoice/perusahaan-data', [InvoiceController::class, 'getPerusahaanData'])
            ->name('invoice.perusahaan.data');


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
            
            // =========== ABSENSI API ROUTES ===========
            Route::get('/today-status', [KaryawanController::class, 'getTodayStatusApi'])->name('today.status');
            Route::get('/history', [KaryawanController::class, 'getHistoryApi'])->name('history');
            Route::get('/dashboard', [KaryawanController::class, 'getDashboardData'])->name('dashboard');
            Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
            Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');
            Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('submit.izin');
            Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('submit.dinas');
            Route::get('/pengajuan-status', [KaryawanController::class, 'getPengajuanStatus'])->name('pengajuan-status');
            Route::get('/tasks', [KaryawanController::class, 'getTasksApi'])->name('tasks');
        });

        Route::get('/pengajuan_cuti', function () {
            return redirect()->route('karyawan.cuti.index');
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
        Route::get('/home', function () {
            return view('general_manajer.home');
        })->name('home');

        // Profile
        Route::get('/profile', function () {
            return view('general_manajer.profile');
        })->name('profile');

        Route::get('/data_karyawan', [AdminKaryawanController::class, 'karyawanGeneral'])->name('data_karyawan');
        Route::get('/layanan', [LayananController::class, 'Generalindex'])->name('layanan');

        // DATA PROJECT - ROUTES
        Route::get('/data_project', [DataProjectController::class, 'index'])->name('data_project');

        Route::prefix('data_project')->name('data_project.')->group(function () {
            Route::get('/', [DataProjectController::class, 'index'])->name('index');
            Route::post('/', [DataProjectController::class, 'store'])->name('store');
            Route::get('/{id}', [DataProjectController::class, 'show'])->name('show');
            Route::put('/{id}', [DataProjectController::class, 'update'])->name('update');
            Route::post('/{id}/assign-responsible', [DataProjectController::class, 'assignResponsible'])->name('assign.responsible');
            Route::delete('/{id}', [DataProjectController::class, 'destroy'])->name('destroy');
            Route::post('/sync/{layananId}', [DataProjectController::class, 'syncFromLayanan'])->name('sync');
        });

        // === DATA PERUSAHAAN (DITAMBAHKAN) ===
        Route::get('/perusahaan', [GMPerusahaanController::class, 'index'])->name('perusahaan.index');
        Route::post('/perusahaan', [GMPerusahaanController::class, 'store'])->name('perusahaan.store');
        Route::put('/perusahaan/{id}', [GMPerusahaanController::class, 'update'])->name('perusahaan.update');
        Route::delete('/perusahaan/{id}', [GMPerusahaanController::class, 'destroy'])->name('perusahaan.delete');

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

        // Absensi Management
        Route::get('/kelola-absen', [AbsensiController::class, 'kelolaAbsenGeneral'])->name('kelola_absen');

        // Action untuk approve/reject
        Route::post('/general-manajer/absensi/{id}/approve', [AbsensiController::class, 'approveAbsensi'])
            ->name('general_manajer.absensi.approve');

        Route::post('/general-manajer/absensi/{id}/reject', [AbsensiController::class, 'rejectAbsensi'])
            ->name('general_manajer.absensi.reject');

        Route::get('/tim_dan_divisi', function () {
            return view('general_manajer.tim_dan_divisi');
        })->name('tim_dan_divisi');

        // Halaman utama tim divisi
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

        Route::get('home', [OwnerBerandaController::class, 'index'])->name('home');

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
    });

/*
|--------------------------------------------------------------------------
| Role: FINANCE Routes - DIPERBAIKI DENGAN API ENDPOINTS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:finance'])
    ->prefix('finance')
    ->name('finance.')
    ->group(function () {
        Route::get('/beranda', [BerandaFinanceController::class, 'index'])->name('beranda');
        Route::get('/test', [BerandaFinanceController::class, 'index'])->withoutMiddleware(['auth', 'role:finance'])->name('test');

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
        Route::get('/layanan', [LayananController::class, 'financeIndex'])->name('layanan.index');

        // Update harga saja
        Route::put('/layanan/{id}/update-harga', [LayananController::class, 'updateHarga'])->name('layanan.update-harga');

        // CUTI VIEW ONLY untuk finance
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

        // CASHFLOW MANAGEMENT
        Route::prefix('cashflow')->name('cashflow.')->group(function () {
            Route::get('/', [CashflowController::class, 'index'])->name('index');
            Route::post('/', [CashflowController::class, 'store'])->name('store');
        });

        // KWITANSI MANAGEMENT - FINANCE
        Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            Route::get('/', [KwitansiController::class, 'financeIndex'])->name('finance.kwitansi.index');
            Route::post('/', [KwitansiController::class, 'store'])->name('store');
            Route::put('/{id}', [KwitansiController::class, 'update'])->name('update');
            Route::delete('/{id}', [KwitansiController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
        });

        // API ENDPOINTS UNTUK FINANCE - TAMBAHAN BARU
        Route::prefix('api')->name('api.')->group(function () {
            // API untuk layanan finance (JSON response)
            Route::get('/layanan', [LayananController::class, 'financeApi'])->name('layanan.api');

            // API untuk invoices finance (JSON response)
            Route::get('/invoices', [InvoiceController::class, 'getFinanceInvoices'])->name('invoices.api');

            // API untuk kategori cashflow
            Route::get('/kategori/{tipe}', [CashflowController::class, 'getKategoriByType'])->name('kategori.by.type');
        });
    });

// Pastikan import ini ada di paling atas file:
// use App\Http\Controllers\ManagerDivisi\MDPerusahaanController;

/*
|--------------------------------------------------------------------------
| Role: MANAGER DIVISI Routes - DIPERBAIKI
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

        Route::get('/kelola_absensi', [AbsensiController::class, 'kelolaAbsenManajer'])->name('kelola_absensi');

        // Profile
        Route::get('/profile', function () {
            return view('manager_divisi.profile');
        })->name('profile');

        // Halaman Kelola Tugas
        Route::get('/kelola-tugas', [ManagerDivisiTaskController::class, 'index'])
            ->name('kelola-tugas');
        
        // Halaman view untuk pengelolaan tugas (kompatibilitas)
        Route::get('/pengelola_tugas', [TaskController::class, 'managerTasks'])->name('pengelola_tugas');
        
        // =========== TUGAS MANAGEMENT ROUTES (CRUD) - DIPERBAIKI ===========
        Route::prefix('tasks')->name('tasks.')->group(function () {
            // ROUTE UTAMA untuk store task - MENGGUNAKAN createTask()
            Route::post('/createTask', [ManagerDivisiTaskController::class, 'createTask'])->name('createTask');
            
            // Route untuk halaman create (view)
            Route::get('/create', [TaskController::class, 'create'])->name('create');
            
            // Route untuk halaman index/view
            Route::get('/', [TaskController::class, 'managerTasks'])->name('index');
            
            // CRUD lainnya
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

        // Data Karyawan
        Route::get('/data-karyawan', function() {
            $user = Auth::user();
            $karyawan = \App\Models\User::where('divisi_id', $user->divisi_id)
                ->where('role', 'karyawan')
                ->get();
            return view('manager_divisi.data_karyawan', compact('karyawan'));
        })->name('data-karyawan');

        /* ============================================
           API ENDPOINTS KHUSUS MANAGER DIVISI - DIPERBAIKI
           ============================================ */
        Route::prefix('api')->name('api.')->group(function () {
            // Data Project untuk Dropdown - ROUTE UTAMA yang dicari frontend
            Route::get('/projects-dropdown', [ManagerDivisiTaskController::class, 'getProjectsDropdown'])
                ->name('projects-dropdown');
            
            // Data Karyawan untuk Dropdown
            Route::get('/karyawan-dropdown', [ManagerDivisiTaskController::class, 'getKaryawanDropdown'])
                ->name('karyawan-dropdown');
            
            // Data Tasks utama - MENGGUNAKAN TaskController::apiGetManagerTasks
            Route::get('/tasks-api', [TaskController::class, 'apiGetManagerTasks'])
                ->name('tasks-api');
            
            // Statistik tugas - MENGGUNAKAN TaskController::apiGetStatistics
            Route::get('/tasks/statistics', [TaskController::class, 'apiGetStatistics'])
                ->name('tasks.statistics');
            
            // API untuk create task - GUNAKAN createTask() dari ManagerDivisiTaskController
            Route::post('/tasks/create-task', [ManagerDivisiTaskController::class, 'createTask'])
                ->name('tasks.create-task');
            
            // Alternate route untuk compatibility
            Route::post('/tasks', [ManagerDivisiTaskController::class, 'store'])
                ->name('tasks.store.api');
            
            // Route khusus untuk mendapatkan detail task
            Route::get('/tasks/{id}', [ManagerDivisiTaskController::class, 'show'])
                ->name('tasks.show.api');
                
            // Update task
            Route::put('/tasks/{id}', [ManagerDivisiTaskController::class, 'update'])
                ->name('tasks.update.api');
            
            // Delete task
            Route::delete('/tasks/{id}', [ManagerDivisiTaskController::class, 'destroy'])
                ->name('tasks.destroy.api');
            
            // Test endpoint untuk create task
            Route::post('/tasks/test-create', [TaskController::class, 'testCreateTask'])
                ->name('tasks.test-create');
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
        

        // API untuk manager divisi
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/tasks', [ManagerDivisiTaskController::class, 'getTasksApi'])->name('tasks');
            Route::get('/tasks/statistics', [ManagerDivisiTaskController::class, 'getStatistics'])->name('tasks.statistics');
            Route::get('/karyawan-by-divisi/{divisi}', [ManagerDivisiTaskController::class, 'getKaryawanByDivisi'])->name('karyawan.by_divisi');
            Route::get('/daftar_karyawan/{divisi}', [AdminKaryawanController::class, 'karyawanDivisi'])->name('karyawan.divisi');
        });

        // Task management
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::post('/', [ManagerDivisiTaskController::class, 'store'])->name('store');
            Route::get('/{id}', [ManagerDivisiTaskController::class, 'show'])->name('show');
            Route::put('/{id}', [ManagerDivisiTaskController::class, 'update'])->name('update');
            Route::put('/{id}/status', [ManagerDivisiTaskController::class, 'updateStatus'])->name('update.status');
            Route::post('/{id}/assign', [ManagerDivisiTaskController::class, 'assignToKaryawan'])->name('assign');
            Route::delete('/{id}', [ManagerDivisiTaskController::class, 'destroy'])->name('destroy');
        });

        Route::get('/pengelola_tugas', function () {
            return view('manager_divisi.pengelola_tugas');
        })->name('pengelola_tugas');
        Route::get('/daftar_karyawan', [AdminKaryawanController::class, 'karyawanDivisi'])->name('daftar_karyawan');

        // Tim Saya
        Route::get('/tim-saya', function () {
            $user = Auth::user();
            $tim = \App\Models\User::where('divisi_id', $user->divisi_id)
                ->where('role', 'karyawan')
                ->get();
            return view('manager_divisi.tim_saya', compact('tim'));
        })->name('tim_saya');
    });

/*
|--------------------------------------------------------------------------
| GLOBAL API ROUTES (Additional) - DIPERBAIKI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('api')->group(function () {
    // Projects API global (untuk testing/compatibility)
    Route::get('/projects/all', [DataProjectController::class, 'getAllProjects'])->name('projects.all');

    // INVOICE API ROUTES
    Route::prefix('invoices')->name('api.invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/mark-printed', [InvoiceController::class, 'markPrinted'])->name('mark.printed');
    });

    // Karyawan API
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

    /* ABSENSI API */
    Route::prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/dashboard-data', [AbsensiController::class, 'apiTodayStatus'])->name('dashboard-data');
        Route::get('/today-status', [AbsensiController::class, 'apiTodayStatus'])->name('today-status');
        Route::get('/history', [AbsensiController::class, 'apiHistory'])->name('history');
        Route::post('/absen-masuk', [AbsensiController::class, 'apiAbsenMasuk'])->name('absen-masuk');
        Route::post('/absen-pulang', [AbsensiController::class, 'apiAbsenPulang'])->name('absen-pulang');
        Route::post('/submit-izin', [AbsensiController::class, 'apiSubmitIzin'])->name('submit-izin');
    });

    /* =====================================================
     |  API MEETING NOTES & ANNOUNCEMENTS
     ===================================================== */
    Route::get('/karyawan/meeting-notes', [KaryawanController::class, 'getMeetingNotes']);
    Route::get('/karyawan/meeting-notes-dates', [KaryawanController::class, 'getMeetingNotesDates']);
    Route::get('/karyawan/announcements', [KaryawanController::class, 'getAnnouncements']);
    Route::get('/karyawan/announcements-by-date', [KaryawanController::class, 'getAnnouncementsByDate']);
    Route::get('/karyawan/announcements-dates', [KaryawanController::class, 'getAnnouncementsDates']);
    Route::get('/karyawan/calendar-dates', [KaryawanController::class, 'getCalendarDates']);

    /* TASKS API */
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
        Route::get('/karyawan/statistics', [TaskController::class, 'getKaryawanStatistics'])->name('karyawan.statistics');
        
        // Route khusus untuk manager divisi store
        Route::post('/store-for-manager', [TaskController::class, 'storeForManager'])
            ->middleware(['role:manager_divisi'])
            ->name('store.for-manager');
            
        // Test endpoint
        Route::post('/test-create', [TaskController::class, 'testCreateTask'])->name('test-create');
    });

    /* TASKS UNTUK KARYAWAN */
    Route::prefix('karyawan-tasks')->name('karyawan.tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'getKaryawanTasks'])->name('index');
        Route::get('/{id}/detail', [TaskController::class, 'getTaskDetailForKaryawan'])->name('detail');
    });

    /* API DASHBOARD DATA */
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/karyawan', [KaryawanController::class, 'getDashboardData'])->name('karyawan');
        Route::get('/pengajuan-status', [KaryawanController::class, 'getPengajuanStatus'])->name('pengajuan.status');
        Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('submit.dinas');
    });

    // ============== PERBAIKAN: Tambahkan Kwitansi API Routes di sini ==============
    Route::prefix('kwitansi')->name('api.kwitansi.')->group(function () {
        Route::get('/', [KwitansiController::class, 'getKwitansiData'])->name('index');
        Route::get('/{id}', [KwitansiController::class, 'show'])->name('show');
        Route::post('/', [KwitansiController::class, 'store'])->name('store');
        Route::put('/{id}', [KwitansiController::class, 'update'])->name('update');
        Route::delete('/{id}', [KwitansiController::class, 'destroy'])->name('destroy');
    });

    // Invoice API untuk dropdown
    Route::prefix('invoices')->name('api.invoices.')->group(function () {
        Route::get('/dropdown', [InvoiceController::class, 'getInvoicesForDropdown'])->name('dropdown');
        Route::get('/{id}/detail', [InvoiceController::class, 'getInvoiceDetail'])->name('detail');
    });
});

/*
|--------------------------------------------------------------------------
| Shortcuts & Redirects
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Catatan Rapat
    Route::get('/catatan-rapat', [CatatanRapatController::class, 'index'])->name('catatan_rapat.index');
    Route::post('/catatan-rapat', [CatatanRapatController::class, 'store'])->name('catatan_rapat.store');
    Route::put('/catatan-rapat/{id}', [CatatanRapatController::class, 'update'])->name('catatan_rapat.update');
    Route::delete('/catatan-rapat/{id}', [CatatanRapatController::class, 'destroy'])->name('catatan_rapat.destroy');
    Route::get('/catatan-rapat/{id}', [CatatanRapatController::class, 'show'])->name('catatan_rapat.show');

    // Pengumuman
    Route::resource('pengumuman', PengumumanController::class);

    // API endpoints
    Route::get('/catatan_rapat/data', [CatatanRapatController::class, 'getData'])->name('catatan_rapat.data');
    Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
    Route::get('/divisis/list', [UserController::class, 'getDivisis'])->name('divisis.list');
});

Route::get('/redirect-login', function () {
    if (Auth::check()) {
        return redirectToRolePage(Auth::user());
    }
    return redirect('/login');
})->name('redirect.login');

Route::get('/tugas', function () {
    if (!Auth::check())
        return redirect('/login');
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
    if (!Auth::check())
        return redirect('/login');
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
| Debug & Test Routes (Development Only) - DITAMBAHKAN
|--------------------------------------------------------------------------
*/

if (env('APP_DEBUG', false)) {
    Route::middleware(['auth'])->group(function () {
        // Test route khusus untuk TaskController
        Route::get('/test/task-controller', function () {
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'divisi_id' => $user->divisi_id
                ],
                'endpoints' => [
                    'createTask' => route('manager_divisi.tasks.createTask'),
                    'api_create_task' => route('manager_divisi.api.tasks.create-task'),
                    'api_tasks_store' => route('manager_divisi.api.tasks.store.api'),
                    'test_create' => route('manager_divisi.api.tasks.test-create'),
                ],
                'controller_methods' => [
                    'ManagerDivisiTaskController::createTask' => method_exists(ManagerDivisiTaskController::class, 'createTask'),
                    'TaskController::storeForManager' => method_exists(TaskController::class, 'storeForManager'),
                    'TaskController::testCreateTask' => method_exists(TaskController::class, 'testCreateTask'),
                    'TaskController::apiGetManagerTasks' => method_exists(TaskController::class, 'apiGetManagerTasks'),
                ]
            ]);
        });

        // Test untuk check divisi
        Route::get('/test/divisi-check', function() {
            $divisi = \App\Models\Divisi::find(1);
            
            return response()->json([
                'divisi_exists' => !is_null($divisi),
                'divisi_table' => (new \App\Models\Divisi())->getTable(),
                'divisi_model' => get_class($divisi),
                'all_divisis' => \App\Models\Divisi::all()->toArray()
            ]);
        });

        // Test untuk create task secara langsung
        Route::post('/test/create-task-direct', [TaskController::class, 'testCreateTask'])
            ->withoutMiddleware(['role:manager_divisi']);
            
        Route::get('/debug/cuti-fix', function () {
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

        Route::get('/test/cuti-routes', function () {
            $user = auth::user();

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

        Route::get('/check-db', [CutiController::class, 'checkDatabase'])->name('check.db');
        
        Route::get('/debug/routes', function () {
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
        });

        Route::get('/test/route/{name}', function ($name) {
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

        // Debug route untuk Manager Divisi Projects
        Route::get('/test/manager-divisi-projects', function () {
            $user = Auth::user();
            
            if ($user->role !== 'manager_divisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya untuk manager divisi'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'endpoints' => [
                    'projects-dropdown' => route('manager_divisi.api.projects-dropdown'),
                    'api_projects_dropdown' => route('manager.divisi.project.dropdown'),
                    'karyawan_dropdown' => route('manager_divisi.api.karyawan-dropdown'),
                    'tasks_api' => route('manager_divisi.api.tasks-api'),
                    'tasks_createTask' => route('manager_divisi.tasks.createTask'),
                    'api_tasks_create_task' => route('manager_divisi.api.tasks.create-task'),
                ],
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'divisi_id' => $user->divisi_id
                ]
            ]);
        });

        Route::get('/test/my-cuti-status', function () {
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

        Route::get('/test/edit-cuti/{id}', function ($id) {
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
Route::get('/data_orderan', function () {
    return view('finance.data_orderan');
});
Route::get('/finance', function () {
    return view('finance.beranda');
});
Route::get('/pemasukan', [FinanceController::class, 'index']);
Route::post('/pemasukan', [FinanceController::class, 'store']);
Route::get('/pengeluaran', function () {
    return view('finance.pengeluaran');
});
Route::get('/finance/invoice', function () {
    return view('finance.invoice');
});

// General Manager legacy shortcuts
Route::get('/general_manajer', function () {
    return view('general_manajer.home');
});
Route::get('/kelola_tugas', function () {
    return redirect()->route('general_manajer.kelola_tugas');
});
Route::get('/kelola_absen', function () {
    return view('general_manajer.kelola_absen');
});

// Admin legacy
Route::get('/admin/templat', function () {
    return view('admin.templet_surat');
});

// Invoice print
Route::get('/invoices/{invoice}/print', function (\App\Models\Invoice $invoice) {
    return view('invoices.print', compact('invoice'));
})->name('invoices.print');

// Additional routes (re-ensuring coverage)
Route::get('/general_manajer', function () {
    return view('general_manajer/home');
});

Route::get('/tugas', [TaskController::class, 'index'])->name('tugas.page');

// API untuk jumlah layanan
Route::middleware(['auth'])->prefix('api/services')->name('api.services.')->group(function () {
    Route::get('/count', [LayananController::class, 'getCount'])->name('count');
});

// Admin Template
Route::get('/admin/templat', function () {
    return view('admin.templet_surat');
});

// Di routes/web.php

Route::prefix('general_manager/api')->middleware(['auth'])->group(function () {
    // Route untuk Catatan Rapat
    Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingNotesDatesForGM']);
    Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesByDateForGM']);

    // Route untuk Pengumuman
    Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesForGM']);
    Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForGM']);
});


// Grup route untuk Owner
Route::prefix('owner/api')->middleware(['auth', 'role:owner'])->group(function () {
    // Route untuk Catatan Rapat
    Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingNotesDatesForOwner']);
    Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesByDateForOwner']);

    // Route untuk Pengumuman
    Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesForOwner']);
    Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForOwner']);
});

// Grup route untuk Manager Divisi
Route::prefix('manager_divisi/api')->middleware(['auth', 'role:manager_divisi'])->group(function () {
    // Route untuk Catatan Rapat
    Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingNotesDatesForManager']);
    Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesByDateForManager']);

    // Route untuk Pengumuman
    Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesForManager']);
    Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForManager']);
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

// Di routes/web.php

Route::prefix('general_manager/api')->middleware(['auth'])->group(function () {
    // Route untuk Catatan Rapat
    Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingNotesDatesForGM']);
    Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesByDateForGM']);

    // Route untuk Pengumuman
    Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesForGM']);
    Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForGM']);
});


// Grup route untuk Owner
Route::prefix('owner/api')->middleware(['auth', 'role:owner'])->group(function () {
    // Route untuk Catatan Rapat
    Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingNotesDatesForOwner']);
    Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesByDateForOwner']);

    // Route untuk Pengumuman
    Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesForOwner']);
    Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForOwner']);
});

// Grup route untuk Manager Divisi
Route::prefix('manager_divisi/api')->middleware(['auth', 'role:manager_divisi'])->group(function () {
    // Route untuk Catatan Rapat
    Route::get('/meeting-notes-dates', [CatatanRapatController::class, 'getMeetingNotesDatesForManager']);
    Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesByDateForManager']);

    // Route untuk Pengumuman
    Route::get('/announcements-dates', [PengumumanController::class, 'getAnnouncementDatesForManager']);
    Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForManager']);
});

// Route untuk pengaturan jam operasional
Route::post('/admin/settings/operational-hours', [App\Http\Controllers\SettingController::class, 'saveOperationalHours'])->name('admin.settings.operational-hours');
Route::get('/admin/settings/operational-hours', [App\Http\Controllers\SettingController::class, 'getOperationalHours'])->name('admin.settings.operational-hours.get');
// Route untuk API jam operasional
Route::get('/api/operational-hours', [App\Http\Controllers\AbsensiController::class, 'apiGetOperationalHours']);

// Finance API routes
Route::middleware(['auth', 'role:finance'])->prefix('finance/api')->group(function () {
    Route::get('/meeting-notes', [CatatanRapatController::class, 'getMeetingNotesForFinance']);
    Route::get('/announcements', [PengumumanController::class, 'getAnnouncementsForFinance']);
});
