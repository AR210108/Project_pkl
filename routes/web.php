<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\Auth\LoginController;

// Halaman login
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

// Redirect default
Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman umum
Route::get('/home', function () {
    return view('home');
})->middleware('auth');

// --- Kelompok Rute Karyawan ---
Route::middleware(['auth', 'role:karyawan'])->group(function () {

    // Halaman (return view)
    Route::get('/karyawan/home', [KaryawanController::class, 'home'])->name('karyawan.home');
    Route::get('/karyawan/absensi', [KaryawanController::class, 'absensiPage'])->name('karyawan.absen.page');
    Route::view('/karyawan', 'karyawan.home');
    Route::view('/absensi', 'karyawan.absen');
    Route::view('/list', 'karyawan.list');
    Route::view('/detail', 'karyawan.list_detail');

    // API untuk JavaScript (return JSON)
    Route::prefix('api/karyawan')->group(function () {
        // PERBAIKAN: Ini adalah rute yang sebelumnya HILANG
        Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardData']);
        
        Route::get('/today-status', [KaryawanController::class, 'getTodayStatus']);
        Route::get('/history', [KaryawanController::class, 'getHistory']);
        Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi']);
        Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi']);
        Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi']);
        Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi']);
    });
});

// Logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});

// --- Kelompok Rute Admin ---
// REKOMENDASI: Tambahkan middleware untuk keamanan
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin/home');
    });
    Route::get('/data_karyawan', function () {
        return view('admin/data_karyawan');
    });
    Route::get('/data_layanan', function () {
        return view('admin/data_layanan');
    });
    Route::get('/data_absen', function () {
        return view('admin/absensi');
    });
});