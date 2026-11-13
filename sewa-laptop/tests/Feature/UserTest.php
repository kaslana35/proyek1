<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laptop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'user']);
    }

    public function test_guest_can_view_catalog(): void
    {
        $this->get(route('laptops.index'))->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $this->post('/register', [
            'name' => 'Bayu', 'email' => 'bayu@gmail.com',
            'password' => 'TestPass123@', 'password_confirmation' => 'TestPass123@',
        ])->assertRedirect();
        
        $this->assertDatabaseHas('users', ['email' => 'bayu@gmail.com']);
    }

    public function test_user_can_rent_laptop(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole('user');

        $laptop = Laptop::create([
            'merk' => 'Asus', 'tipe' => 'Zenbook', 'spesifikasi' => 'i5', 'serial_number' => 'AS1234567890123',
            'harga_sewa_per_hari' => 100000, 'stok' => 3, 'status' => 'ready'
        ]);

        $this->actingAs($user)->post(route('laptops.sewa', $laptop->id), [
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(2)->toDateString(),
            'terms' => 'on'
        ])->assertRedirect(route('transactions.index'));
        
        $this->assertDatabaseHas('transactions', ['user_id' => $user->id, 'status' => 'pending']);
    }
}
