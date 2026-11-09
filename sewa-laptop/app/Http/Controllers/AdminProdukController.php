<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Http\Requests\StoreLaptopRequest;
use App\Http\Requests\UpdateLaptopRequest;
use Illuminate\Support\Facades\Storage;

class AdminProdukController extends Controller {
    public function manageProduk() {
        $laptops = Laptop::paginate(10);
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

        return redirect()->route('admin.produk.manage')->with('success', 'Laptop berhasil ditambah!');
    }

    public function edit(Laptop $laptop) {
        return view('admin.laptops.edit', compact('laptop'));
    }

    public function update(UpdateLaptopRequest $request, Laptop $laptop) {
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
        }

        return redirect()->route('admin.produk.manage')->with('success', 'Data laptop & stok berhasil diperbarui!');
    }

    public function destroy(Laptop $laptop) {
        if ($laptop->image) Storage::disk('public')->delete($laptop->image);
        $laptop->delete();
        return redirect()->route('admin.produk.manage')->with('success', 'Laptop dihapus!');
    }
}
