<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model {
    // Mass Assignment protection.
    protected $fillable = ['laptop_id', 'user_id', 'nama_peminjam', 'tanggal_mulai', 'tanggal_selesai', 'total_biaya'];

    // Relasi: Transaksi ini milik satu laptop tertentu.
    public function laptop(): BelongsTo {
        return $this->belongsTo(Laptop::class);
    }
}
