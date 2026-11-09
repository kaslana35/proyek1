@extends('layouts.app')
@section('title', 'Manajemen Produk')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Produk Laptop</h1>
        <p class="text-sm text-gray-500">Kelola master data produk, spesifikasi, dan status ketersediaan.</p>
    </div>
    <a href="{{ route('admin.laptops.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-sm">+ Tambah Produk</a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-6 text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider border-b">
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
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($laptops as $l)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4">
                        @if($l->image)
                            <img src="{{ asset('storage/' . $l->image) }}" alt="{{ $l->merk }}" class="w-14 h-14 object-cover rounded-xl border border-gray-200 shadow-sm">
                        @else
                            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 text-xs">No Image</div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-gray-800">{{ $l->merk }}</div>
                        <div class="text-xs text-gray-500">{{ $l->tipe }}</div>
                    </td>
                    <td class="p-4 font-mono text-xs text-gray-600 bg-gray-50 rounded px-2 py-1 inline-block mt-3">
                        {{ $l->serial_number ?? '-' }}
                    </td>
                    <td class="p-4 font-semibold text-gray-700">
                        Rp {{ number_format($l->harga_sewa_per_hari) }}
                    </td>
                    <td class="p-4">
                        <span class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg text-xs">
                            {{ $l->stok }} Unit
                        </span>
                    </td>
                    <td class="p-4">
                        @if($l->status == 'ready')
                            <span class="bg-green-50 text-green-700 font-semibold px-3 py-1 rounded-full text-xs border border-green-200">Ready</span>
                        @elseif($l->status == 'maintenance')
                            <span class="bg-yellow-50 text-yellow-700 font-semibold px-3 py-1 rounded-full text-xs border border-yellow-200">Maintenance</span>
                        @else
                            <span class="bg-red-50 text-red-700 font-semibold px-3 py-1 rounded-full text-xs border border-red-200">Not Ready</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.laptops.edit', $l->id) }}" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">Edit</a>
                            <form action="{{ route('admin.laptops.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-100 transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">Belum ada data produk laptop.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t bg-gray-50">
        {{ $laptops->links() }}
    </div>
</div>
@endsection
