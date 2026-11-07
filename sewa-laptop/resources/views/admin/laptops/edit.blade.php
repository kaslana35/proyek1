@extends('layouts.app')
@section('title', 'Edit Laptop')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">Edit Laptop</h2>
    <form action="{{ route('admin.laptops.update', $laptop->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Merk:</label>
            <select name="merk" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500" required>
                @foreach(['Asus', 'Acer', 'HP', 'Apple', 'Dell', 'Lenovo', 'Gigabyte'] as $merk)
                    <option value="{{ $merk }}" {{ $laptop->merk == $merk ? 'selected' : '' }}>
                        {{ $merk }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tipe:</label>
            <input type="text" name="tipe" value="{{ $laptop->tipe }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa/Hari:</label>
            <input type="number" step="0.01" name="harga_sewa_per_hari" value="{{ $laptop->harga_sewa_per_hari }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Stok:</label>
            <input type="number" name="stok" value="{{ $laptop->stok }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">
                <option value="ready" {{ $laptop->status == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="maintenance" {{ $laptop->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="empty" {{ $laptop->status == 'empty' ? 'selected' : '' }}>Empty</option>
                <option value="not_ready" {{ $laptop->status == 'not_ready' ? 'selected' : '' }}>Not Ready</option>
            </select>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Foto Baru (Opsional):</label>
            <input type="file" name="image" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">
            @if($laptop->image)
                <img src="{{ asset('storage/' . $laptop->image) }}" class="w-24 h-24 object-cover rounded mt-3 shadow">
            @endif
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">Update</button>
            <a href="{{ route('admin.laptops.manage') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Kembali</a>
        </div>
    </form>
</div>
@endsection
