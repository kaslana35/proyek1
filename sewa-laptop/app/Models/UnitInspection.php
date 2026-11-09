<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitInspection extends Model
{
    protected $fillable = ['laptop_id', 'status_sebelum', 'status_sesudah', 'jumlah', 'catatan'];

    public function laptop() {
        return $this->belongsTo(Laptop::class);
    }
}
