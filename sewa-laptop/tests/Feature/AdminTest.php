<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laptop;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_admin_can_store_laptop(): void
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.laptops.store'), [
            'merk' => 'Dell',
            'tipe' => 'Latitude 5420',
            'spesifikasi' => 'Core i5, 8GB RAM',
            'serial_number' => 'DL12345',
            'harga_sewa_per_hari' => 150000,
            'stok' => 5,
            'image' => UploadedFile::fake()->image('laptop.jpg')
        ]);

        $response->assertRedirect(route('admin.produk.manage'));
        $this->assertDatabaseHas('laptops', ['serial_number' => 'DL12345']);
    }

    public function test_admin_can_approve_and_complete_transaction(): void
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        
        $laptop = Laptop::create([
            'merk' => 'Dell', 'tipe' => 'XPS', 'spesifikasi' => 'i7', 'serial_number' => 'DL99999', 
            'harga_sewa_per_hari' => 100000, 'stok' => 5, 'status' => 'ready'
        ]);

        $transaction = Transaction::create([
            'laptop_id' => $laptop->id, 'user_id' => $user->id, 'nama_peminjam' => 'User',
            'tanggal_mulai' => now(), 'tanggal_selesai' => now()->addDays(1), 
            'total_biaya' => 100000, 'status' => 'pending'
        ]);

        $this->actingAs($admin)->put(route('admin.transactions.approve', $transaction->id))
             ->assertRedirect();
             
        $this->actingAs($admin)->put(route('admin.transactions.complete', $transaction->id))
             ->assertRedirect();

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'status' => 'returned']);
    }
}
