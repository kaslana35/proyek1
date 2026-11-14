<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {
    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => ['required', 'string', \Illuminate\Validation\Rules\Password::defaults()->mixedCase()->numbers()->symbols(), 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        auth()->login($user);

        Log::info('Successful registration', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->intended(route('laptops.index'))->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => [
                'required', 
                'email', 
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => ['required', 'string', 'min:8', 'max:255',
                'regex:/[a-z]/',         // minimal 1 huruf kecil
                'regex:/[A-Z]/',         // minimal 1 huruf besar
                'regex:/[0-9]/',         // minimal 1 angka
                'regex:/[@$!%*#?&]/',   // minimal 1 special char
            ],
        ]);

        $user = User::where('email', strip_tags($request->email))->first();

        // Account lockout check (anti-botnet)
        if ($user && $user->isLocked()) {
            $minutes = (int) now()->diffInMinutes($user->locked_until, false);
            return back()->withErrors(['email' => "Akun terkunci sementara. Coba lagi dalam {$minutes} menit."]);
        }

        $credentials = [
            'email' => strip_tags($request->email),
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if ($user) {
                $user->resetFailedLogins();
            }
            $user = auth()->user();
            Log::info('Successful login', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->getRoleNames()->implode(', '),
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);
            return auth()->user()->hasRole('admin') ? redirect()->route('dashboard') : redirect()->route('laptops.index');
        }

        if ($user) {
            $user->recordFailedLogin();
        }

        Log::warning('Failed login attempt', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'email' => $request->email,
            'timestamp' => now(),
        ]);

        return back()->withErrors(['email' => 'Email atau password tidak sesuai.']);
    }

    public function showForgot() { 
        return view('auth.forgot-password'); 
    }

    public function sendResetLink(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);
        Log::info('Password reset link sent', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'timestamp' => now(),
        ]);
        return back()->with('status', 'Link reset password sudah dikirim ke email kamu!');
    }
}
