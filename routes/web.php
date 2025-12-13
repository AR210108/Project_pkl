<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminKaryawanController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PengumumanController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Pengunjung Belum Login)
|--------------------------------------------------------------------------
*/

// Redirect default ke halaman login
Route::get('/', fn() => redirect()->route('login'));

// Rute untuk menampilkan halaman login
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| API Routes (Global)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [AbsensiController::class, 'apiIndex'])->name('index');
        Route::get('/ketidakhadiran', [AbsensiController::class, 'apiIndexKetidakhadiran'])->name('ketidakhadiran');
        Route::post('/', [AbsensiController::class, 'apiStore'])->name('store');
        Route::post('/cuti', [AbsensiController::class, 'apiStoreCuti'])->name('store.cuti');
        Route::get('/{id}', [AbsensiController::class, 'apiShow'])->name('show');
        Route::put('/{id}', [AbsensiController::class, 'apiUpdate'])->name('update');
        Route::put('/{id}/cuti', [AbsensiController::class, 'apiUpdateCuti'])->name('update.cuti');
        Route::post('/{id}/verify', [AbsensiController::class, 'apiVerify'])->name('verify');
        Route::delete('/{id}', [AbsensiController::class, 'apiDestroy'])->name('destroy');
        Route::get('/statistics', [AbsensiController::class, 'apiStatistics'])->name('statistics');
    });
});

/*
|--------------------------------------------------------------------------
| Role-Based Routes
|--------------------------------------------------------------------------
*/

/* ------------------------ API Karyawan ------------------------ */
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

        Route::get('/pengajuan-status', [KaryawanController::class, 'getPengajuanStatus'])->name('pengajuan.status');
    });

/* ------------------------ PAGE Karyawan ------------------------ */
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        Route::get('/home', [KaryawanController::class, 'home'])->name('home');
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');
        Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
        Route::get('/detail/{id}', [KaryawanController::class, 'detailPage'])->name('detail');
    });

/* --------------------------- Admin ---------------------------- */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/home', [AdminController::class, 'home'])->name('home');

        Route::get('/data_karyawan', [AdminKaryawanController::class, 'index'])->name('data_karyawan');

        // User
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

        // Karyawan CRUD
        Route::controller(AdminKaryawanController::class)->group(function () {
            Route::get('/karyawan', 'index')->name('karyawan.index');
            Route::post('/karyawan/store', 'store')->name('karyawan.store');
            Route::post('/karyawan/update/{id}', 'update')->name('karyawan.update');
            Route::delete('/karyawan/delete/{id}', 'destroy')->name('karyawan.delete');
        });

        // Absensi admin
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

        // Keuangan
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
| Aliases / Pintasan
|--------------------------------------------------------------------------
*/

// Role-based redirect
Route::get('/karyawan', fn() => redirect()->route('karyawan.home'));
Route::get('/admin', fn() => redirect()->route('admin.home'));

Route::get('/absensi', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.absensi.index');
    }
    return redirect()->route('karyawan.absensi.page');
});

Route::get('/list', fn() => redirect()->route('karyawan.list'));
Route::get('/detail', fn() => redirect()->route('karyawan.detail'));

/*
|--------------------------------------------------------------------------
| Banyak Route View Langsung (admin, pemilik, finance, dsb)
|--------------------------------------------------------------------------
*/

Route::view('/admin', 'admin/home');
Route::view('/data_karyawan_admin', 'admin/data_karyawan');
Route::view('/data_layanan_admin', 'admin/data_layanan');
Route::get('/data_user', fn() => redirect()->route('admin.user'));

Route::view('/data_absen', 'admin/absensi');
Route::view('/template_surat', 'admin/templet_surat');
Route::view('/list_surat', 'admin/list_surat');
Route::view('/invoice', 'admin/invoice');
Route::view('/kwitansi', 'admin/kwitansi');
Route::view('/catatan_rapat', 'admin/catatan_rapat');
Route::view('/pengumuman', 'admin/pengumuman');

Route::view('/pemilik', 'pemilik/home');
Route::view('/rekap_absen', 'pemilik/rekap_absen');
Route::view('/laporan', 'pemilik/laporan');
Route::view('/monitoring', 'pemilik/monitoring_progres');
Route::view('/surat', 'pemilik/surat_kerjasama');

Route::view('/finance', 'finance/beranda');
Route::view('/data', 'finance/data_layanan');
Route::view('/pembayaran', 'finance/data_pembayaran');
Route::view('/data_in_out', 'finance/data_in_out');

Route::view('/manager_divisi', 'manager_divisi/home');
Route::view('/pengelola_tugas', 'manager_divisi/pengelola_tugas');

Route::view('/general_manajer', 'general_manajer/home');
Route::view('/data_karyawan', 'general_manajer/data_karyawan');
Route::view('/layanan', 'general_manajer/data_layanan');
Route::view('/kelola_tugas', 'general_manajer/kelola_tugas');
Route::view('/kelola_absen', 'general_manajer/kelola_absen');

Route::resource('invoices', InvoiceController::class);
Route::get('/invoices/{invoice}/print', function (Invoice $invoice) {
    return view('invoices.print', compact('invoice'));
})->name('invoices.print');


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


Route::resource('pengumuman', PengumumanController::class);
