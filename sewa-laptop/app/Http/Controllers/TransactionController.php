<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Riwayat Sewa khusus User
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', auth()->id())
            ->with('laptop');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }

    // Manajemen Transaksi khusus Admin
    public function adminIndex()
    {
        $transactions = Transaction::with(['laptop', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    // Admin Menyetujui Transaksi
    public function approve(Transaction $transaction)
    {
        $transaction->update(['status' => 'approved']);
        return back()->with('success', 'Transaksi berhasil disetujui!');
    }

    // Admin Menyelesaikan Transaksi (Barang Kembali & Stok Kembali)
    public function complete(Transaction $transaction)
    {
        if ($transaction->status !== 'approved') {
            return back()->withErrors(['error' => 'Hanya transaksi yang disetujui yang dapat diselesaikan.']);
        }

        DB::beginTransaction();
        try {
            $laptop = $transaction->laptop;
            if ($laptop) {
                $laptop->increment('stok');
                if ($laptop->status === 'empty') {
                    $laptop->update(['status' => 'ready']);
                }
            }

            $transaction->update(['status' => 'returned']);
            DB::commit();

            return back()->with('success', 'Barang berhasil dikembalikan dan stok unit bertambah kembali!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyelesaikan transaksi.']);
        }
    }

    // Admin / User Membatalkan Transaksi (Dengan Pengembalian Stok)
    public function cancel(Transaction $transaction)
    {
        // Keamanan: Cek kepemilikan jika user biasa
        if (auth()->user()->hasRole('user') && $transaction->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak');
        }

        DB::beginTransaction();
        try {
            // Jika transaksi belum dibatalkan sebelumnya, kembalikan stok
            if ($transaction->status !== 'cancelled') {
                $laptop = $transaction->laptop;
                if ($laptop) {
                    $laptop->increment('stok');
                    if ($laptop->status === 'empty') {
                        $laptop->update(['status' => 'ready']);
                    }
                }
            }

            $transaction->update(['status' => 'cancelled']);
            DB::commit();

            return back()->with('success', 'Penyewaan dibatalkan dan stok laptop berhasil dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membatalkan transaksi.']);
        }
    }
}
