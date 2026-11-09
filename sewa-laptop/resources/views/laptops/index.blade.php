@extends('layouts.app')
@section('title', 'Katalog Laptop')
@section('content')
<div class="mb-6">
    <form action="{{ route('laptops.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari merk atau tipe..." class="border rounded px-4 py-2 w-full">
        <select name="sort" class="border rounded px-4 py-2">
            <option value="">Urutkan Harga</option>
            <option value="cheap" {{ request('sort') == 'cheap' ? 'selected' : '' }}>Termurah</option>
            <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Termahal</option>
        </select>
        <button class="bg-blue-600 text-white px-6 py-2 rounded">Cari</button>
    </form>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($laptops as $laptop)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col justify-between">
        <div>
            @if($laptop->image)
                <img src="{{ asset('storage/' . $laptop->image) }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
            @endif
            <div class="p-5">
                <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $laptop->merk }} - {{ $laptop->tipe }}</h3>
                <p class="text-green-600 font-bold mb-3">Rp {{ number_format($laptop->harga_sewa_per_hari, 2) }} / hari</p>
                <div class="mt-4 p-3 bg-gray-50 rounded-lg border text-center text-xs">
                    <div>Stok Tersedia: <span class="font-bold text-green-600">{{ $laptop->stok }} unit</span></div>
                </div>
            </div>
        </div>
        <div class="p-5 pt-0">
            @if($laptop->stok > 0 && $laptop->status == 'ready')
                <a href="{{ route('laptops.show', $laptop->id) }}" class="block text-center bg-blue-600 text-white font-bold py-2 rounded-xl hover:bg-blue-700 transition">Sewa Sekarang</a>
            @else
                <button disabled class="w-full bg-gray-200 text-gray-500 font-bold py-2 rounded-xl cursor-not-allowed">Stok Habis</button>
            @endif
        </div>
    </div>
    @endforeach
</div>
<div class="mt-6">{{ $laptops->links() }}</div>
@endsection
