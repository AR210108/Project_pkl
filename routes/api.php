<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\KwitansiController;

// Dalam routes/api.php, semua route OTOMATIS mendapat prefix 'api'
// Jadi jangan tambahkan 'api/' di depan route

// Route untuk testing API access (tanpa auth untuk debug)
Route::get('/test', function() {
    return response()->json([
        'success' => true,
        'message' => 'API is accessible',
        'time' => now()->toDateTimeString()
    ]);
});

// API routes untuk invoices - WITH SESSION AUTH
// User harus login (session based)
Route::middleware(['web', 'auth'])->group(function () {
    
    Route::prefix('invoices')->group(function () {
        // GET /api/invoices
        Route::get('/', [InvoiceApiController::class, 'index'])->name('api.invoices.index');
        
        // POST /api/invoices
        Route::post('/', [InvoiceApiController::class, 'store'])->name('api.invoices.store');
        
        // GET /api/invoices/{id}
        Route::get('/{id}', [InvoiceApiController::class, 'show'])->name('api.invoices.show');
        
        // GET /api/invoices/{id}/edit
        Route::get('/{id}/edit', [InvoiceApiController::class, 'edit'])->name('api.invoices.edit');
        
        // PUT /api/invoices/{id}
        Route::put('/{id}', [InvoiceApiController::class, 'update'])->name('api.invoices.update');
        
        // DELETE /api/invoices/{id}
        Route::delete('/{id}', [InvoiceApiController::class, 'destroy'])->name('api.invoices.destroy');
    });
});

// API routes untuk kwitansi - WITHOUT AUTH (Debug)
Route::prefix('kwitansi')->group(function () {
    // GET /api/kwitansi
    Route::get('/', [KwitansiController::class, 'index'])->name('api.kwitansi.index');
    
    // POST /api/kwitansi
    Route::post('/', [KwitansiController::class, 'store'])->name('api.kwitansi.store');
    
    // GET /api/kwitansi/{id}
    Route::get('/{id}', [KwitansiController::class, 'show'])->name('api.kwitansi.show');
    
    // PUT /api/kwitansi/{id}
    Route::put('/{id}', [KwitansiController::class, 'update'])->name('api.kwitansi.update');
    
    // DELETE /api/kwitansi/{id}
    Route::delete('/{id}', [KwitansiController::class, 'destroy'])->name('api.kwitansi.destroy');
});

// Route untuk testing auth
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/auth-test', function() {
        return response()->json([
            'success' => true,
            'user' => auth()->user()->only(['id', 'name', 'email', 'role']),
            'message' => 'Authenticated API access'
        ]);
    });
});

// Debug route untuk melihat semua routes
Route::get('/debug-all-routes', function() {
    $routes = [];
    foreach (Route::getRoutes() as $route) {
        $routes[] = [
            'uri' => $route->uri,
            'methods' => $route->methods,
            'name' => $route->getName(),
            'action' => $route->getActionName()
        ];
    }
    
    return response()->json([
        'total_routes' => count($routes),
        'routes' => $routes
    ]);
});
