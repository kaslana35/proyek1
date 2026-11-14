<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Support\Facades\RateLimiter;

class AccountLockoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'user']);
    }

    public function test_record_failed_login_locks_after_10_attempts()
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            $user->recordFailedLogin();
        }

        $user->refresh();
        $this->assertNotNull($user->locked_until);
        $this->assertTrue($user->isLocked());
        $this->assertEquals(10, $user->failed_login_attempts);
    }

    public function test_reset_failed_logins_clears_lockout()
    {
        $user = User::factory()->create();
        for ($i = 0; $i < 10; $i++) {
            $user->recordFailedLogin();
        }
        $user->refresh();
        $this->assertTrue($user->isLocked());

        $user->resetFailedLogins();
        $user->refresh();
        $this->assertNull($user->locked_until);
        $this->assertEquals(0, $user->failed_login_attempts);
        $this->assertFalse($user->isLocked());
    }

    public function test_locked_account_shows_lockout_message()
    {
        $user = User::factory()->create(['password' => 'CorrectPass123!']);
        $user->assignRole('user');
        $user->update(['locked_until' => now()->addMinutes(15), 'failed_login_attempts' => 10]);

        RateLimiter::clear('login:' . request()->ip());
        RateLimiter::clear('login:' . strtolower($user->email));
        RateLimiter::clear('login-global:global-login');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'CorrectPass123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('terkunci', $response->getSession()->get('errors')->first('email'));
    }

    public function test_successful_login_resets_failed_attempts()
    {
        $user = User::factory()->create(['password' => 'CorrectPass123!']);
        $user->assignRole('user');
        $user->update(['failed_login_attempts' => 5]);

        RateLimiter::clear('login:' . request()->ip());
        RateLimiter::clear('login:' . strtolower($user->email));
        RateLimiter::clear('login-global:global-login');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'CorrectPass123!',
        ]);

        $user->refresh();
        $this->assertEquals(0, $user->failed_login_attempts);
        $this->assertNull($user->locked_until);
    }
}
