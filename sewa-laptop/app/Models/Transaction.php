<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'laptop_id',
        'user_id',
        'nama_peminjam',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_biaya',
        'status'
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
