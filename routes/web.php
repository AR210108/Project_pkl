    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\KaryawanController;
    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\Auth\AdminLoginController;

    //
    Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.process');

    Route::middleware(['auth'])->group(function () {
    Route::get('/admin/home', function () {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda bukan Admin');
        }
        return view('admin.home');
    })->name('admin.home');
});

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

    Route::get('/data_karyawan', function () {
        return view('admin/data_karyawan');
    });
    Route::get('/data_layanan', function () {
        return view('admin/data_layanan');
    });
    Route::get('/data_absen', function () {
        return view('admin/absensi');
    });
// routes/web.php

// ... (kode Anda yang lain) ...

// Route khusus untuk API yang dipanggil oleh JavaScript
Route::middleware(['auth', 'role:karyawan'])->prefix('api/karyawan')->group(function () {
    // Untuk mengambil data status hari ini
    Route::get('/today-status', [KaryawanController::class, 'getTodayStatus'])->name('karyawan.api.today');
    
    // Untuk mengambil data riwayat absensi
    Route::get('/history', [KaryawanController::class, 'getHistory'])->name('karyawan.api.history');

    // Untuk proses absen
    Route::post('/absen-masuk', [KaryawanController::class, 'absenMasukApi'])->name('karyawan.api.absen.masuk');
    Route::post('/absen-pulang', [KaryawanController::class, 'absenPulangApi'])->name('karyawan.api.absen.pulang');
    
    // Untuk pengajuan izin dan dinas
    Route::post('/submit-izin', [KaryawanController::class, 'submitIzinApi'])->name('karyawan.api.izin');
    Route::post('/submit-dinas', [KaryawanController::class, 'submitDinasApi'])->name('karyawan.api.dinas');
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

Route::get('/owner', function () {
    return view('owner/index');
});
Route::get('/pm', function () {
    return view('pm/index');
});

// Tambahkan route untuk logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});
