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
use App\Http\Controllers\CatatanRapatController;
use App\Http\Controllers\DataProjectController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SuratKerjasamaController;
use App\Http\Controllers\PelayananController;
use App\Http\Controllers\TugasController;
use App\Models\Invoice;
use App\Http\Controllers\GeneralManajer\OrderController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Pengunjung Belum Login)
|--------------------------------------------------------------------------
*/
Route::get('/users/data', [UserController::class, 'data'])
    ->middleware('auth');
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

        // Routes untuk AdminKaryawanController
        Route::controller(AdminKaryawanController::class)->group(function () {
            Route::get('/karyawan', 'index')->name('karyawan.index');
            Route::post('/karyawan/store', 'store')->name('karyawan.store');
            Route::post('/karyawan/update/{id}', 'update')->name('karyawan.update');
            Route::delete('/karyawan/delete/{id}', 'destroy')->name('karyawan.delete');
        });

        // Halaman untuk mengelola data absensi
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

        // Grup rute untuk CRUD Surat Kerjasama
        Route::prefix('surat_kerjasama')->name('surat_kerjasama.')->group(function () {
            Route::get('/', [SuratKerjasamaController::class, 'index'])->name('index');
            Route::get('/create', [SuratKerjasamaController::class, 'create'])->name('create');
            Route::post('/', [SuratKerjasamaController::class, 'store'])->name('store');
            Route::get('/surat_kerjasama/{id}', [SuratKerjasamaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SuratKerjasamaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SuratKerjasamaController::class, 'update'])->name('update');
            Route::delete('/{id}', [SuratKerjasamaController::class, 'destroy'])->name('destroy');
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

        // ✅ PERBAIKAN: Grup rute untuk Kwitansi (dipindahkan ke dalam grup admin)
        Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            Route::get('/', fn() => view('admin.kwitansi'))->name('index');
            Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
        });
    });

/*
|--------------------------------------------------------------------------
| Routes untuk Pengumuman
|--------------------------------------------------------------------------
*/
Route::resource('pengumuman', PengumumanController::class);
Route::get('/pengumuman/data', [PengumumanController::class, 'data']);

// Resource routes untuk Pegawai (mendefinisikan pegawai.index, pegawai.store, dll.)
Route::resource('pegawai', PegawaiController::class);

/*
|--------------------------------------------------------------------------
| Routes untuk Catatan Rapat
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // view
    Route::get('/catatan_rapat', [CatatanRapatController::class, 'index'])
        ->name('catatan_rapat');

    // data (JSON)
    Route::get('/catatan_rapat/data', [CatatanRapatController::class, 'data'])
        ->name('catatan_rapat.data');

    Route::post('/catatan_rapat', [CatatanRapatController::class, 'store'])
        ->name('catatan_rapat.store');

    Route::put('/catatan_rapat/{catatanRapat}', [CatatanRapatController::class, 'update'])
        ->name('catatan_rapat.update');

    Route::delete('/catatan_rapat/{catatanRapat}', [CatatanRapatController::class, 'destroy'])
        ->name('catatan_rapat.destroy');

        Route::get('/catatan_rapat/data', [CatatanRapatController::class, 'data'])
    ->name('catatan_rapat.data');

});

/*
|--------------------------------------------------------------------------
| Pintasan Routes
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
    Route::get('/data_order', fn() => view('admin/data_order'));
    // Orderan Routes
Route::resource('project', DataProjectController::class)->names([
    'index' => 'project.index',
    'store' => 'project.store',
    'show' => 'project.show',
    'update' => 'project.update',
    'destroy' => 'project.destroy'
]);
    
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
    Route::get('/kwetansi', fn() => view('finance/kwitansi'));
    
    
    // Manager Divisi
    Route::get('/manager_divisi', fn() => view('manager_divisi/home'));
    Route::get('/pengelola_tugas', fn() => view('manager_divisi/pengelola_tugas'));
    
    // General Manager
    Route::get('/general_manajer', fn() => view('general_manajer/home'));
    Route::get('/data_karyawan', fn() => view('general_manajer/data_karyawan'));
    Route::get('/layanan', fn() => view('general_manajer/data_layanan'));
    Route::get('/kelola_tugas', fn() => view('general_manajer/kelola_tugas'));
    Route::get('/kelola_absen', fn() => view('general_manajer/kelola_absen'));
    // Route untuk General Manajer - Orderan
Route::prefix('general_manajer')->name('general_manajer.')->group(function () {
    Route::get('/orderan', [OrderController::class, 'index'])->name('orderan.index');
    Route::post('/orderan', [OrderController::class, 'store'])->name('orderan.store');
    Route::put('/orderan/{id}', [OrderController::class, 'update'])->name('orderan.update');
    Route::delete('/orderan/{id}', [OrderController::class, 'destroy'])->name('orderan.destroy');
});
  
});
/*
|--------------------------------------------------------------------------
| Banyak Route View Langsung (admin, pemilik, finance, dsb)
|--------------------------------------------------------------------------
*/
Route::view('/data_karyawan_admin', 'admin/data_karyawan');
Route::view('/data_layanan_admin', 'admin/data_layanan');
Route::get('/data_user', fn() => redirect()->route('admin.user'));

