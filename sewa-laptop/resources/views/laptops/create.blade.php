@extends('layouts.app')
@section('title', 'Tambah Laptop')
@section('content')
<div class="max-w-4xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b border-gray-100 dark:border-slate-700 pb-4">Tambah Laptop Baru</h2>
    <form action="{{ route('admin.laptops.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Merk:</label>
                <select name="merk" class="w-full h-11 px-4 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100" required>
                    @foreach(['Asus', 'Acer', 'HP', 'Apple', 'Dell', 'Lenovo', 'Gigabyte', 'MSI'] as $merk)
                        <option value="{{ $merk }}">{{ $merk }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Tipe:</label>
                <input type="text" name="tipe" class="w-full h-11 px-4 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Serial Number:</label>
                <input type="text" name="serial_number" value="{{ old('serial_number') }}" placeholder="Dell=7, Lenovo=8, HP=10, Acer=22, Asus=15, Apple=10-12, MSI=15-20" class="w-full h-11 px-4 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Harga Sewa / Hari (Rp):</label>
                <input type="number" name="harga_sewa_per_hari" class="w-full h-11 px-4 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Stok Awal:</label>
                <input type="number" name="stok" class="w-full h-11 px-4 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Foto Laptop:</label>
                <input type="file" name="image" class="w-full h-11 px-3 py-2 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100" accept="image/*" required>
            </div>
        </div>
        <div class="mt-6">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Spesifikasi Lengkap:</label>
            <textarea name="spesifikasi" rows="4" class="w-full p-4 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100" required></textarea>
        </div>
        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition-smooth shadow-sm">Simpan Produk</button>
            <a href="{{ route('admin.produk.manage') }}" class="bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 dark:hover:bg-slate-600 transition-smooth">Kembali</a>
        </div>
    </form>
</div>
@endsection