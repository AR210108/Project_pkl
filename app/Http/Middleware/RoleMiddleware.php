<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. PERIKSA: Apakah user sudah login?
        if (!Auth::check()) {
            // Jika belum, redirect ke halaman login dengan pesan
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // 2. PERIKSA: Apakah role user ada di dalam daftar role yang diizinkan?
        if (!in_array(Auth::user()->role, $roles)) {
            // Jika role-nya tidak sesuai, tampilkan error 403 yang lebih jelas
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // 3. LANJUTKAN: Jika semua pengecekan lolos, lanjutkan request
        return $next($request);
    }
}