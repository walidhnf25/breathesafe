<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    public function register(Request $request)
    {
        return view('register');
    }

    public function proseslogin(Request $request)
    {
        if (Auth::guard('user')->attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::guard('user')->user();

            if ($user->role === 'User') {
                return redirect('/live-stream');
            } elseif ($user->role === 'Administrator') {
                return redirect('/number-of-violations');
            } else {
                Auth::guard('user')->logout();
                return redirect('/')->with(['warning' => 'Role tidak diizinkan.']);
            }
        } else {
            return redirect('/')->with(['warning' => 'Nomor Telepon atau Password salah']);
        }
    }

    public function registerakun(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
        ]);
    
        $existingUser = User::where('username', $request->username)->first();
    
        if ($existingUser) {
            return redirect('/register')->with(['warning' => 'Akun sudah ada']);
        }
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->role = 'User';
        $user->password = Hash::make($request->password);
        $user->save();
    
        return redirect()->route('index')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function proseslogout()
    {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            return redirect('/');
        }
    }
}
