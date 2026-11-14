<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;

use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('laptop:check-expired')->daily();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
        ]);
        $middleware->trustHosts(
            at: function () {
                $hosts = [];
                if ($host = parse_url(env('APP_URL'), PHP_URL_HOST)) {
                    $hosts[] = $host;
                }
                // Allow PaaS dynamic domains (Railway, Heroku) as fallback
                $hosts[] = '^.+\.up\.railway\.app$';
                $hosts[] = '^.+\.herokuapp\.com$';
                return array_unique($hosts);
            },
            subdomains: false
        );
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'security.headers' => \App\Http\Middleware\SetSecurityHeaders::class,
            'force.https' => \App\Http\Middleware\ForceHttps::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SetSecurityHeaders::class,
            \App\Http\Middleware\ForceHttps::class,
        ]);
        $middleware->append(\App\Http\Middleware\LogRequests::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
