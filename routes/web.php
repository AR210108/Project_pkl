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

// Logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});
