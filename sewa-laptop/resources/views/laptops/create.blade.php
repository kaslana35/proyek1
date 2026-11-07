@extends('layouts.app')
@section('title', 'Tambah Laptop')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4 text-gray-850 border-b pb-2">Tambah Laptop Baru</h2>
    <form action="{{ route('admin.laptops.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Merk:</label>
            <select name="merk" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Pilih Merk --</option>
                @foreach(['Asus', 'Acer', 'HP', 'Apple', 'Dell', 'Lenovo', 'Gigabyte'] as $merk)
                    <option value="{{ $merk }}">{{ $merk }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tipe:</label>
            <input type="text" name="tipe" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Foto Laptop:</label>
            <input type="file" name="image" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" accept="image/*" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa/Hari:</label>
            <input type="number" step="0.01" name="harga_sewa_per_hari" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Stok:</label>
            <input type="number" name="stok" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="ready">Ready</option>
                <option value="maintenance">Maintenance</option>
                <option value="empty">Empty</option>
                <option value="not_ready">Not Ready</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Simpan</button>
            <a href="{{ route('admin.laptops.manage') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Kembali</a>
        </div>
    </form>
</div>
@endsection
