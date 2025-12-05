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

// Halaman karyawan
Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/karyawan/home', [KaryawanController::class, 'home'])
        ->name('karyawan.home');

    Route::view('/karyawan', 'karyawan.home');
    Route::view('/absensi', 'karyawan.absen');
    Route::view('/list', 'karyawan.list');
    Route::view('/detail', 'karyawan.list_detail');
});

Route::middleware(['auth', 'role:karyawan'])->group(function () {

    Route::get('/karyawan/home', [KaryawanController::class, 'home'])->name('karyawan.home');

    Route::get('/karyawan/absensi', [KaryawanController::class, 'absensiPage'])->name('karyawan.absen.page');

    Route::post('/karyawan/absen-masuk', [KaryawanController::class, 'absenMasuk'])->name('karyawan.absen.masuk');

    Route::post('/karyawan/absen-pulang', [KaryawanController::class, 'absenPulang'])->name('karyawan.absen.pulang');
});


// Logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});


Route::get('/admin', function () {
    return view('admin/home');
});

Route::get('/data_karyawan_admin', function () {
    return view('admin/data_karyawan');
});
Route::get('/data_layanan_admin', function () {
    return view('admin/data_layanan');
});
Route::get('/data_user', function () {
    return view('admin/user');
});
Route::get('/data_absen', function () {
    return view('admin/absensi');
});
Route::get('/template_surat', function () {
    return view('admin/templet_surat');
});
Route::get('/list_surat', function () {
    return view('admin/list_surat');
});
Route::get('/invoice', function () {
    return view('admin/invoice');
});
Route::get('/kwitansi', function () {
    return view('admin/kwitansi');
});
Route::get('/catatan_rapat', function () {
    return view('admin/catatan_rapat');
});
Route::get('/pengumuman', function () {
    return view('admin/pengumuman');
});


Route::get('/pemilik', function () {
    return view('pemilik/home');
});
Route::get('/rekap_absen', function () {
    return view('pemilik/rekap_absen');
});
Route::get('/laporan', function () {
    return view('pemilik/laporan');
});
Route::get('/monitoring', function () {
    return view('pemilik/monitoring_progres');
});
Route::get('/surat', function () {
    return view('pemilik/surat_kerjasama');
});


// finance
Route::get('/finance', function () {
    return view('finance/beranda');
});
Route::get('/data', function () {
    return view('finance/data_layanan');
});
Route::get('/pembayaran', function () {
    return view('finance/data_pembayaran');
});
Route::get('/data_in_out', function () {
    return view('finance/data_in_out');
});



Route::get('/manager_divisi', function () {
    return view('manager_divisi/home');
});
Route::get('/pengelola_tugas', function () {
    return view('manager_divisi/pengelola_tugas');
});


Route::get('/general_manajer', function () {
    return view('general_manajer/home');
});
Route::get('/data_karyawan', function () {
    return view('general_manajer/data_karyawan');
});
Route::get('/layanan', function () {
    return view('general_manajer/data_layanan');
});
Route::get('/kelola_tugas', function () {
    return view('general_manajer/kelola_tugas');
});
Route::get('/kelola_absen', function () {
    return view('general_manajer/kelola_absen');
});