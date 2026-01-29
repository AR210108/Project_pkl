<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceApiController;

/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
| Semua route di sini otomatis punya prefix /api
| Contoh: POST /api/invoices
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceApiController::class, 'index']);       // GET  /api/invoices
        Route::post('/', [InvoiceApiController::class, 'store']);      // POST /api/invoices
        Route::get('/{id}', [InvoiceApiController::class, 'show']);    // GET  /api/invoices/{id}
        Route::put('/{id}', [InvoiceApiController::class, 'update']);  // PUT  /api/invoices/{id}
        Route::delete('/{id}', [InvoiceApiController::class, 'destroy']); // DELETE /api/invoices/{id}
    });

});
