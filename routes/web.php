<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\Auth\LoginController;


// Halaman login
Route::get('/login', [LoginController::class, 'show'])->name('login');

// Proses login
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');


// Redirect default ke login
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

    Route::get('/karyawan', function () {
        return view('karyawan/beranda');
    });

    Route::get('/absensi', function () {
        return view('karyawan/absen');
    });

    Route::get('/list', function () {
        return view('karyawan/list');
    });

    Route::get('/detail', function () {
        return view('karyawan/list_detail');
    });
    Route::view('/karyawan', 'karyawan.home');
Route::view('/absensi', 'karyawan.absen');
Route::view('/list', 'karyawan.list');
Route::view('/detail', 'karyawan.list_detail');
});
