<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Catat semua respon API (200, 201, 400, 500) ke laravel.log
        if (str_starts_with($request->path(), 'api/')) {
            Log::info("API {$response->getStatusCode()} | {$request->method()} {$request->path()}", [
                'ip' => $request->ip(),
                'user' => $request->user()?->id,
            ]);
        }

        return $response;
    }
}
