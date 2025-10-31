<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect sesuai role + kirim flash message sukses
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard')->with('status', 'Login berhasil! Selamat datang Admin.');
            } else {
                return redirect('/user/dashboard')->with('status', 'Login berhasil! Selamat datang User.');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
