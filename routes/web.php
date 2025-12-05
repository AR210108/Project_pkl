<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Pengunjung Belum Login)
|--------------------------------------------------------------------------
*/

// Redirect default ke halaman login
Route::get('/', fn() => redirect()->route('login'));

// Autentikasi
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Logout global
    Route::post('/logout', function () {
        auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| Role-Based Routes
|--------------------------------------------------------------------------
*/

/* ----------------------------- API Karyawan ----------------------------- */
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('api/karyawan')
    ->name('api.karyawan.')
    ->group(function () {

        Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardData'])->name('dashboard');
        Route::get('/today-status', [KaryawanController::class, 'getTodayStatus'])->name('today');
        Route::get('/history', [KaryawanController::class, 'getHistory'])->name('history');

        Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
        Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');

        Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('izin');
        Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('dinas');
    });

/* ----------------------------- Page Karyawan ---------------------------- */
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        Route::get('/home', [KaryawanController::class, 'home'])->name('home');
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');

        Route::view('/list', 'karyawan.list')->name('list');
        Route::view('/detail', 'karyawan.list_detail')->name('detail');
    });

/* --------------------------------- Admin -------------------------------- */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/home', [AdminController::class, 'home'])->name('home');

        // Data Karyawan
        Route::get('/data_karyawan', [AdminController::class, 'dataKaryawan'])->name('data_karyawan');

        // Data Absensi
        Route::get('/absensi', fn() => view('admin.absensi'))->name('absensi.index');

        // Data Keuangan
        Route::get('/keuangan', fn() => view('admin.keuangan'))->name('keuangan.index');

        // Layanan CRUD
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

    Route::get('/owner', fn() => view('owner.index'))->name('owner.home');

    Route::get('/pm', fn() => view('pm.index'))->name('pm.home');
});

/*
|--------------------------------------------------------------------------
| Aliases / Shortcuts
|--------------------------------------------------------------------------
*/

Route::get('/karyawan', fn() => redirect()->route('karyawan.home'));

Route::get('/absensi', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.absensi.index');
    }
    return redirect()->route('karyawan.absensi.page');
});

Route::get('/list', fn() => redirect()->route('karyawan.list'));
Route::get('/detail', fn() => redirect()->route('karyawan.detail'));

Route::get('/admin', fn() => redirect()->route('admin.home'));
