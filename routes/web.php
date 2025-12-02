<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login/login');
});

Route::get('/home', function () {
    return view('home');
});
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