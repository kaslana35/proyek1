@extends('layouts.app')
@section('title', 'Tambah Laptop')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Tambah Laptop Baru</h2>
    
    <form action="{{ route('admin.laptops.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <!-- Kolom Kiri -->
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Merk:</label>
                    <select name="merk" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500 bg-white" required>
                        <option value="">-- Pilih Merk --</option>
                        @foreach(['Asus', 'Acer', 'HP', 'Apple', 'Dell', 'Lenovo', 'Gigabyte'] as $merk)
                            <option value="{{ $merk }}" {{ old('merk') == $merk ? 'selected' : '' }}>{{ $merk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Tipe:</label>
                    <input type="text" name="tipe" value="{{ old('tipe') }}" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500" placeholder="Contoh: ROG Strix G15" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Serial Number:</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}" placeholder="Dell=7, Lenovo=8, HP/Acer/Gigabyte=10, Asus/Apple=12" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Harga Sewa / Hari (Rp):</label>
                    <input type="number" step="0.01" name="harga_sewa_per_hari" value="{{ old('harga_sewa_per_hari') }}" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Stok Awal:</label>
                    <input type="number" name="stok" value="{{ old('stok', 1) }}" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Foto Laptop:</label>
                    <input type="file" name="image" class="w-full h-11 px-3 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 bg-white" accept="image/*" required>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Spesifikasi Lengkap:</label>
            <textarea name="spesifikasi" rows="4" class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Processor Intel Core i7-12700H, RAM 16GB DDR5, SSD 512GB NVMe, GPU RTX 3060 6GB..." required>{{ old('spesifikasi') }}</textarea>
        </div>

        <div class="mt-8 flex gap-3 pt-6 border-t">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition">Simpan Produk</button>
            <a href="{{ route('admin.produk.manage') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition">Kembali</a>
        </div>
    </form>
</div>
@endsection
