<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminKaryawanController;

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

        Route::get('/home', [AdminController::class, 'home'])->name('home');

        // USER LIST
        Route::get('/user', [UserController::class, 'index'])->name('user');

        // STORE USER
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');

        // UPDATE USER
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');

        // HAPUS USER
        Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
    
        // Data Karyawan
       Route::get('/data_karyawan', [AdminKaryawanController::class, 'index'])->name('data_karyawan');
        
        // Routes untuk AdminKaryawanController - PERBAIKAN
        Route::controller(AdminKaryawanController::class)->group(function () {
            Route::get('/karyawan', 'index')->name('karyawan.index');
            Route::post('/karyawan/store', 'store')->name('karyawan.store');
            Route::post('/karyawan/update/{id}', 'update')->name('karyawan.update');
            Route::delete('/karyawan/delete/{id}', 'destroy')->name('karyawan.delete');
        });

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
    return redirect()->route('admin.user');
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