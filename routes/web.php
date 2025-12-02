<?php

use Illuminate\Support\Facades\Route;

// Route yang sudah ada
Route::get('/', function () {
    return view('login/login');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/karyawan', function () {
    return view('karyawan/home');
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

// Tambahkan route untuk logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

Route::get('/admin', function () {
    return view('admin/home');
});