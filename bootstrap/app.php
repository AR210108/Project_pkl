<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // Jika Anda memiliki rute API, uncomment baris ini
        // api: __DIR__.'/../routes/api.php', 
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        /*
        |--------------------------------------------------------------------------
        | Middleware Global
        |--------------------------------------------------------------------------
        |
        | Middleware yang terdaftar di sini akan berjalan untuk setiap request
        | HTTP ke aplikasi Anda.
        |
        */

        // $middleware->append(\App\Http\Middleware\TrustProxies::class);


        /*
        |--------------------------------------------------------------------------
        | Middleware Groups
        |--------------------------------------------------------------------------
        |
        | Di sini Anda bisa mendefinisikan grup middleware. Laravel sudah
        | menyediakan grup 'web' dan 'api' secara default.
        |
        */

        // $middleware->group('web', [
        //     // Middleware untuk grup 'web' sudah dikelola oleh Laravel
        // ]);


        /*
        |--------------------------------------------------------------------------
        | Alias Middleware (Route Middleware)
        |--------------------------------------------------------------------------
        |
        | Di sinilah Anda mendaftarkan alias untuk middleware yang akan
        | digunakan di file routes/web.php. Ini adalah pengganti dari
        | array $routeMiddleware di file app/Http/Kernel.php (Laravel 10 ke bawah).
        |
        */

        $middleware->alias([
            // 'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class, // Contoh alias bawaan
            'role' => \App\Http\Middleware\RoleMiddleware::class, // Alias middleware custom Anda
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();