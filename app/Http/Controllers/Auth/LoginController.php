<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Debug log
            \Log::info('Login successful', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role,
                'redirect_to' => match($user->role) {
                    'admin', 'finance' => route("{$user->role}.beranda"),
                    'karyawan', 'general_manager', 'manager_divisi', 'owner' => route("{$user->role}.home"),
                    default => route('login')
                }
            ]);
            
            // Redirect berdasarkan role
            if (in_array($user->role, ['admin', 'finance'])) {
                return redirect()->route("{$user->role}.beranda");
            } else {
                return redirect()->route("{$user->role}.home");
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}