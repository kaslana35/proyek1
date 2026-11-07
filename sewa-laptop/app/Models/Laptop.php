<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laptop extends Model {
    // Mass Assignment: Hanya field ini yang diizinkan untuk diisi secara massal.
    // Mencegah penyisipan data ilegal ke field sensitif.
    protected $fillable = ['merk', 'tipe', 'image', 'harga_sewa_per_hari', 'status', 'stok', 'is_available'];

    // Relasi: Satu laptop bisa memiliki banyak transaksi penyewaan.
    public function transactions(): HasMany {
        return $this->hasMany(Transaction::class);
    }
}
