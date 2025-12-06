<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- TAMBAHAN penting

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user', compact('users'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'role' => 'required',
            'password' => 'required|min:5'
        ]);

        User::create([
            'name' => $r->name,
            'email' => $r->email,
            'role' => $r->role,
            'password' => Hash::make($r->password)  // <-- bcrypt pakai Hash
        ]);

        return redirect()->route('admin.user')->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $r, $id)
    {
        $r->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        $user = User::find($id);

        $user->name = $r->name;
        $user->email = $r->email;
        $user->role = $r->role;
        if ($r->password != '') {
            $user->password = Hash::make($r->password);
        }
        $user->save();

        return redirect()->route('admin.user')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('admin.user')->with('success', 'User berhasil dihapus');
    }
}
