<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SetSecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Jika response adalah RedirectResponse, lewati penambahan headers
        // agar tidak terjadi Error 500 (TypeError)
        if ($response instanceof RedirectResponse) {
            return $response;
        }

        // Core Security Headers - pasti Response type
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Download-Options', 'noopen');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy - hanya jika belum ada
        if (! $response->headers->has('Content-Security-Policy')) {
            $csp = "default-src 'self'; "
                 . "script-src 'self' 'unsafe-inline'; "
                 . "style-src 'self' 'unsafe-inline'; "
                 . "img-src 'self' data:; "
                 . "connect-src 'self'; "
                 . "font-src 'self'; "
                 . "frame-src 'none'; ";

            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}