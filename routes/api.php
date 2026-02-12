<?php

use Illuminate\Support\Facades\Route;

// Kosongkan saja, semua pindah ke web.php
Route::get('/test', function() {
    return response()->json(['message' => 'API is working']);
});