/*
|--------------------------------------------------------------------------
| Routes untuk Invoice (Public/Resource)
|--------------------------------------------------------------------------
*/
Route::resource('invoices', InvoiceController::class);
Route::get('/invoices/{invoice}/print', function (Invoice $invoice) {
    return view('invoices.print', compact('invoice'));
})->name('invoices.print');

/*
|--------------------------------------------------------------------------
| Routes untuk Role Pemilik
|--------------------------------------------------------------------------
*/
Route::get('/pemilik', function () { 
    return view('pemilik/home');
});
Route::get('/rekap_absen', function () {
    return view('pemilik/rekap_absen');
});
Route::get('/laporan', function () {
    return view('pemilik/laporan');
});

/*
|--------------------------------------------------------------------------
| Routes untuk Finance
|--------------------------------------------------------------------------
*/
Route::get('/finance', function () {
    return view('finance/beranda');
});
Route::get('/data', function () {
    return view('finance/data_layanan');
});
Route::get('/pembayaran', function () {
    return view('finance/data_pembayaran');
});
Route::get('/pemasukan', function () {
    return view('finance/pemasukan');
});
Route::get('/pengeluaran', function () {
    return view('finance/pengeluaran');
});
Route::get('/finance/invoice', function () {
    return view('finance/invoice');
});
Route::get('/pembayaran', function () {
    return view('finance/data_pembayaran');
});
Route::get('/karyawann', function () {
    return view('finance/daftar_karyawan');
});

/*
|--------------------------------------------------------------------------
| Routes untuk Manager Divisi
|--------------------------------------------------------------------------
*/
Route::get('/manager_divisi', function () {
    return view('manager_divisi/home');
});
Route::get('/pengelola_tugas', function () {
    return view('manager_divisi/pengelola_tugas');
});


    Route::get('/manager_divisi', function () {
        return view('manager_divisi/home');
    });
    Route::get('/pengelola_tugas', action: function () {
        return view('manager_divisi/pengelola_tugas');
    });
    Route::get('/data_order', function () {
        return view('manager_divisi/data_order');
    });
    Route::get('/daftar_karyawan', function () {
        return view('manager_divisi/daftar_karyawan');
    });
    Route::get('/kelola_absensi', function () {
        return view('manager_divisi/kelola_absensi');
    });

Route::get('/general_manajer', function () {
    return view('general_manajer/home');
});
Route::get('/data_karyawan', function () {
    return redirect()->route('pegawai.index');
});
Route::get('/layanan', [PelayananController::class, 'index']);

Route::get('/kelola_order', function () {
    return view('general_manajer/kelola_order');
});
Route::get('/kelola_tugas', [TugasController::class, 'index'])->name('tugas.page');
Route::get('/kelola_absen', function () {
    return view('general_manajer/kelola_absen');
});

// routes untuk pelayanan
Route::post('/layanan', [PelayananController::class, 'store']);
Route::put('/layanan/{id}', [PelayananController::class, 'update']);
Route::delete('/layanan/{id}', [PelayananController::class, 'destroy']);

Route::resource('pengumuman', PengumumanController::class);
// === ROUTE UNTUK CATATAN RAPAT ANDA ===
// Semua route di dalam group ini memerlukan user sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/catatan_rapat', [CatatanRapatController::class, 'index'])->name('catatan_rapat.index');
    Route::post('/catatan_rapat', [CatatanRapatController::class, 'store'])->name('catatan_rapat.store');
    Route::put('/catatan_rapat/{catatanRapat}', [CatatanRapatController::class, 'update'])->name('catatan_rapat.update');
    Route::delete('/catatan_rapat/{catatanRapat}', [CatatanRapatController::class, 'destroy'])->name('catatan_rapat.destroy');
});

// Rekap
Route::get('/kelola_absen', [AbsensiController::class, 'kelolaAbsen'])->name('kelola.absen');
Route::get('/rekap_absensi', [AbsensiController::class, 'rekapAbsensi'])->name('rekap.absensi');

//Buat Tugas
// Tambahkan route untuk tugas
