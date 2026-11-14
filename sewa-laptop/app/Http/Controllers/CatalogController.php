<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller {
    public function index(Request $request) {
        $query = Laptop::where('stok', '>', 0)->where('status', 'ready');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('merk', 'like', "%{$request->search}%")
                  ->orWhere('tipe', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'cheap') $query->orderBy('harga_sewa_per_hari', 'asc');
            elseif ($request->sort === 'expensive') $query->orderBy('harga_sewa_per_hari', 'desc');
        }

        $laptops = $query->paginate(6)->withQueryString();
        return view('laptops.index', compact('laptops'));
    }

    public function show(Laptop $laptop) {
        return view('laptops.show', compact('laptop'));
    }

    public function storeSewa(Request $request, Laptop $laptop) {
        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'terms' => 'required|accepted',
        ]);

        // Idempotency: prevent double submit within 60 seconds
        $recentDuplicate = Transaction::where('user_id', auth()->id())
            ->where('laptop_id', $laptop->id)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subSeconds(60))
            ->exists();

        if ($recentDuplicate) {
            return back()->withErrors(['error' => 'Permintaan sewa sedang diproses. Mohon tunggu sebentar.']);
        }

        DB::beginTransaction();
        try {
            // Re-check stock inside transaction (prevent race condition)
            $laptop = Laptop::lockForUpdate()->find($laptop->id);
            if (!$laptop || $laptop->stok < 1 || $laptop->status !== 'ready') {
                DB::rollBack();
                return back()->withErrors(['stok' => 'Maaf, stok ready laptop ini sedang habis atau tidak tersedia.']);
            }

            $diff = (new \DateTime($request->tanggal_mulai))->diff(new \DateTime($request->tanggal_selesai))->days;

            Transaction::create([
                'laptop_id' => $laptop->id,
                'user_id' => auth()->id(),
                'nama_peminjam' => auth()->user()->name,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'total_biaya' => $diff * $laptop->harga_sewa_per_hari,
                'status' => 'pending'
            ]);

            $laptop->decrement('stok');
            if ($laptop->stok <= 0) {
                $laptop->update(['status' => 'empty']);
            }

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses penyewaan.']);
        }
    }
}
