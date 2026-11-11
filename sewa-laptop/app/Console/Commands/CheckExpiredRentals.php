<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;

class CheckExpiredRentals extends Command
{
    protected $signature = 'laptop:check-expired';
    protected $description = 'Tandai transaksi yang sudah melewati tanggal selesai';

    public function handle()
    {
        $expiredCount = Transaction::where('status', 'approved')
            ->where('tanggal_selesai', '<', now()->toDateString())
            ->update(['status' => 'overdue']);

        $this->info("Berhasil menandai {$expiredCount} transaksi sebagai overdue.");
    }
}
