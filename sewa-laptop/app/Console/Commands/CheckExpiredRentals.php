<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CheckExpiredRentals extends Command
{
    protected $signature = 'rentals:check-expired';
    protected $description = 'Otomatis kembalikan stok laptop dan update status jika masa sewa selesai';

    public function handle()
    {
        $today = date('Y-m-d');

        // Ambil transaksi yang sudah disetujui (approved) dan tanggal selesainya hari ini atau lewat
        $expiredTransactions = Transaction::where('status', 'approved')
            ->where('tanggal_selesai', '<', $today)
            ->get();

        if ($expiredTransactions->isEmpty()) {
            $this->info('Tidak ada rental yang kadaluwarsa hari ini.');
            return;
        }

        foreach ($expiredTransactions as $trx) {
            DB::beginTransaction();
            try {
                $laptop = $trx->laptop;
                if ($laptop) {
                    // 1. Tambah kembali stok laptop (Update Stok Otomatis)
                    $laptop->increment('stok');

                    // 2. Jika sebelumnya 'empty', kembalikan jadi 'ready'
                    if ($laptop->status === 'empty') {
                        $laptop->update(['status' => 'ready']);
                    }
                }

                // 3. Ubah status transaksi menjadi 'completed' (selesai)
                $trx->update(['status' => 'completed']);

                DB::commit();
                $this->info("Rental #{$trx->id} selesai. Stok laptop ID {$trx->laptop_id} dikembalikan.");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal memproses rental #{$trx->id}: " . $e->getMessage());
            }
        }
    }
}
