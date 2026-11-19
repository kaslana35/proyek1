<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Http\Requests\StoreLaptopRequest;
use App\Http\Requests\UpdateLaptopRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProdukController extends Controller {
    public function manageProduk(Request $request) {
        $query = Laptop::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('merk', 'like', "%{$request->search}%")
                  ->orWhere('tipe', 'like', "%{$request->search}%")
                  ->orWhere('serial_number', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['ready', 'empty'])) {
            $query->where('status', $request->status);
        }

        $laptops = $query->orderBy('created_at', 'desc')->paginate(6)->withQueryString();
        return view('admin.produk.manage', compact('laptops'));
    }

    public function create() {
        return view('laptops.create');
    }

    public function store(StoreLaptopRequest $request) {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('laptops', 'public');
        }
        
        $data['status'] = 'ready';
        Laptop::create($data);

        \Illuminate\Support\Facades\Cache::forget('catalog.ready_laptops');

        return redirect()->route('admin.produk.manage')->with('success', 'Laptop berhasil ditambah!');
    }

    public function edit(Laptop $laptop) {
        return view('admin.laptops.edit', compact('laptop'));
    }

    public function update(UpdateLaptopRequest $request, Laptop $laptop) {
        $this->authorize('update', $laptop);
        
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($laptop->image) Storage::disk('public')->delete($laptop->image);
            $data['image'] = $request->file('image')->store('laptops', 'public');
        } else {
            $data['image'] = $laptop->image;
        }

        $laptop->update([
            'merk' => $data['merk'],
            'tipe' => $data['tipe'],
            'spesifikasi' => $data['spesifikasi'],
            'serial_number' => $data['serial_number'],
            'image' => $data['image'],
            'harga_sewa_per_hari' => $data['harga_sewa_per_hari'],
        ]);

        if ($request->filled('stok_baru') && $request->stok_baru > 0) {
            $laptop->increment('stok', $request->stok_baru);
            $laptop->refresh();
            if ($laptop->stok > 0) {
                $laptop->update(['status' => 'ready']);
            }
        }

        \Illuminate\Support\Facades\Cache::forget('catalog.ready_laptops');

        return redirect()->route('admin.produk.manage')->with('success', 'Data laptop & stok berhasil diperbarui!');
    }

    public function destroy(Laptop $laptop) {
        // Prevent deletion if there are active transactions
        if ($laptop->transactions()->whereIn('status', ['pending', 'approved', 'active'])->exists()) {
            return back()->withErrors(['error' => 'Laptop tidak dapat dihapus karena masih memiliki transaksi aktif.']);
        }

        if ($laptop->image) Storage::disk('public')->delete($laptop->image);
        $laptop->delete();
        \Illuminate\Support\Facades\Cache::forget('catalog.ready_laptops');
        \App\Support\SecurityLogger::log('product_deleted', ['laptop_id' => $laptop->id, 'merk' => $laptop->merk]);
        return redirect()->route('admin.produk.manage')->with('success', 'Laptop dihapus (soft delete)!');
    }
}
