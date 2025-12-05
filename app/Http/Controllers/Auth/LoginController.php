<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     * Middleware 'guest' akan menangani redirect jika user sudah login.
     */
    public function show()
    {
        // Tugas controller ini hanya satu: tampilkan view.
        return view('login.login');
    }

    /**
     * Proses autentikasi user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Arahkan user ke halaman yang sesuai berdasarkan role
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.home'));
            }

            // Default untuk role 'karyawan' atau role lainnya
            return redirect()->intended(route('karyawan.home'));
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password yang Anda masukkan salah.'],
        ]);
    }
}