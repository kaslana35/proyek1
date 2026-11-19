<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
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

        $transactions = $query->orderBy('created_at', 'desc')->paginate(5)->withQueryString();
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
        $this->authorize('update', $transaction);
        DB::beginTransaction();
        try {
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if ($transaction->status !== 'pending') {
                DB::rollBack();
                return back()->withErrors(['error' => 'Hanya transaksi pending yang dapat disetujui.']);
            }

            $transaction->update(['status' => 'approved']);
            DB::commit();
            return back()->with('success', 'Transaksi berhasil disetujui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyetujui transaksi.']);
        }
    }

    // Admin Menyelesaikan Transaksi (Barang Kembali & Stok Kembali)
    public function complete(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        DB::beginTransaction();
        try {
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if ($transaction->status !== 'approved') {
                DB::rollBack();
                return back()->withErrors(['error' => 'Hanya transaksi yang disetujui yang dapat diselesaikan.']);
            }

            if ($transaction->laptop_id) {
                $laptop = Laptop::lockForUpdate()->find($transaction->laptop_id);
                if ($laptop) {
                    $laptop->increment('stok');
                    $laptop->refresh();
                    if ($laptop->stok > 0 && $laptop->status !== 'ready') {
                        $laptop->update(['status' => 'ready']);
                    }
                }
            }

            $transaction->update(['status' => 'returned']);
            \Illuminate\Support\Facades\Cache::forget('catalog.ready_laptops');
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

        $this->authorize('update', $transaction);

        DB::beginTransaction();
        try {
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            // Kembalikan stok hanya jika unit masih berada di tangan penyewa
            if (in_array($transaction->status, ['pending', 'approved', 'overdue']) && $transaction->laptop_id) {
                $laptop = Laptop::lockForUpdate()->find($transaction->laptop_id);
                if ($laptop) {
                    $laptop->increment('stok');
                    $laptop->refresh();
                    if ($laptop->stok > 0 && $laptop->status !== 'ready') {
                        $laptop->update(['status' => 'ready']);
                    }
                }
            }

            if ($transaction->status !== 'cancelled') {
                $transaction->update(['status' => 'cancelled']);
            }

            \Illuminate\Support\Facades\Cache::forget('catalog.ready_laptops');
            DB::commit();
            return back()->with('success', 'Penyewaan dibatalkan dan stok laptop berhasil dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membatalkan transaksi.']);
        }
    }
}
