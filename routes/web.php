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
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Pengunjung Belum Login)
|--------------------------------------------------------------------------
*/


// Redirect default ke halaman home
Route::get('/', function () {
    return view('home');
})->name('home');

// Rute untuk menampilkan halaman login
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Rute untuk logout, berlaku global untuk semua role yang sudah login
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| API Routes (Global untuk semua role yang sudah login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    // API untuk Absensi (digunakan oleh frontend admin)
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

    // API untuk Kwitansi
    Route::apiResource('kwitansi', KwitansiController::class);
    
    // API untuk Invoice
    Route::apiResource('invoices', InvoiceController::class);
});

/*
|--------------------------------------------------------------------------
| Role-Based Routes
|--------------------------------------------------------------------------
*/

/* ----------------------------- API untuk Role Karyawan ----------------------------- */
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('api/karyawan')
    ->name('api.karyawan.')
    ->group(function () {
        // Endpoint untuk data dashboard karyawan
        Route::get('/dashboard-data', [KaryawanController::class, 'getDashboardData'])->name('dashboard');
        // Endpoint untuk status kehadiran hari ini
        Route::get('/today-status', [KaryawanController::class, 'getTodayStatus'])->name('today');
        // Endpoint untuk riwayat absensi karyawan
        Route::get('/history', [KaryawanController::class, 'getHistory'])->name('history');

        // Endpoint untuk proses absen masuk
        Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('absen.masuk');
        // Endpoint untuk proses absen pulang
        Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('absen.pulang');
        // Endpoint untuk submit pengajuan izin
        Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('izin');
        // Endpoint untuk submit pengajuan dinas luar
        Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('dinas');
        
        // Endpoint untuk mendapatkan status pengajuan (pending, approved, rejected)
        Route::get('/pengajuan-status', [KaryawanController::class, 'getPengajuanStatus'])->name('pengajuan.status');
    });

/* ----------------------------- Halaman untuk Role Karyawan ---------------------------- */
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        // Halaman utama (home) untuk karyawan
        Route::get('/home', [KaryawanController::class, 'home'])->name('home');
        // Halaman untuk melakukan absensi
        Route::get('/absensi', [KaryawanController::class, 'absensiPage'])->name('absensi.page');

        // Halaman daftar absensi (menggunakan view langsung)
        Route::get('/list', [KaryawanController::class, 'listPage'])->name('list');
        // Halaman detail absensi (menggunakan view langsung)
        Route::get('/detail/{id}', [KaryawanController::class, 'detailPage'])->name('detail');
    });

/* --------------------------------- Rute untuk Role Admin -------------------------------- */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Halaman dashboard admin
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

        // Halaman untuk mengelola data absensi.
        // Rute ini memanggil method 'index' di AbsensiController yang bertugas
        // menyiapkan data (statistik, absensi, dll) sebelum menampilkan view.
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

        // Halaman untuk mengelola data keuangan (menggunakan view langsung)
        Route::get('/keuangan', fn() => view('admin.keuangan'))->name('keuangan.index');

        // Grup rute untuk CRUD Layanan
        Route::prefix('layanan')->name('layanan.')->group(function () {
            Route::get('/', [LayananController::class, 'index'])->name('index');
            Route::post('/', [LayananController::class, 'store'])->name('store');
            Route::put('/{id}', [LayananController::class, 'update'])->name('update');
            Route::delete('/{id}', [LayananController::class, 'destroy'])->name('delete');
        });
        
        // Grup rute untuk CRUD Invoice
        Route::prefix('invoice')->name('invoice.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/create', [InvoiceController::class, 'create'])->name('create');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
        });
          Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            Route::get('/', fn() => view('admin.kwitansi'))->name('index');
            Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
        });
    });

/*
|--------------------------------------------------------------------------
| Routes untuk Role Lain
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Halaman untuk role 'owner'
    Route::get('/owner', fn() => view('owner.index'))->name('owner.home');
    // Halaman untuk role 'pm' (project manager)
    Route::get('/pm', fn() => view('pm.index'))->name('pm.home');
});

/*
|--------------------------------------------------------------------------
| Aliases / Pintasan
|--------------------------------------------------------------------------
*/

