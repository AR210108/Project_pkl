<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Rute untuk Tamu/Pengunjung yang Belum Login)
|--------------------------------------------------------------------------
*/

// Redirect default ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// --- Rute Autentikasi (Satu untuk Semua Role) ---
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Rute untuk Pengguna yang Sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Rute Logout (berlaku untuk semua role)
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| Role-Based Routes (Rute Berdasarkan Peran)
|--------------------------------------------------------------------------
*/

// --- Kelompok Rute Karyawan (API) ---
Route::middleware(['auth', 'role:karyawan'])->prefix('api/karyawan')->name('api.karyawan.')->group(function () {
    Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardData'])->name('dashboard');
    Route::get('/today-status', [KaryawanController::class, 'getTodayStatus'])->name('today');
    Route::get('/history', [KaryawanController::class, 'getHistory'])->name('history');
    Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
    Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');
    Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('izin');
    Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('dinas');
});

// --- Kelompok Rute Karyawan (Halaman) ---
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/home', [KaryawanController::class, 'home'])->name('home');
    Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
    Route::view('/list', 'karyawan.list')->name('list');
    Route::view('/detail', 'karyawan.list_detail')->name('detail');
});

// --- Kelompok Rute Admin ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/home', [AdminController::class, 'home'])->name('home');
    
    // Data Karyawan
    Route::get('/data_karyawan', [AdminController::class, 'dataKaryawan'])->name('data_karyawan');
    
    // Data PM (tidak perlu controller, hanya menampilkan modal)
    // Route::get('/data_pm', [PMController::class, 'index'])->name('data_pm');
    
    // Data Absensi
    Route::get('/absensi', function () {
        return view('admin.absensi');
    })->name('absensi.index');
    
    // Data Keuangan
    Route::get('/keuangan', function () {
        return view('admin.keuangan');
    })->name('keuangan.index');

    // Layanan
    Route::prefix('layanan')->name('layanan.')->group(function () {
        Route::get('/', [LayananController::class, 'index'])->name('index');
        Route::post('/', [LayananController::class, 'store'])->name('store');
        Route::put('/{id}', [LayananController::class, 'update'])->name('update');
        Route::delete('/{id}', [LayananController::class, 'destroy'])->name('delete');
    });
});

// --- Kelompok Rute Lainnya ---
Route::middleware('auth')->group(function () {
    Route::get('/owner', function () {
        return view('owner.index');
    })->name('owner.home');

    Route::get('/pm', function () {
        return view('pm.index');
    })->name('pm.home');
});

/*
|--------------------------------------------------------------------------
| Aliases / Shortcuts (Rute Singkat)
|--------------------------------------------------------------------------
*/

Route::get('/karyawan', function () {
    return redirect()->route('karyawan.home');
});

Route::get('/absensi', function () {
    // Jika pengguna adalah admin, arahkan ke halaman absensi admin
    if (auth()->check() && auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.absensi.index');
    }
    // Jika bukan, arahkan ke halaman absensi karyawan
    return redirect()->route('karyawan.absensi.page');
});

Route::get('/list', function () {
    return redirect()->route('karyawan.list');
});

Route::get('/detail', function () {
    return redirect()->route('karyawan.detail');
});

Route::get('/admin', function () {
    return redirect()->route('admin.home');
});