<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laptop extends Model {
    use SoftDeletes;
    protected $fillable = [
        'merk',
        'tipe',
        'spesifikasi',
        'serial_number',
        'image',
        'harga_sewa_per_hari',
        'status',
        'stok',
        'maintenance_stok',
        'not_ready_stok'
    ];

    protected static function booted() {
        static::saving(function ($laptop) {
            // Mencegah stok negatif
            if ($laptop->stok < 0) $laptop->stok = 0;
            if ($laptop->maintenance_stok < 0) $laptop->maintenance_stok = 0;
            if ($laptop->not_ready_stok < 0) $laptop->not_ready_stok = 0;

            // Update status berdasarkan stok ready
            $laptop->status = ($laptop->stok > 0) ? 'ready' : 'empty';

            // Jika stok < 0 (karena error sistem), paksa jadi 0
            if ($laptop->stok < 0) $laptop->stok = 0;
            if ($laptop->maintenance_stok < 0) $laptop->maintenance_stok = 0;
            if ($laptop->not_ready_stok < 0) $laptop->not_ready_stok = 0;
        });
    }

    public function inspections() {
        return $this->hasMany(UnitInspection::class);
    }

    public function transactions(): HasMany {
        return $this->hasMany(Transaction::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function averageRating() {
        return $this->reviews()->avg('rating') ?: 0;
    }
}
