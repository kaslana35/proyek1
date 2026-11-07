<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Menampilkan riwayat sewa untuk user yang sedang login
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with('laptop')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transactions.index', compact('transactions'));
    }
}
