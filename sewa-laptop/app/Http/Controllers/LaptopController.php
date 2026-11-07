<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LaptopController extends Controller {
    // Katalog khusus User (menampilkan yang statusnya 'ready' dan stok > 0)
    public function index() {
        $laptops = Laptop::where('status', 'ready')->where('stok', '>', 0)->paginate(10);
        return view('laptops.index', compact('laptops'));
    }

    // Manajemen Produk khusus Admin
    public function manage() {
        $laptops = Laptop::paginate(10);
        return view('admin.laptops.manage', compact('laptops'));
    }

    public function create() { return view('laptops.create'); }

    public function store(Request $request) {
        $data = $request->validate([
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'harga_sewa_per_hari' => 'required|numeric',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:ready,maintenance,empty,not_ready'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('laptops', 'public');
        }

        Laptop::create($data);
        return redirect()->route('admin.laptops.manage')->with('success', 'Laptop berhasil ditambah!');
    }

    public function edit(Laptop $laptop) {
        return view('admin.laptops.edit', compact('laptop'));
    }

    public function update(Request $request, Laptop $laptop) {
        $data = $request->validate([
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_sewa_per_hari' => 'required|numeric',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:ready,maintenance,empty,not_ready'
        ]);

        if ($request->hasFile('image')) {
            if ($laptop->image) {
                Storage::disk('public')->delete($laptop->image);
            }
            $data['image'] = $request->file('image')->store('laptops', 'public');
        }

        $laptop->update($data);
        return redirect()->route('admin.laptops.manage')->with('success', 'Laptop berhasil diperbarui!');
    }

    public function destroy(Laptop $laptop) {
        if ($laptop->image) {
            Storage::disk('public')->delete($laptop->image);
        }
        $laptop->delete();
        return back()->with('success', 'Laptop dihapus!');
    }

    public function show(Laptop $laptop) {
        return view('laptops.show', compact('laptop'));
    }

    // Proses Sewa dengan Pengurangan Stok Otomatis
    public function storeSewa(Request $request, Laptop $laptop) {
        // Cek stok apakah mencukupi
        if ($laptop->stok < 1) {
            return back()->withErrors(['stok' => 'Maaf, stok laptop ini sedang habis.']);
        }

        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $start = new \DateTime($request->tanggal_mulai);
        $end = new \DateTime($request->tanggal_selesai);
        $diff = $start->diff($end)->days;
        
        $total_biaya = $diff * $laptop->harga_sewa_per_hari;

        // Menggunakan Database Transaction untuk keamanan mutlak
        DB::beginTransaction();
        try {
            // 1. Buat Transaksi
            Transaction::create([
                'laptop_id' => $laptop->id,
                'user_id' => auth()->id(),
                'nama_peminjam' => auth()->user()->name,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'total_biaya' => $total_biaya
            ]);

            // 2. Kurangi Stok Laptop 1 unit
            $laptop->decrement('stok');

            // 3. Jika stok menjadi 0, otomatis update status menjadi 'empty'
            if ($laptop->stok == 0) {
                $laptop->update(['status' => 'empty']);
            }

            DB::commit();
            return redirect()->route('laptops.index')->with('success', 'Penyewaan diajukan! Stok laptop berhasil berkurang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses penyewaan. Silakan coba lagi.']);
        }
    }
}
