@extends('layouts.app')
@section('title', 'Manajemen Produk')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Produk</h1>
    <a href="{{ route('admin.laptops.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Tambah Laptop</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="p-3">Foto</th>
                <th class="p-3">Merk & Tipe</th>
                <th class="p-3">Harga</th>
                <th class="p-3">Stok</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($laptops as $laptop)
            <tr class="hover:bg-gray-50">
                <td class="p-3">
                    @if($laptop->image)
                        <img src="{{ asset('storage/' . $laptop->image) }}" class="w-12 h-12 object-cover rounded shadow-sm">
                    @else
                        <span class="text-gray-400 text-xs">No Image</span>
                    @endif
                </td>
                <td class="p-3 font-semibold text-gray-700">{{ $laptop->merk }} - {{ $laptop->tipe }}</td>
                <td class="p-3 text-green-600 font-bold">Rp {{ number_format($laptop->harga_sewa_per_hari, 2) }}</td>
                <td class="p-3 text-gray-700 font-medium">{{ $laptop->stok }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 text-xs rounded-full font-semibold 
                        {{ $laptop->status == 'ready' ? 'bg-green-100 text-gray-800' : 
                          ($laptop->status == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($laptop->status) }}
                    </span>
                </td>
                <td class="p-3 text-center flex justify-center gap-2">
                    <a href="{{ route('admin.laptops.edit', $laptop->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600 transition">Edit</a>
                    <form action="{{ route('admin.laptops.destroy', $laptop->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600 transition">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $laptops->links() }}</div>
@endsection
