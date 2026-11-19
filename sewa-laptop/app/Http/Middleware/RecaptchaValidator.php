<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RecaptchaValidator
{
    public function handle(Request $request, Closure $next)
    {
        // If reCAPTCHA keys are not configured, allow all requests (development mode)
        $siteKey = config('recaptcha.site_key');
        
        if (empty($siteKey)) {
            // No keys configured - allow request but log warning
            // In production, this should be prevented or keys should be set
            return $next($request);
        }

        // Only validate on login and register pages
        if (!in_array($request->path(), ['login', 'register'])) {
            return $next($request);
        }

        $response = $request->session()->get('g-recaptcha-response');

        if (empty($response)) {
            return back()
                ->withErrors(['recaptcha' => 'Harap lengkapi verifikasi CAPTCHA'])
                ->withInput();
        }

        $secretKey = config('recaptcha.secret_key');
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $response;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $verifyUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, false);
        
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        if (!$data || !$data['success']) {
            return back()
                ->withErrors(['recaptcha' => 'Verifikasi CAPTCHA gagal. Silakan coba lagi.'])
                ->withInput();
        }

        return $next($request);
    }
}