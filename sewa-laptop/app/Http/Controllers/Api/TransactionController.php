<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request, $id)
    {
        $laptop = Laptop::find($id);
        if (!$laptop || $laptop->stok < 1) {
            return response()->json(['status' => 'error', 'message' => 'Stok habis'], 400);
        }

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $start = new \DateTime($request->tanggal_mulai);
        $end = new \DateTime($request->tanggal_selesai);
        $diff = $start->diff($end)->days;
        $total_biaya = $diff * $laptop->harga_sewa_per_hari;

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'laptop_id' => $laptop->id,
                'user_id' => $request->user()->id,
                'nama_peminjam' => $request->user()->name,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'total_biaya' => $total_biaya,
                'status' => 'pending'
            ]);

            $laptop->decrement('stok');
            if ($laptop->stok == 0) $laptop->update(['status' => 'empty']);

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $transaction], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal'], 500);
        }
    }
}
