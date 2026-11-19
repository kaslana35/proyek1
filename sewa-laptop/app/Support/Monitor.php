<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Facade;

class Monitor extends Facade
{
    public static function log(string $event, array $context = []): void
    {
        Log::channel('security')->info('MONITOR: ' . $event, array_merge([
            'timestamp' => now()->toIso8601String(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
        ], $context));
    }

    public static function recordLoginAttempt($success, $userId = null): void
    {
        self::log('login_attempt', [
            'success' => $success,
            'user_id' => $userId,
        ]);
    }

    public static function recordFailedLogin($email): void
    {
        self::log('failed_login', ['email' => $email]);
    }

    public static function recordTransaction($action, $transactionId): void
    {
        self::log('transaction_' . $action, ['transaction_id' => $transactionId]);
    }

    public static function recordPasswordReset($email): void
    {
        self::log('password_reset', ['email' => $email]);
    }
}