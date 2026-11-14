<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

use Illuminate\Support\Facades\Gate;
use App\Models\Laptop;
use App\Models\Transaction;
use App\Policies\LaptopPolicy;
use App\Policies\TransactionPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perMinute(5)->by(strtolower($request->input('email', ''))),
            ];
        });

        // Global login rate limiter (anti-botnet IP rotation)
        RateLimiter::for('login-global', function (Request $request) {
            return Limit::perMinute(100)->by('global-login');
        });

        RateLimiter::for('password-reset', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perMinute(3)->by(strtolower($request->input('email', ''))),
            ];
        });

        Gate::policy(Laptop::class, LaptopPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);

        // Production safety: warn if SESSION_DOMAIN allows subdomain sharing
        if (app()->environment('production') && config('session.domain') && str_starts_with(config('session.domain'), '.')) {
            throw new \RuntimeException('SESSION_DOMAIN must not start with a dot in production (subdomain cookie sharing risk). Set SESSION_DOMAIN=null.');
        }
    }
}
