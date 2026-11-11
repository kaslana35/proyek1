<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
        if ($laptop->stok < 1 || $laptop->status !== 'ready') {
            return back()->withErrors(['stok' => 'Maaf, stok ready laptop ini sedang habis atau tidak tersedia.']);
        }

        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'terms' => 'required|accepted',
        ]);
        
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
        return redirect()->route('transactions.index')->with('success', 'Berhasil!');
    }
}
