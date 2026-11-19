<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminIpAllowlist
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = config('admin.allowed_ips', []);

        // If no IPs configured, allow all (default)
        if (empty($allowedIps)) {
            return $next($request);
        }

        $clientIp = $request->ip();

        foreach ($allowedIps as $allowed) {
            if ($clientIp === $allowed || $this->ipInRange($clientIp, $allowed)) {
                return $next($request);
            }
        }

        abort(403, 'Akses admin dibatasi dari IP ini.');
    }

    private function ipInRange(string $ip, string $range): bool
    {
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }

        [$subnet, $mask] = explode('/', $range);
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = ~((1 << (32 - (int) $mask)) - 1);

        return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
    }
}
