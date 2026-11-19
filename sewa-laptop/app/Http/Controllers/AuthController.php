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

        if (session()->has('pending_sewa')) {
            return redirect()->route('sewa.process_pending');
        }

        $intended = session()->pull('url.intended', route('laptops.index'));
        if (is_string($intended) && str_contains($intended, '/sewa')) {
            $intended = preg_replace('#/sewa$#', '', $intended);
        }
        return redirect($intended ?: route('laptops.index'))->with('success', 'Registrasi berhasil! Selamat datang.');
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

        // Global failed login counter (anti-botnet flood)
        $globalKey = 'global_failed_logins';
        $globalAttempts = (int) \Illuminate\Support\Facades\Cache::get($globalKey, 0);
        if ($globalAttempts >= 50) {
            Log::warning('Global login lockout triggered', ['ip' => $request->ip(), 'attempts' => $globalAttempts]);
            return back()->withErrors(['email' => 'Terlalu banyak percobaan login. Coba lagi dalam 5 menit.']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if ($user) {
                $user->resetFailedLogins();
            }
            \Illuminate\Support\Facades\Cache::forget('global_failed_logins');
            $user = auth()->user();
            Log::info('Successful login', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->getRoleNames()->implode(', '),
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);
            if (auth()->user()->hasRole('admin')) {
                return redirect()->route('dashboard');
            }

            // Proses sewa pending (jika ada) setelah login
            if (session()->has('pending_sewa')) {
                return redirect()->route('sewa.process_pending');
            }

            // Redirect to intended URL, but convert POST sewa route to show page
            $intended = session()->pull('url.intended', route('laptops.index'));
            if (is_string($intended) && str_contains($intended, '/sewa')) {
                $intended = preg_replace('#/sewa$#', '', $intended);
            }
            return redirect($intended ?: route('laptops.index'))->with('success', 'Login berhasil! Selamat datang.');
        }

        if ($user) {
            $user->recordFailedLogin();
        }

        // Increment global failed login counter
        $globalKey = 'global_failed_logins';
        $attempts = (int) \Illuminate\Support\Facades\Cache::get($globalKey, 0) + 1;
        \Illuminate\Support\Facades\Cache::put($globalKey, $attempts, now()->addMinutes(5));

        \App\Support\SecurityLogger::log('failed_login_attempt', ['email' => $request->email]);

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
        
        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            Log::info('Password reset link sent', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
