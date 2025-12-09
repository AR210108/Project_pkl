<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbsensiController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Pengunjung Belum Login)
|--------------------------------------------------------------------------
*/

// Redirect default ke halaman login
Route::get('/', fn() => redirect()->route('login'));

// Rute untuk menampilkan halaman login
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Rute untuk logout, berlaku global untuk semua role yang sudah login
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| API Routes (Global untuk semua role yang sudah login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    // API untuk Absensi (digunakan oleh frontend admin)
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [AbsensiController::class, 'apiIndex'])->name('index');
        Route::get('/ketidakhadiran', [AbsensiController::class, 'apiIndexKetidakhadiran'])->name('ketidakhadiran');
        Route::post('/', [AbsensiController::class, 'apiStore'])->name('store');
        Route::post('/cuti', [AbsensiController::class, 'apiStoreCuti'])->name('store.cuti');
        Route::get('/{id}', [AbsensiController::class, 'apiShow'])->name('show');
        Route::put('/{id}', [AbsensiController::class, 'apiUpdate'])->name('update');
        Route::put('/{id}/cuti', [AbsensiController::class, 'apiUpdateCuti'])->name('update.cuti');
        Route::post('/{id}/verify', [AbsensiController::class, 'apiVerify'])->name('verify');
        Route::delete('/{id}', [AbsensiController::class, 'apiDestroy'])->name('destroy');
        Route::get('/statistics', [AbsensiController::class, 'apiStatistics'])->name('statistics');
    });
});

/*
|--------------------------------------------------------------------------
| Role-Based Routes
|--------------------------------------------------------------------------
*/

/* ----------------------------- API untuk Role Karyawan ----------------------------- */
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('api/karyawan')
    ->name('api.karyawan.')
    ->group(function () {
        // Endpoint untuk data dashboard karyawan
        Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardData'])->name('dashboard');
        // Endpoint untuk status kehadiran hari ini
        Route::get('/today-status', [KaryawanController::class, 'getTodayStatus'])->name('today');
        // Endpoint untuk riwayat absensi karyawan
        Route::get('/history', [KaryawanController::class, 'getHistory'])->name('history');

        // Endpoint untuk proses absen masuk
        Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
        // Endpoint untuk proses absen pulang
        Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');
        // Endpoint untuk submit pengajuan izin
        Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('izin');
        // Endpoint untuk submit pengajuan dinas luar
        Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('dinas');
        
        // Endpoint untuk mendapatkan status pengajuan (pending, approved, rejected)
        Route::get('/pengajuan-status', [KaryawanController::class, 'getPengajuanStatus'])->name('pengajuan.status');
    });

/* ----------------------------- Halaman untuk Role Karyawan ---------------------------- */
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        // Halaman utama (home) untuk karyawan
        Route::get('/home', [KaryawanController::class, 'home'])->name('home');
        // Halaman untuk melakukan absensi
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');

        // Halaman daftar absensi (menggunakan view langsung)
        Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
        // Halaman detail absensi (menggunakan view langsung)
        Route::get('/detail/{id}', [KaryawanController::class, 'detailPage'])->name('detail');
    });

/* --------------------------------- Rute untuk Role Admin -------------------------------- */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Halaman dashboard admin
        Route::get('/home', [AdminController::class, 'home'])->name('home');

        // Halaman untuk mengelola data karyawan
        Route::get('/data_karyawan', [AdminController::class, 'dataKaryawan'])->name('data_karyawan');

        // Halaman untuk mengelola data absensi.
        // Rute ini memanggil method 'index' di AbsensiController yang bertugas
        // menyiapkan data (statistik, absensi, dll) sebelum menampilkan view.
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

        // Halaman untuk mengelola data keuangan (menggunakan view langsung)
        Route::get('/keuangan', fn() => view('admin.keuangan'))->name('keuangan.index');

        // Grup rute untuk CRUD Layanan
        Route::prefix('layanan')->name('layanan.')->group(function () {
            Route::get('/', [LayananController::class, 'index'])->name('index');
            Route::post('/', [LayananController::class, 'store'])->name('store');
            Route::put('/{id}', [LayananController::class, 'update'])->name('update');
            Route::delete('/{id}', [LayananController::class, 'destroy'])->name('delete');
        });
    });

/*
|--------------------------------------------------------------------------
| Routes untuk Role Lain
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Halaman untuk role 'owner'
    Route::get('/owner', fn() => view('owner.index'))->name('owner.home');
    // Halaman untuk role 'pm' (project manager)
    Route::get('/pm', fn() => view('pm.index'))->name('pm.home');
});

/*
|--------------------------------------------------------------------------
| Aliases / Pintasan
|--------------------------------------------------------------------------
*/

// Pintasan ke halaman home berdasarkan role
Route::get('/karyawan', fn() => redirect()->route('karyawan.home'));
Route::get('/admin', fn() => redirect()->route('admin.home'));

// Pintasan umum yang mengarahkan user ke halaman yang tepat
Route::get('/absensi', function () {
    // Jika user sudah login dan role-nya 'admin', arahkan ke halaman admin absensi.
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.absensi.index');
    }
    // Jika bukan admin, arahkan ke halaman karyawan untuk melakukan absensi.
    return redirect()->route('karyawan.absensi.page');
});

// Pintasan lainnya
Route::get('/list', fn() => redirect()->route('karyawan.list'));
Route::get('/detail', fn() => redirect()->route('karyawan.detail'));