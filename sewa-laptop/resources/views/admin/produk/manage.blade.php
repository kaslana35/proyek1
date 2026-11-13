@extends('layouts.app')
@section('title', 'Manajemen Produk')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manajemen Produk Laptop</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola master data produk, spesifikasi, dan status ketersediaan.</p>
    </div>
    <a href="{{ route('admin.laptops.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition-smooth shadow-sm">+ Tambah Produk</a>
</div>

@if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 p-4 rounded-xl mb-6 text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[600px]">
            <thead class="bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-gray-300 uppercase text-xs tracking-wider border-b border-gray-100 dark:border-slate-700">
                <tr>
                    <th class="p-4 font-semibold">Foto</th>
                    <th class="p-4 font-semibold">Merek & Tipe</th>
                    <th class="p-4 font-semibold">Serial Number</th>
                    <th class="p-4 font-semibold">Harga / Hari</th>
                    <th class="p-4 font-semibold">Stok Ready</th>
                    <th class="p-4 font-semibold">Status</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($laptops as $l)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-smooth">
                    <td class="p-4">
                        @if($l->image)
                            <img src="{{ asset('storage/' . $l->image) }}" alt="{{ $l->merk }}" class="w-14 h-14 object-cover rounded-xl border border-gray-200 dark:border-slate-600 shadow-sm">
                        @else
                            <div class="w-14 h-14 bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-gray-400 dark:text-gray-500 text-xs">No Image</div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-gray-800 dark:text-gray-100">{{ $l->merk }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $l->tipe }}</div>
                    </td>
                    <td class="p-4 font-mono text-xs text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 rounded px-2 py-1 inline-block mt-3">{{ $l->serial_number }}</td>
                    <td class="p-4 text-gray-700 dark:text-gray-300 font-semibold">Rp {{ number_format($l->harga_sewa_per_hari) }}</td>
                    <td class="p-4 text-gray-700 dark:text-gray-300">{{ $l->stok }}</td>
                    <td class="p-4">
                        <span class="px-2.5 py-1 text-xs rounded-full font-semibold {{ $l->status == 'ready' ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800' : 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-slate-600' }}">
                            {{ ucfirst($l->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-1">
                        <a href="{{ route('admin.laptops.edit', $l->id) }}" class="bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-gray-200 dark:hover:bg-slate-600 transition-smooth">Edit</a>
                        <form action="{{ route('admin.laptops.destroy', $l->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-100 dark:hover:bg-red-900/40 transition-smooth">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $laptops->links() }}</div>
</div>
@endsection