// Pintasan ke halaman home berdasarkan role
Route::get('/karyawan', fn() => redirect()->route('karyawan.home'));
Route::get('/admin', fn() => redirect()->route('admin.home'));

// Pintasan umum yang mengarahkan user ke halaman yang tepat
Route::get('/absensi', function () {
    // Jika user sudah login dan role-nya 'admin', arahkan ke halaman admin absensi.
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.absensi.index');
    }
    // Jika bukan admin, arahkan ke halaman karyawan untuk melakukan absensi.
    return redirect()->route('karyawan.absensi.page');
});

// Pintasan lainnya
Route::get('/list', fn() => redirect()->route('karyawan.list'));
Route::get('/detail', fn() => redirect()->route('karyawan.detail'));

// Halaman-halaman lain yang tidak memerlukan controller
Route::middleware('auth')->group(function () {
    // Admin
    Route::get('/data_karyawan_admin', fn() => view('admin/data_karyawan'));
    Route::get('/data_layanan_admin', fn() => view('admin/data_layanan'));
    Route::get('/data_user', fn() => redirect()->route('admin.user'));
    Route::get('/data_absen', fn() => view('admin/absensi'));
    Route::get('/template_surat', fn() => view('admin/templet_surat'));
    Route::get('/list_surat', fn() => view('admin/list_surat'));
    Route::get('/invoice', fn() => view('admin/invoice'));
    Route::get('/kwitansi', fn() => view('admin/kwitansi'));
    Route::get('/catatan_rapat', fn() => view('admin/catatan_rapat'));
    Route::get('/pengumuman', fn() => view('admin/pengumuman'));
    Route::get('/data_order', fn() => view('admin/data_order'));
    
    // Pemilik
    Route::get('/pemilik', fn() => view('pemilik/home'));
    Route::get('/rekap_absen', fn() => view('pemilik/rekap_absen'));
    Route::get('/laporan', fn() => view('pemilik/laporan'));
    Route::get('/monitoring', fn() => view('pemilik/monitoring_progres'));
    Route::get('/surat', fn() => view('pemilik/surat_kerjasama'));
    
    // Finance
    Route::get('/finance', fn() => view('finance/beranda'));
    Route::get('/data', fn() => view('finance/data_layanan'));
    Route::get('/pembayaran', fn() => view('finance/data_pembayaran'));
    Route::get('/data_in_out', fn() => view('finance/data_in_out'));
    
    // Manager Divisi
    Route::get('/manager_divisi', fn() => view('manager_divisi/home'));
    Route::get('/pengelola_tugas', fn() => view('manager_divisi/pengelola_tugas'));
    
    // General Manager
    Route::get('/general_manajer', fn() => view('general_manajer/home'));
    Route::get('/data_karyawan', fn() => view('general_manajer/data_karyawan'));
    Route::get('/layanan', fn() => view('general_manajer/data_layanan'));
    Route::get('/kelola_tugas', fn() => view('general_manajer/kelola_tugas'));
    Route::get('/kelola_absen', fn() => view('general_manajer/kelola_absen'));
    Route::get('/kelola_order', fn() => view('general_manajer/kelola_order'));
  
});
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
// === ROUTE UNTUK CATATAN RAPAT ANDA ===
// Semua route di dalam group ini memerlukan user sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/catatan_rapat', [CatatanRapatController::class, 'index'])->name('catatan_rapat.index');
    Route::post('/catatan_rapat', [CatatanRapatController::class, 'store'])->name('catatan_rapat.store');
    Route::put('/catatan_rapat/{catatanRapat}', [CatatanRapatController::class, 'update'])->name('catatan_rapat.update');
    Route::delete('/catatan_rapat/{catatanRapat}', [CatatanRapatController::class, 'destroy'])->name('catatan_rapat.destroy');
});

