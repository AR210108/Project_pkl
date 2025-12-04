<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Rute untuk Tamu/Pengunjung)
|--------------------------------------------------------------------------
*/

// Redirect default ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// --- Rute Autentikasi Karyawan ---
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

// --- Rute Autentikasi Admin ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.process');
});

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

// --- Kelompok Rute Karyawan ---
// Middleware: auth (harus login) dan role:karyawan (harus berperan sebagai karyawan)
// Prefix: /karyawan (semua rute di dalamnya akan diawali /karyawan)
// Name: karyawan. (semua nama rute di dalamnya akan diawali karyawan.)
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {

    // --- Halaman Utama ---
    Route::get('/home', [KaryawanController::class, 'home'])->name('home');
    Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
    Route::view('/list', 'karyawan.list')->name('list');
    Route::view('/detail', 'karyawan.list_detail')->name('detail');

    // --- Rute API untuk Dashboard (dipanggil oleh JavaScript) ---
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardData'])->name('dashboard');
        Route::get('/today-status', [KaryawanController::class, 'getTodayStatus'])->name('today');
        Route::get('/history', [KaryawanController::class, 'getHistory'])->name('history');
        Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
        Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');
        Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('izin');
        Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('dinas');
    });
});

// --- Kelompok Rute Admin ---
// Middleware: auth dan role:admin
// Prefix: /admin
// Name: admin.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // --- Halaman Utama Admin ---
    Route::get('/home', function () {
        return view('admin.home');
    })->name('home');

    // --- Halaman Data ---
    Route::get('/data_karyawan', function () {
        return view('admin.data_karyawan');
    })->name('data.karyawan');

    Route::get('/data_absen', function () {
        return view('admin.absensi');
    })->name('data.absen');

    // --- Rute untuk Manajemen Layanan (menggunakan controller) ---
    Route::prefix('layanan')->name('layanan.')->group(function () {
        Route::get('/', [LayananController::class, 'index'])->name('index');
        Route::post('/', [LayananController::class, 'store'])->name('store');
        Route::put('/{id}', [LayananController::class, 'update'])->name('update');
        Route::delete('/{id}', [LayananController::class, 'destroy'])->name('delete');
    });
});

// --- Kelompok Rute Lainnya (Owner, Project Manager, dll.) ---
// Middleware: auth (bisa diakses semua role yang login)
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

// Rute singkat untuk kemudahan akses (opsional)
Route::get('/karyawan', function () {
    return redirect()->route('karyawan.home');
});

Route::get('/absensi', function () {
    return redirect()->route('karyawan.absensi.page');
});

Route::get('/list', function () {
    return redirect()->route('karyawan.list');
});

Route::get('/detail', function () {
    return redirect()->route('karyawan.detail');
});