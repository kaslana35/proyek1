<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => [
                'required', 
                'email', 
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => 'required|string|min:8',
        ]);

        $credentials = [
            'email' => strip_tags($request->email),
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return auth()->user()->hasRole('admin') ? redirect()->route('dashboard') : redirect()->route('laptops.index');
        }

        return back()->withErrors(['email' => 'Email atau password tidak sesuai.']);
    }

    public function showForgot() { return view('auth.forgot-password'); }

    public function sendResetLink(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);
        return back()->with('status', 'Link reset password sudah dikirim ke email kamu!');
    }
}
