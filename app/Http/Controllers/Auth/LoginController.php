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
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect('/admin/home');
            }

            if ($user->role === 'karyawan') {
                return redirect('/karyawan/home');
            }
        }

        return back()->with('error', 'Login gagal');
    }
}
