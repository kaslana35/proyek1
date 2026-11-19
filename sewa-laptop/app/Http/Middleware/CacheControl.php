<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControl
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent cache poisoning: only vary on Accept-Encoding
        if (! $response->headers->has('Vary')) {
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        // No caching for authenticated/admin/auth pages
        $path = $request->path();
        if (str_starts_with($path, 'admin/') || str_starts_with($path, 'login') ||
            str_starts_with($path, 'register') || str_starts_with($path, 'profile') ||
            str_starts_with($path, 'riwayat-sewa') || str_starts_with($path, 'laptops/') && $request->isMethod('POST')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }

        return $response;
    }
}
