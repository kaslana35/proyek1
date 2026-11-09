@extends('layouts.app')
@section('title', 'Edit Laptop')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Edit Data Produk</h2>
    
    <form action="{{ route('admin.laptops.update', $laptop->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <!-- Kolom Kiri -->
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Merk:</label>
                    <select name="merk" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500 bg-white" required>
                        @foreach(['Asus', 'Acer', 'HP', 'Apple', 'Dell', 'Lenovo', 'Gigabyte'] as $merk)
                            <option value="{{ $merk }}" {{ $laptop->merk == $merk ? 'selected' : '' }}>{{ $merk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Tipe:</label>
                    <input type="text" name="tipe" value="{{ $laptop->tipe }}" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Serial Number:</label>
                    <input type="text" name="serial_number" value="{{ $laptop->serial_number }}" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Harga Sewa / Hari:</label>
                    <input type="number" step="0.01" name="harga_sewa_per_hari" value="{{ $laptop->harga_sewa_per_hari }}" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Tambah Stok (Opsional):</label>
                    <input type="number" name="stok_baru" placeholder="Masukkan jumlah penambahan" class="w-full h-11 px-4 border rounded-xl focus:ring-2 focus:ring-blue-500">
                    <p class="text-[11px] text-gray-400 mt-1">Stok saat ini: {{ $laptop->stok }} unit</p>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Ganti Foto (Opsional):</label>
                    <input type="file" name="image" class="w-full h-11 px-3 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 bg-white">
                </div>
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Spesifikasi Lengkap:</label>
            <textarea name="spesifikasi" rows="4" class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Processor Intel Core i7..." required>{{ $laptop->spesifikasi }}</textarea>
        </div>

        @if($laptop->image)
            <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-4">
                <img src="{{ asset('storage/' . $laptop->image) }}" class="w-16 h-16 object-cover rounded-xl border border-gray-200">
                <div>
                    <p class="text-xs font-semibold text-gray-700">Foto Saat Ini</p>
                    <p class="text-[11px] text-gray-400">Foto akan tetap sama jika tidak mengunggah file baru.</p>
                </div>
            </div>
        @endif

        <div class="mt-8 flex gap-3 pt-6 border-t">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition">Update Produk</button>
            <a href="{{ route('admin.produk.manage') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition">Kembali</a>
        </div>
    </form>
</div>
@endsection
